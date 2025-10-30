<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResultBySampleGroupedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Group by sample_id first
        $groupedBySample = $this->groupBy('sample_id')->map(function ($sampleTests, $sampleId) {

            // Get sample info from first test result
            $first = $sampleTests->first();

            // Group by parameter within this sample
            $groupedByParameter = $sampleTests->groupBy('parameter_name')->map(function ($tests, $parameter) {

                $first = $tests->first();
                $readings = $tests->map(function ($test) {
                    return [
                        'reading' => $test->reading_number,
                        'value' => $test->test_value,
                        'tested_at' => $test->tested_at,
                    ];
                })->values();

                return [
                    'parameter_name' => $parameter,
                    'specification_id' => $first->specification_id,
                    'status' => $first->status,
                    'readings' => $readings,
                    'average' => number_format($tests->avg('test_value'), 4),
                    'min' => number_format($tests->min('test_value'), 4),
                    'max' => number_format($tests->max('test_value'), 4),
                    'count' => $tests->count(),
                ];
            })->values();

            return [
                'sample_id' => $sampleId,
                'batch_lot' => $first->sample->batch_lot ?? null,
                'supplier' => $first->sample->supplier ?? null,
                'sample_status' => $first->sample->status ?? null,
                'parameters' => $groupedByParameter,
            ];
        })->values();

        return $groupedBySample->toArray();
    }
}
