<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Services\ProductService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $productService;
    protected $searchService;

    public function __construct(ProductService $productService, SearchService $searchService)
    {
        $this->productService = $productService;
        $this->searchService = $searchService;
    }

    public function index(Request $request): JsonResponse
    {
        $request->merge(['admin' => true]);
        $result = $this->productService->getProductsWithFilters($request);
        return response()->json($result);
    }

    public function show($id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }

    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();

        // Debug: log the request data
        Log::info('Update Product Request Data:', $request->all());
        Log::info('Validated Data:', $validated);

        $updated = $this->productService->updateProductWithAttributesAndImages($id, $validated, $request);

        if (!$updated) {
            return response()->json(['message' => 'Product not found or could not be updated'], 404);
        }

        return response()->json(['message' => 'Product updated successfully']);
    }


    public function store(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Debug: log the request data
        Log::info('Store Product Request Data:', $request->all());
        Log::info('Validated Data:', $validated);

        $product = $this->productService->createProductWithAttributesAndImages($validated, $request);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->productService->deleteProduct($id);

        if (!$deleted) {
            return response()->json(['message' => 'Product not found or could not be deleted'], 404);
        }

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function bulkUpload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
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

            return response()->json([
                'message' => $message,
                'success_count' => $successCount,
                'warnings' => $warnings,
                'errors' => $errors
            ], $statusCode);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process file: ' . $e->getMessage()], 500);
        }
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
            'is_published' => in_array(strtolower(trim($row[9] ?? '1')), ['1', 'да', 'yes', 'true']),
            'is_sale' => in_array(strtolower(trim($row[10] ?? '0')), ['1', 'да', 'yes', 'true']),
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
        $allAttributes = \App\Models\Attribute::orderBy('id')->get();
        $attributeIndex = 0;

        for ($i = 15; $i < count($row); $i++) {
            $value = trim($row[$i] ?? '');
            if (!empty($value) && isset($allAttributes[$attributeIndex])) {
                $attribute = $allAttributes[$attributeIndex];
                $productData['attribute_values'][] = [
                    'attribute_id' => $attribute->id,
                    'string_value' => $attribute->type === 'string' ? $value : null,
                    'number_value' => $attribute->type === 'number' && is_numeric($value) ? (float)$value : null,
                    'boolean_value' => $attribute->type === 'boolean' ? in_array(strtolower($value), ['1', 'да', 'yes', 'true']) : null,
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
        return \App\Models\Category::where('name', $name)->value('id');
    }

    private function getBrandIdByName(string $name): ?int
    {
        if (empty($name)) return null;
        return \App\Models\Brand::where('name', $name)->value('id');
    }

    private function getColorIdByName(string $name): ?int
    {
        if (empty($name)) return null;
        return \App\Models\Color::where('name', $name)->value('id');
    }
}
