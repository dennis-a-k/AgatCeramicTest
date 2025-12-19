<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Services\ProductService;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductExportController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function export(): StreamedResponse
    {
        $products = $this->productService->getAllProductsForExport();

        // Собираем уникальные характеристики
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Заголовки
        $headers = [
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
        $headers = array_merge($headers, $uniqueAttributes, ['Изображение 1', 'Изображение 2', 'Изображение 3', 'Изображение 4', 'Изображение 5']);
        $sheet->fromArray($headers, null, 'A1');

        // Стилизация заголовков
        $highestColumn = $sheet->getHighestColumn();
        $headerRange = 'A1:' . $highestColumn . '1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('95B3D7');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Установка ширины колонок по ширине заголовков
        $col = 0;
        foreach ($headers as $header) {
            $columnLetter = Coordinate::stringFromColumnIndex($col + 1);
            $width = strlen($header) * 1.2; // Коэффициент для отступа
            $sheet->getColumnDimension($columnLetter)->setWidth($width);
            $col++;
        }

        $row = 2;
        foreach ($products as $product) {
            // Собираем карту характеристик для продукта
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

            // Собираем ссылки на изображения
            $imageUrls = [];
            if (!empty($product['images'])) {
                foreach ($product['images'] as $image) {
                    $imageUrls[] = asset('storage/' . $image['image_path']);
                }
            }

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

            // Добавляем значения характеристик
            foreach ($uniqueAttributes as $attrName) {
                $data[] = $attributeMap[$attrName] ?? '';
            }

            // Добавляем изображения
            for ($i = 0; $i < 5; $i++) {
                $data[] = $imageUrls[$i] ?? '';
            }

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="products_export_' . date('Y-m-d_H-i-s') . '.xlsx"',
        ]);
    }

    public function template($categoryId): StreamedResponse
    {
        $category = Category::with('attributes')->find($categoryId);

        $attributes = [];
        if ($category) {
            $attributes = $category->attributes->map(function($attr) {
                return ['name' => $attr->name, 'type' => $attr->type];
            })->toArray();
        }

        // Получить списки для выпадающих меню
        $allCategories = Category::orderBy('name')->pluck('name')->toArray();
        $allBrands = Brand::orderBy('name')->pluck('name')->toArray();
        $allColors = Color::orderBy('name')->pluck('name')->toArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Создать скрытый лист с данными для выпадающих списков
        $dataSheet = $spreadsheet->createSheet();
        $dataSheet->setTitle('Data');
        $dataSheet->fromArray(array_map(function($item) { return [$item]; }, $allCategories), null, 'A1');
        $dataSheet->fromArray(array_map(function($item) { return [$item]; }, $allBrands), null, 'B1');
        $dataSheet->fromArray(array_map(function($item) { return [$item]; }, $allColors), null, 'C1');
        $dataSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

        // Заголовки
        $headers = [
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

        // Добавить специфические поля для определенных категорий
        $specificCategories = ['Керамогранит', 'Плитка', 'Мозаика', 'Клинкер', 'Ступени'];
        if ($category && in_array($category->name, $specificCategories)) {
            $headers = array_merge($headers, ['Поверхность', 'Рисунок', 'Коллекция']);
        }
        $attributeNames = array_column($attributes, 'name');
        $headers = array_merge($headers, $attributeNames, ['Изображение 1', 'Изображение 2', 'Изображение 3', 'Изображение 4', 'Изображение 5']);
        $sheet->fromArray($headers, null, 'A1');

        // Стилизация заголовков
        $highestColumn = $sheet->getHighestColumn();
        $headerRange = 'A1:' . $highestColumn . '1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('C4D79B');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Установка ширины колонок по ширине заголовков
        $col = 0;
        foreach ($headers as $header) {
            $columnLetter = Coordinate::stringFromColumnIndex($col + 1);
            $width = strlen($header) * 1.2; // Коэффициент для отступа
            $sheet->getColumnDimension($columnLetter)->setWidth($width);
            $col++;
        }

        // Добавление выпадающих списков
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

        // Для Категория
        if ($categoryIndex !== false) {
            $columnLetter = Coordinate::stringFromColumnIndex($categoryIndex + 1);
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
            $validation->setFormula1('Data!$A:$A');
            $validation->setSqref($columnLetter . '2:' . $columnLetter . '1000');
        }

        // Для Бренд
        if ($brandIndex !== false) {
            $columnLetter = Coordinate::stringFromColumnIndex($brandIndex + 1);
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
            $validation->setFormula1('Data!$B:$B');
            $validation->setSqref($columnLetter . '2:' . $columnLetter . '1000');
        }

        // Для Цвет
        if ($colorIndex !== false) {
            $columnLetter = Coordinate::stringFromColumnIndex($colorIndex + 1);
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
            $validation->setFormula1('Data!$C:$C');
            $validation->setSqref($columnLetter . '2:' . $columnLetter . '1000');
        }

        foreach ($allBooleanColumns as $colIndex) {
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
                $validation->setFormula1('"Да,Нет"');
                $validation->setSqref($columnLetter . '2:' . $columnLetter . '1000');
            }
        }

        $writer = new Xlsx($spreadsheet);

        $filename = $category ? 'template_' . $category->slug . '_' . date('Y-m-d_H-i-s') . '.xlsx' : 'template_' . date('Y-m-d_H-i-s') . '.xlsx';

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
