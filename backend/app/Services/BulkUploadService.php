<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ZipArchive;

class BulkUploadService
{
    protected $productService;
    protected $imageService;

    public function __construct(ProductService $productService, ImageService $imageService)
    {
        $this->productService = $productService;
        $this->imageService = $imageService;
    }

    public function processBulkUpload(Request $request): array
    {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Get header row
        $headers = array_shift($rows);

        $successCount = 0;
        $errorCount = 0;
        $warningCount = 0;

        foreach ($rows as $index => $row) {
            try {
                $productData = $this->parseProductRow($row, $headers);
                if ($productData) {
                    $this->productService->createProductWithAttributesAndImages($productData, $request);
                    $successCount++;
                }
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000) { // Integrity constraint violation (duplicate entry)
                    $warningCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        $totalFailed = $errorCount + $warningCount;
        $message = $totalFailed === 0 ? "Успешно загруженных товаров {$successCount} шт." : "Успешно загруженных товаров {$successCount} шт.";
        $statusCode = $totalFailed === 0 ? 200 : 422;

        $errors = $totalFailed > 0 ? ["Не удалось загрузить {$totalFailed} товаров"] : [];
        $warnings = [];

        return [
            'message' => $message,
            'success_count' => $successCount,
            'warnings' => $warnings,
            'errors' => $errors,
            'status_code' => $statusCode
        ];
    }

    private function parseProductRow(array $row, array $headers): ?array
    {
        // Skip completely empty rows
        if (empty(array_filter($row))) {
            return null;
        }

        // Required fields validation
        if (empty(trim($row[0] ?? ''))) {
            throw new \Exception('Название товара обязательно');
        }

        $productData = [
            'name' => trim($row[0]),
            'price' => is_numeric($row[1] ?? 0) ? (float)$row[1] : 0,
            'unit' => trim($row[2] ?? 'шт'),
            'product_code' => trim($row[3] ?? ''),
            'description' => trim($row[4] ?? ''),
            'category_id' => $this->getCategoryIdByName(trim($row[5] ?? '')),
            'brand_id' => $this->getBrandIdByName(trim($row[6] ?? '')),
            'color_id' => $this->getColorIdByName(trim($row[7] ?? '')),
            'is_published' => in_array(strtolower(trim($row[8] ?? '1')), ['1', 'да', 'Да', 'yes', 'true']),
            'is_sale' => in_array(strtolower(trim($row[9] ?? '0')), ['1', 'да', 'Да', 'yes', 'true']),
            'country' => trim($row[10] ?? ''),
            'attribute_values' => []
        ];

        // Validate required relationships
        if (!$productData['category_id']) {
            throw new \Exception('Категория "' . trim($row[6] ?? '') . '" не найдена');
        }

        // Determine if category is specific
        $categoryName = trim($row[6] ?? '');
        $isSpecific = in_array($categoryName, ['Керамогранит', 'Плитка', 'Мозаика', 'Клинкер', 'Ступени']);

        $baseHeadersCount = 11; // Up to 'country'
        if ($isSpecific) {
            $productData['texture'] = trim($row[11] ?? '');
            $productData['pattern'] = trim($row[12] ?? '');
            $productData['collection'] = trim($row[13] ?? '');
            $baseHeadersCount = 14;
        }

        // Parse attributes starting from baseHeadersCount
        for ($i = $baseHeadersCount; $i < count($headers); $i++) {
            $header = trim($headers[$i] ?? '');
            if (in_array($header, ['Изображение 1', 'Изображение 2', 'Изображение 3', 'Изображение 4', 'Изображение 5'])) {
                // Skip image columns
                continue;
            }
            $value = trim($row[$i] ?? '');
            if (!empty($value) || $value === '0' || $value === 0) {
                $attribute = Attribute::where('name', $header)->first();
                if ($attribute) {
                    $productData['attribute_values'][] = [
                        'attribute_id' => $attribute->id,
                        'string_value' => $attribute->type === 'string' ? $value : null,
                        'number_value' => $attribute->type === 'number' && is_numeric($value) ? (float)$value : null,
                        'boolean_value' => $attribute->type === 'boolean' ? in_array(strtolower($value), ['1', 'да', 'Да', 'yes', 'true']) : null,
                        'text_value' => $attribute->type === 'text' ? $value : null,
                    ];
                }
            }
        }

        return $productData;
    }

    private function getCategoryIdByName(string $name): ?int
    {
        if (empty($name)) return null;
        return Category::where('name', $name)->value('id');
    }

    private function getBrandIdByName(string $name): ?int
    {
        if (empty($name)) return null;
        return Brand::where('name', $name)->value('id');
    }

    private function getColorIdByName(string $name): ?int
    {
        if (empty($name)) return null;
        return Color::where('name', $name)->value('id');
    }

    public function processBulkEdit(Request $request): array
    {
        $file = $request->file('file');
        $type = $request->input('type');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Get header row
        $headers = array_shift($rows);

        $successCount = 0;
        $errorCount = 0;
        $warningCount = 0;

        foreach ($rows as $index => $row) {
            try {
                $updateData = $this->parseEditRow($row, $type, $headers);
                if ($updateData) {
                    $this->productService->updateProductBulk($updateData);
                    $successCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        $totalFailed = $errorCount + $warningCount;
        $message = $totalFailed === 0 ? "Успешно обновленных товаров {$successCount} шт." : "Успешно обновленных товаров {$successCount} шт.";
        $statusCode = $totalFailed === 0 ? 200 : 422;

        $errors = $totalFailed > 0 ? ["Не удалось обновить {$totalFailed} товаров"] : [];
        $warnings = [];

        return [
            'message' => $message,
            'success_count' => $successCount,
            'warnings' => $warnings,
            'errors' => $errors,
            'status_code' => $statusCode
        ];
    }

    private function parseEditRow(array $row, string $type, array $headers): ?array
    {
        // Skip completely empty rows
        if (empty(array_filter($row))) {
            return null;
        }

        // Required fields validation
        if (empty(trim($row[0] ?? ''))) {
            throw new \Exception('Артикул обязателен');
        }

        $article = trim($row[0]);
        $updateData = ['article' => $article];

        switch ($type) {
            case 'products':
                // Parse based on headers
                for ($i = 1; $i < count($headers); $i++) {
                    $header = trim($headers[$i] ?? '');
                    $value = trim($row[$i] ?? '');
                    switch ($header) {
                        case 'Название':
                            $updateData['name'] = $value;
                            break;
                        case 'Цена':
                            $updateData['price'] = is_numeric($value) ? (float)$value : null;
                            break;
                        case 'Описание':
                            $updateData['description'] = $value;
                            break;
                        case 'Категория':
                            $updateData['category_id'] = $this->getCategoryIdByName($value);
                            break;
                        case 'Бренд':
                            $updateData['brand_id'] = $this->getBrandIdByName($value);
                            break;
                        case 'Цвет':
                            $updateData['color_id'] = $this->getColorIdByName($value);
                            break;
                        case 'Опубликовано':
                            $updateData['is_published'] = in_array(strtolower($value), ['1', 'да', 'Да', 'yes', 'true']);
                            break;
                        case 'Распродажа':
                            $updateData['is_sale'] = in_array(strtolower($value), ['1', 'да', 'Да', 'yes', 'true']);
                            break;
                        case 'Страна':
                            $updateData['country'] = $value;
                            break;
                        case 'Поверхность':
                            $updateData['texture'] = $value;
                            break;
                        case 'Рисунок':
                            $updateData['pattern'] = $value;
                            break;
                        case 'Коллекция':
                            $updateData['collection'] = $value;
                            break;
                        case 'Единица':
                            $updateData['unit'] = $value;
                            break;
                        case 'Код товара':
                            $updateData['product_code'] = $value;
                            break;
                        default:
                            // Check if it's an attribute
                            $attribute = Attribute::where('name', $header)->first();
                            if ($attribute) {
                                $updateData['attribute_values'][] = [
                                    'attribute_id' => $attribute->id,
                                    'string_value' => $attribute->type === 'string' ? $value : null,
                                    'number_value' => $attribute->type === 'number' && is_numeric($value) ? (float)$value : null,
                                    'boolean_value' => $attribute->type === 'boolean' ? in_array(strtolower($value), ['1', 'да', 'Да', 'yes', 'true']) : null,
                                    'text_value' => $attribute->type === 'text' ? $value : null,
                                ];
                            }
                            break;
                    }
                }
                return $updateData;
            case 'prices':
                return [
                    'article' => $article,
                    'price' => is_numeric($row[1] ?? 0) ? (float)$row[1] : null,
                ];
            case 'statuses':
                return [
                    'article' => $article,
                    'is_published' => in_array(strtolower(trim($row[1] ?? '')), ['1', 'да', 'Да', 'yes', 'true']),
                ];
            case 'sales':
                return [
                    'article' => $article,
                    'is_sale' => in_array(strtolower(trim($row[1] ?? '')), ['1', 'да', 'Да', 'yes', 'true']),
                ];
            default:
                return null;
        }
    }

    public function processBulkPhotoUpload(Request $request): array
    {
        $file = $request->file('file');
        $zipPath = $file->getPathname();

        // Создаем временную директорию для распаковки
        $tempDir = sys_get_temp_dir() . '/bulk_photos_' . uniqid();
        mkdir($tempDir, 0755, true);

        try {
            // Распаковываем ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== true) {
                throw new \Exception('Не удалось открыть ZIP файл');
            }

            $zip->extractTo($tempDir);
            $zip->close();

            // Группируем файлы по артикулам
            $filesByArticle = $this->groupFilesByArticle($tempDir);

            $successCount = 0;
            $errorCount = 0;
            $warningCount = 0;
            $errors = [];
            $warnings = [];

            foreach ($filesByArticle as $article => $filePaths) {
                try {
                    $product = Product::where('article', $article)->first();
                    if (!$product) {
                        $warnings[] = "Товар с артикулом {$article} не найден";
                        $warningCount++;
                        continue;
                    }

                    // Удаляем существующие изображения
                    $existingImages = ProductImage::where('product_id', $product->id)->pluck('id')->toArray();
                    if (!empty($existingImages)) {
                        $this->imageService->deleteImages($existingImages);
                    }

                    // Сохраняем новые изображения
                    $this->imageService->saveProductImagesFromPaths($product->id, $article, $filePaths);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Ошибка при обработке артикула {$article}: " . $e->getMessage();
                    $errorCount++;
                }
            }

            // Очищаем временную директорию
            $this->deleteDirectory($tempDir);

            $totalFailed = $errorCount + $warningCount;
            $message = $totalFailed === 0 ? "Успешно загружено фото для {$successCount} товаров" : "Загружено фото для {$successCount} товаров";
            $statusCode = $totalFailed === 0 ? 200 : 422;

            return [
                'message' => $message,
                'success_count' => $successCount,
                'warnings' => $warnings,
                'errors' => $errors,
                'status_code' => $statusCode
            ];
        } catch (\Exception $e) {
            // Очищаем временную директорию в случае ошибки
            if (is_dir($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            throw $e;
        }
    }

    private function groupFilesByArticle(string $dir): array
    {
        $filesByArticle = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filename = $file->getFilename();
                // Проверяем формат: article_index.ext
                if (preg_match('/^(\d+)_(\d+)\.(jpg|jpeg|png|webp)$/i', $filename, $matches)) {
                    $article = $matches[1];
                    $filesByArticle[$article][] = $file->getPathname();
                }
            }
        }

        return $filesByArticle;
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }

        rmdir($dir);
    }
}
