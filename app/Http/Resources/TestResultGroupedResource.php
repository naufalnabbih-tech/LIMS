<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResultGroupedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $grouped = $this->groupBy('parameter_name')->map(function ($tests, $parameter) {

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

        return $grouped->toArray();
    }
}
