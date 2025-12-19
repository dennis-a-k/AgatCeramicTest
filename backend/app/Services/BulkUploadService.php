<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BulkUploadService
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function processBulkUpload(Request $request): array
    {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove header row
        array_shift($rows);

        $successCount = 0;
        $errorCount = 0;
        $warningCount = 0;

        foreach ($rows as $index => $row) {
            try {
                $productData = $this->parseProductRow($row);
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

    private function parseProductRow(array $row): ?array
    {
        // Skip completely empty rows
        if (empty(array_filter($row))) {
            return null;
        }

        // Required fields validation
        if (empty(trim($row[0] ?? '')) || empty(trim($row[1] ?? ''))) {
            throw new \Exception('Артикул и название товара обязательны');
        }

        $productData = [
            'article' => trim($row[0]),
            'name' => trim($row[1]),
            'price' => is_numeric($row[2] ?? 0) ? (float)$row[2] : 0,
            'unit' => trim($row[3] ?? 'шт'),
            'product_code' => trim($row[4] ?? ''),
            'description' => trim($row[5] ?? ''),
            'category_id' => $this->getCategoryIdByName(trim($row[6] ?? '')),
            'brand_id' => $this->getBrandIdByName(trim($row[7] ?? '')),
            'color_id' => $this->getColorIdByName(trim($row[8] ?? '')),
            'is_published' => in_array(strtolower(trim($row[9] ?? '1')), ['1', 'да', 'Да', 'yes', 'true']),
            'is_sale' => in_array(strtolower(trim($row[10] ?? '0')), ['1', 'да', 'Да', 'yes', 'true']),
            'texture' => trim($row[11] ?? ''),
            'pattern' => trim($row[12] ?? ''),
            'country' => trim($row[13] ?? ''),
            'collection' => trim($row[14] ?? ''),
            'attribute_values' => []
        ];

        // Validate required relationships
        if (!$productData['category_id']) {
            throw new \Exception('Категория "' . trim($row[6] ?? '') . '" не найдена');
        }

        // Parse dynamic attributes starting from column 15
        // Assuming columns 15+ are attribute values, and we need to map them to existing attributes
        $allAttributes = Attribute::orderBy('id')->get();
        $attributeIndex = 0;

        for ($i = 15; $i < count($row); $i++) {
            $value = trim($row[$i] ?? '');
            if (!empty($value) && isset($allAttributes[$attributeIndex])) {
                $attribute = $allAttributes[$attributeIndex];
                $productData['attribute_values'][] = [
                    'attribute_id' => $attribute->id,
                    'string_value' => $attribute->type === 'string' ? $value : null,
                    'number_value' => $attribute->type === 'number' && is_numeric($value) ? (float)$value : null,
                    'boolean_value' => $attribute->type === 'boolean' ? in_array(strtolower($value), ['1', 'да', 'Да', 'yes', 'true']) : null,
                    'text_value' => $attribute->type === 'text' ? $value : null,
                ];
            }
            $attributeIndex++;
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
}
