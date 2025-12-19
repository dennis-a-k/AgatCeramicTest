<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ProductService;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
            $attributes = $category->attributes->pluck('name')->toArray();
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
            'Страна'
        ];

        // Добавить специфические поля для определенных категорий
        $specificCategories = ['Керамогранит', 'Плитка', 'Мозаика', 'Клинкер', 'Ступени'];
        if ($category && in_array($category->name, $specificCategories)) {
            $headers = array_merge($headers, ['Поверхность', 'Рисунок', 'Коллекция']);
        }
        $headers = array_merge($headers, $attributes, ['Изображение 1', 'Изображение 2', 'Изображение 3', 'Изображение 4', 'Изображение 5']);
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
