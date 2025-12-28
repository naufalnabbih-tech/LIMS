<?php

namespace App\Services;

class SpecificationEvaluationService
{
    public function evaluate($testValue, $targetValue, string $operator, $maxValue = null): bool
    {
        return match ($operator) {
            'should_be' => strcasecmp(trim($testValue), trim($targetValue)) === 0,

            // Convert to float
            '>=' => floatval($testValue) >= floatval($targetValue),
            '>' => floatval($testValue) > floatval($targetValue),
            '<=' => floatval($testValue) <= floatval($targetValue),
            '<' => floatval($testValue) < floatval($targetValue),
            '=', '==' => abs(floatval($testValue) - floatval($targetValue)) < 0.001,

            '-' => $maxValue !== null
            ? (floatval($testValue) >= floatval($targetValue) && floatval($testValue) <= floatval($maxValue))
            : true,

            default => true,
        };
    }
}
