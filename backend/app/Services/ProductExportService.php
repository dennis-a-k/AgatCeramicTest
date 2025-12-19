<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductExportService
{
    protected $productService;

    // Constants for headers and styling
    const EXPORT_HEADERS = [
        'Артикул',
        'Название',
        'Цена',
        'Единица',
        'Код товара',
        'Описание',
        'Категория',
        'Бренд',
        'Цвет',
        'Опубликовано',
        'Распродажа',
        'Поверхность',
        'Рисунок',
        'Страна',
        'Коллекция'
    ];

    const TEMPLATE_BASE_HEADERS = [
        'Артикул',
        'Название',
        'Цена',
        'Единица',
        'Код товара',
        'Описание',
        'Категория',
        'Бренд',
        'Цвет',
        'Опубликовано',
        'Распродажа',
        'Страна'
    ];

    const SPECIFIC_CATEGORIES = ['Керамогранит', 'Плитка', 'Мозаика', 'Клинкер', 'Ступени'];
    const SPECIFIC_HEADERS = ['Поверхность', 'Рисунок', 'Коллекция'];

    const HEADER_FILL_COLOR_EXPORT = '95B3D7';
    const HEADER_FILL_COLOR_TEMPLATE = 'C4D79B';
    const HEADER_FILL_COLOR_EDIT_TEMPLATE = 'DA9694';
    const COLUMN_WIDTH_FACTOR = 1.2;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function export(): StreamedResponse
    {
        $products = $this->productService->getAllProductsForExport();
        $uniqueAttributes = $this->getUniqueAttributes($products);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = array_merge(self::EXPORT_HEADERS, $uniqueAttributes, $this->getImageHeaders());
        $this->setHeaders($sheet, $headers);
        $this->styleHeaders($sheet, self::HEADER_FILL_COLOR_EXPORT);
        $this->setColumnWidths($sheet, $headers);

        $this->populateExportData($sheet, $products, $uniqueAttributes);

        return $this->createResponse($spreadsheet, 'products_export_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function template($categoryId): StreamedResponse
    {
        $category = Category::with('attributes')->find($categoryId);
        $attributes = $this->getCategoryAttributes($category);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $dataSheet = $this->createDataSheet($spreadsheet);

        $headers = $this->buildTemplateHeaders($category, $attributes);
        $this->setHeaders($sheet, $headers);
        $this->styleHeaders($sheet, self::HEADER_FILL_COLOR_TEMPLATE);
        $this->setColumnWidths($sheet, $headers);

        $this->applyDataValidations($sheet, $headers, $attributes, $dataSheet);

        $filename = $category ? 'template_' . $category->slug . '_' . date('Y-m-d_H-i-s') . '.xlsx' : 'template_' . date('Y-m-d_H-i-s') . '.xlsx';

        return $this->createResponse($spreadsheet, $filename);
    }

    public function editTemplate($type): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        switch ($type) {
            case 'prices':
                $headers = ['Артикул', 'Цена'];
                break;
            case 'statuses':
                $headers = ['Артикул', 'Опубликовано'];
                break;
            case 'sales':
                $headers = ['Артикул', 'Распродажа'];
                break;
            default:
                $headers = ['Артикул'];
        }

        $dataSheet = $this->createDataSheet($spreadsheet);

        $this->setHeaders($sheet, $headers);
        $this->styleHeaders($sheet, self::HEADER_FILL_COLOR_EDIT_TEMPLATE);
        $this->setColumnWidths($sheet, $headers);

        $this->applyEditDataValidations($sheet, $headers, $type, $dataSheet);

        $filename = 'edit_template_' . $type . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return $this->createResponse($spreadsheet, $filename);
    }

    public function editTemplateCategory($categoryId): StreamedResponse
    {
        $category = Category::with('attributes')->find($categoryId);
        $attributes = $this->getCategoryAttributes($category);
        $products = $this->productService->getProductsByCategory($categoryId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $dataSheet = $this->createDataSheet($spreadsheet);

        $headers = $this->buildTemplateHeaders($category, $attributes);
        $this->setHeaders($sheet, $headers);
        $this->styleHeaders($sheet, self::HEADER_FILL_COLOR_EDIT_TEMPLATE);
        $this->setColumnWidths($sheet, $headers);

        $this->populateCategoryEditData($sheet, $products, $attributes, $headers);

        $this->applyDataValidations($sheet, $headers, $attributes, $dataSheet);

        $filename = $category ? 'edit_template_' . $category->slug . '_' . date('Y-m-d_H-i-s') . '.xlsx' : 'edit_template_' . date('Y-m-d_H-i-s') . '.xlsx';

        return $this->createResponse($spreadsheet, $filename);
    }

    protected function getUniqueAttributes(array $products): array
    {
        $uniqueAttributes = [];
        foreach ($products as $product) {
            if (!empty($product['attribute_values'])) {
                foreach ($product['attribute_values'] as $attr) {
                    $name = $attr['attribute']['name'] ?? '';
                    if ($name && !in_array($name, $uniqueAttributes)) {
                        $uniqueAttributes[] = $name;
                    }
                }
            }
        }
        return $uniqueAttributes;
    }

    protected function getCategoryAttributes(?Category $category): array
    {
        $attributes = [];
        if ($category) {
            $attributes = $category->attributes->map(function($attr) {
                return ['name' => $attr->name, 'type' => $attr->type];
            })->toArray();
        }
        return $attributes;
    }

    protected function buildTemplateHeaders(?Category $category, array $attributes): array
    {
        $headers = self::TEMPLATE_BASE_HEADERS;
        if ($category && in_array($category->name, self::SPECIFIC_CATEGORIES)) {
            $headers = array_merge($headers, self::SPECIFIC_HEADERS);
        }
        $attributeNames = array_column($attributes, 'name');
        $headers = array_merge($headers, $attributeNames, $this->getImageHeaders());
        return $headers;
    }

    protected function getImageHeaders(): array
    {
        return ['Изображение 1', 'Изображение 2', 'Изображение 3', 'Изображение 4', 'Изображение 5'];
    }

    protected function createDataSheet(Spreadsheet $spreadsheet): Worksheet
    {
        $dataSheet = $spreadsheet->createSheet();
        $dataSheet->setTitle('Data');
        $allCategories = Category::orderBy('name')->pluck('name')->toArray();
        $allBrands = Brand::orderBy('name')->pluck('name')->toArray();
        $allColors = Color::orderBy('name')->pluck('name')->toArray();
        $dataSheet->fromArray(array_map(function($item) { return [$item]; }, $allCategories), null, 'A1');
        $dataSheet->fromArray(array_map(function($item) { return [$item]; }, $allBrands), null, 'B1');
        $dataSheet->fromArray(array_map(function($item) { return [$item]; }, $allColors), null, 'C1');
        $dataSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        return $dataSheet;
    }

    protected function setHeaders(Worksheet $sheet, array $headers): void
    {
        $sheet->fromArray($headers, null, 'A1');
    }

    protected function styleHeaders(Worksheet $sheet, string $fillColor): void
    {
        $highestColumn = $sheet->getHighestColumn();
        $headerRange = 'A1:' . $highestColumn . '1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($fillColor);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    protected function setColumnWidths(Worksheet $sheet, array $headers): void
    {
        $col = 0;
        foreach ($headers as $header) {
            $columnLetter = Coordinate::stringFromColumnIndex($col + 1);
            $width = strlen($header) * self::COLUMN_WIDTH_FACTOR;
            $sheet->getColumnDimension($columnLetter)->setWidth($width);
            $col++;
        }
    }

    protected function populateExportData(Worksheet $sheet, array $products, array $uniqueAttributes): void
    {
        $row = 2;
        foreach ($products as $product) {
            $attributeMap = $this->buildAttributeMap($product);
            $imageUrls = $this->getImageUrls($product);

            $data = [
                $product['article'],
                $product['name'],
                $product['price'],
                $product['unit'],
                $product['product_code'],
                $product['description'],
                $product['category']['name'] ?? '',
                $product['brand']['name'] ?? '',
                $product['color']['name'] ?? '',
                $product['is_published'] ? 'Да' : 'Нет',
                $product['is_sale'] ? 'Да' : 'Нет',
                $product['texture'],
                $product['pattern'],
                $product['country'],
                $product['collection']
            ];

            foreach ($uniqueAttributes as $attrName) {
                $data[] = $attributeMap[$attrName] ?? '';
            }

            for ($i = 0; $i < 5; $i++) {
                $data[] = $imageUrls[$i] ?? '';
            }

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }
    }

    protected function populateCategoryEditData(Worksheet $sheet, array $products, array $attributes, array $headers): void
    {
        $row = 2;
        $attributeNames = array_column($attributes, 'name');
        foreach ($products as $product) {
            $attributeMap = $this->buildAttributeMap($product);
            $imageUrls = $this->getImageUrls($product);

            $data = [
                $product['article'],
                $product['name'],
                $product['price'],
                $product['unit'],
                $product['product_code'],
                $product['description'],
                $product['category']['name'] ?? '',
                $product['brand']['name'] ?? '',
                $product['color']['name'] ?? '',
                $product['is_published'] ? 'Да' : 'Нет',
                $product['is_sale'] ? 'Да' : 'Нет',
                $product['country']
            ];

            // Add specific headers if category is specific
            if (in_array($product['category']['name'] ?? '', self::SPECIFIC_CATEGORIES)) {
                $data[] = $product['texture'];
                $data[] = $product['pattern'];
                $data[] = $product['collection'];
            }

            // Add attribute values in order of attributeNames
            foreach ($attributeNames as $attrName) {
                $data[] = $attributeMap[$attrName] ?? '';
            }

            // Add image URLs
            for ($i = 0; $i < 5; $i++) {
                $data[] = $imageUrls[$i] ?? '';
            }

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }
    }


    protected function buildAttributeMap(array $product): array
    {
        $attributeMap = [];
        if (!empty($product['attribute_values'])) {
            foreach ($product['attribute_values'] as $attr) {
                $name = $attr['attribute']['name'] ?? '';
                if (isset($attr['string_value'])) {
                    $value = $attr['string_value'];
                } elseif (isset($attr['number_value'])) {
                    $value = $attr['number_value'];
                } elseif (isset($attr['boolean_value'])) {
                    $value = $attr['boolean_value'] ? 'Да' : 'Нет';
                } else {
                    $value = '';
                }
                $attributeMap[$name] = $value;
            }
        }
        return $attributeMap;
    }

    protected function getImageUrls(array $product): array
    {
        $imageUrls = [];
        if (!empty($product['images'])) {
            foreach ($product['images'] as $image) {
                $imageUrls[] = asset('storage/' . $image['image_path']);
            }
        }
        return $imageUrls;
    }

    protected function applyDataValidations(Worksheet $sheet, array $headers, array $attributes, Worksheet $dataSheet): void
    {
        $categoryIndex = array_search('Категория', $headers);
        $brandIndex = array_search('Бренд', $headers);
        $colorIndex = array_search('Цвет', $headers);
        $publishedIndex = array_search('Опубликовано', $headers);
        $saleIndex = array_search('Распродажа', $headers);
        $booleanColumns = [];
        foreach ($attributes as $attr) {
            if ($attr['type'] === 'boolean') {
                $index = array_search($attr['name'], $headers);
                if ($index !== false) {
                    $booleanColumns[] = $index;
                }
            }
        }
        $allBooleanColumns = array_merge($booleanColumns, array_filter([$publishedIndex, $saleIndex]));

        $this->applyListValidation($sheet, $categoryIndex, 'Data!$A:$A');
        $this->applyListValidation($sheet, $brandIndex, 'Data!$B:$B');
        $this->applyListValidation($sheet, $colorIndex, 'Data!$C:$C');

        foreach ($allBooleanColumns as $colIndex) {
            $this->applyListValidation($sheet, $colIndex, '"Да,Нет"');
        }
    }

    protected function applyEditDataValidations(Worksheet $sheet, array $headers, string $type, Worksheet $dataSheet): void
    {
        if (in_array($type, ['statuses', 'sales'])) {
            // For statuses and sales, apply validation to the second column (index 1)
            $this->applyListValidation($sheet, 1, '"Да,Нет"');
        }
    }

    protected function applyListValidation(Worksheet $sheet, ?int $colIndex, string $formula): void
    {
        if ($colIndex !== false) {
            $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
            $validation = $sheet->getDataValidation($columnLetter . '2');
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Ошибка ввода');
            $validation->setError('Значение отсутствует в списке.');
            $validation->setPromptTitle('Выберите из списка');
            $validation->setPrompt('Пожалуйста, выберите значение из выпадающего списка.');
            $validation->setFormula1($formula);
            $validation->setSqref($columnLetter . '2:' . $columnLetter . '1000');
        }
    }

    protected function createResponse(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        $writer = new Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
