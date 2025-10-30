<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RawMaterialSample;
use Illuminate\Http\Request;
use App\Models\TestResult;
use Illuminate\Http\JsonResponse;

class TestResultController extends Controller
{

    // Get all test_results with filters
    public function index(Request $request): JsonResponse
    {
        $query = TestResult::with(['sample', 'specification', 'testedBy']);

        if ($request->has('sample_id')) {
            $query->where('sample_id', $request->sample_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('from_date')) {
            $query->whereDate('tested_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('tested_at', '<=', $request->to_date);
        }

        if ($request->has('parameter')) {
            $query->where('parameter_name', $request->parameter);
        }

        $sortBy = $request->get('sort_by', 'tested_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Test results retrieved successfully',
            'data' => [
                'test_results' => $results->items(),
            ]
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = TestResult::with(['sample', 'specification', 'testedBy'])->find($id);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Test result not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Test result retrieved successfully',
            'data' => $result
        ]);
    }

    public function bySample(int $sampleId): JsonResponse
    {
        $sample = RawMaterialSample::with(['testResults.specification', 'testResults.testedBy'])->find($sampleId);

        if (!$sample) {
            return response()->json([
                'success' => false,
                'message' => 'Sample not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Test results retrieved successfully',
            'data' => [
                'sample' => [
                    'id' => $sample->id,
                    'batch_lot' => $sample->batch_lot,
                    'supplier' => $sample->supplier,
                    'status' => $sample->status,
                    'submission_time' => $sample->submission_time,
                ],
                'test_results' => $sample->testResults,
            ],
        ]);
    }
}
