<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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
            'Артикул', 'Название', 'Цена', 'Единица', 'Код товара', 'Описание',
            'Категория', 'Бренд', 'Цвет', 'Опубликовано', 'Распродажа', 'Текстура',
            'Узор', 'Страна', 'Коллекция'
        ];
        $headers = array_merge($headers, $uniqueAttributes, ['Изображение 1', 'Изображение 2', 'Изображение 3', 'Изображение 4', 'Изображение 5']);
        $sheet->fromArray($headers, null, 'A1');

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
}
