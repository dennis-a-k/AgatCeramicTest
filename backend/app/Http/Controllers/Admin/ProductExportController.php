<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductExportService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductExportController extends Controller
{
    protected $productExportService;

    public function __construct(ProductExportService $productExportService)
    {
        $this->productExportService = $productExportService;
    }

    public function export(): StreamedResponse
    {
        return $this->productExportService->export();
    }

    public function template($categoryId): StreamedResponse
    {
        return $this->productExportService->template($categoryId);
    }
}
