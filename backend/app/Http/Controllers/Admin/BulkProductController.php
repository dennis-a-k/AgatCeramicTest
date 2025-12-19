<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BulkUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BulkProductController extends Controller
{
    protected $bulkUploadService;

    public function __construct(BulkUploadService $bulkUploadService)
    {
        $this->bulkUploadService = $bulkUploadService;
    }

    public function bulkUpload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            $result = $this->bulkUploadService->processBulkUpload($request);

            return response()->json([
                'message' => $result['message'],
                'success_count' => $result['success_count'],
                'warnings' => $result['warnings'],
                'errors' => $result['errors']
            ], $result['status_code']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process file: ' . $e->getMessage()], 500);
        }
    }

    public function bulkEdit(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
            'type' => 'required|string|in:products,prices,statuses,sales'
        ]);

        try {
            $result = $this->bulkUploadService->processBulkEdit($request);

            return response()->json([
                'message' => $result['message'],
                'success_count' => $result['success_count'],
                'warnings' => $result['warnings'],
                'errors' => $result['errors']
            ], $result['status_code']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process file: ' . $e->getMessage()], 500);
        }
    }
}
