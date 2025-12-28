<?php

namespace App\Repositories;

use App\Models\Sample;
use Illuminate\Database\Eloquent\Collection;

class SampleRepository
{
    /**
     * Find sample with complete analysis data (all eager loaded relations)
     *
     * @param int $sampleId
     * @return Sample|null
     */
    public function findWithCompleteAnalysisData(int $sampleId): ?Sample
    {
        return Sample::with([
            'category',
            'material',
            'reference.specificationsManytoMany',
            'submittedBy',
            'primaryAnalyst',
            'secondaryAnalyst',
            'testResults'
        ])->find($sampleId);
    }

    /**
     * Update sample with analysis completion status
     *
     * @param Sample $sample
     * @param int|null $statusId
     * @return bool
     */
    public function markAsAnalysisCompleted(Sample $sample, ?int $statusId): bool
    {
        return $sample->update([
            'status_id' => $statusId,
            'status' => 'analysis_completed', // Keep for backward compatibility
            'analysis_completed_at' => now('Asia/Jakarta'),
        ]);
    }

    /**
     * Check if sample has active handover
     *
     * @param Sample $sample
     * @return bool
     */
    public function hasActiveHandover(Sample $sample): bool
    {
        return $sample->hasActiveHandover();
    }

    /**
     * Get samples by status for analyst
     * (Example of reusable query - you can add more as needed)
     */
    public function getPendingSamplesForAnalyst(int $analystId): Collection
    {
        return Sample::where(function($q) use ($analystId) {
            $q->where('primary_analyst_id', $analystId)
              ->orWhere('secondary_analyst_id', $analystId);
        })
        ->whereIn('status', ['assigned', 'in_progress'])
        ->with(['category', 'material', 'submittedBy'])
        ->orderBy('submitted_at', 'asc')
        ->get();
    }
}
