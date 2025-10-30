<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\TestResultController;

// Public endpoint - test API (no authentication)
Route::get('/ping', function () {
    return response()->json([
        'success' => true,
        'message' => 'LIMS API is running',
        'timestamp' => now(),
        'version' => '1.0.0',
    ]);
});

// Helper function untuk validasi API key
function validateApiKey(Request $request)
{
    $apiKey = $request->header('X-API-Key');
    $validKey = config('app.api_key');

    if (!$apiKey || $apiKey !== $validKey) {
        abort(response()->json([
            'success' => false,
            'message' => 'Unauthorized - Invalid or missing API Key',
            'hint' => 'Include X-API-Key header with valid API key',
        ], 401));
    }
}

// Protected API endpoints - v1
Route::prefix('v1')->group(function () {

    // Test Results endpoints
    Route::get('/test-results', function (Request $request) {
        validateApiKey($request);
        return app(TestResultController::class)->index($request);
    });

    Route::get('/test-results/{id}', function (Request $request, $id) {
        validateApiKey($request);
        return app(TestResultController::class)->show($id);
    });

    Route::get('/samples/{id}/test-results', function (Request $request, $id) {
        validateApiKey($request);
        return app(TestResultController::class)->bySample($id);
    });
});
