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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Заголовки
        $headers = [
            'ID', 'Артикул', 'Название', 'Цена', 'Единица', 'Код товара', 'Описание',
            'Категория', 'Бренд', 'Цвет', 'Опубликовано', 'Распродажа', 'Текстура',
            'Узор', 'Страна', 'Коллекция', 'Характеристики', 'Изображения'
        ];
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        foreach ($products as $product) {
            // Собираем характеристики
            $attributes = '';
            if (!empty($product['attribute_values'])) {
                $attrList = [];
                foreach ($product['attribute_values'] as $attr) {
                    $name = $attr['attribute']['name'] ?? '';
                    $value = $attr['string_value'] ?? $attr['number_value'] ?? $attr['boolean_value'] ?? '';
                    $attrList[] = $name . ': ' . $value;
                }
                $attributes = implode('; ', $attrList);
            }

            // Собираем ссылки на изображения
            $images = '';
            if (!empty($product['images'])) {
                $imageUrls = [];
                foreach ($product['images'] as $image) {
                    $imageUrls[] = asset('storage/' . $image['image_path']);
                }
                $images = implode('; ', $imageUrls);
            }

            $data = [
                $product['id'],
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
                $product['collection'],
                $attributes,
                $images
            ];

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
