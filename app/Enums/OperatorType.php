<?php

namespace App\Enums;

enum OperatorType: string
{
    case GREATER_THAN_OR_EQUAL = '>=';
    case LESS_THAN_OR_EQUAL = '<=';
    case EQUAL = '==';
    case RANGE = '-';
    case CONTAINS = 'contains';
    case SHOULD_BE = 'should_be';

    public function isRange(): bool
    {
        return $this === self::RANGE;
    }

    public function isNumericComparison(): bool
    {
        return in_array($this, [self::GREATER_THAN_OR_EQUAL, self::LESS_THAN_OR_EQUAL, self::EQUAL, self::RANGE]);
    }

    public function isStringComparison(): bool
    {
        return in_array($this, [self::CONTAINS, self::SHOULD_BE]);
    }

    public function isComparison(): bool
    {
        return $this->isNumericComparison() || $this->isStringComparison();
    }

    public function evaluate($testValue, $value, ?float $maxValue = null): bool
    {
        return match ($this) {
            self::GREATER_THAN_OR_EQUAL => (float) $testValue >= (float) $value,
            self::LESS_THAN_OR_EQUAL => (float) $testValue <= (float) $value,
            self::EQUAL => is_numeric($testValue) && is_numeric($value) 
                ? abs((float) $testValue - (float) $value) < 0.0001 
                : (string) $testValue === (string) $value,
            self::RANGE => $this->evaluateRanges((float) $testValue, $value),
            self::CONTAINS => $this->evaluateContains((string) $testValue, (string) $value),
            self::SHOULD_BE => $this->evaluateShouldBe((string) $testValue, (string) $value),
        };
    }

    private function evaluateRanges(float $testValue, $rangeData): bool
    {
        // Handle JSON string or array of ranges
        $ranges = is_string($rangeData) ? json_decode($rangeData, true) : $rangeData;
        
        if (!is_array($ranges)) {
            return false;
        }

        // Check if test value falls within any of the ranges
        foreach ($ranges as $range) {
            if (isset($range['min']) && isset($range['max'])) {
                $min = (float) $range['min'];
                $max = (float) $range['max'];
                if ($testValue >= $min && $testValue <= $max) {
                    return true;
                }
            }
        }

        return false;
    }

    private function evaluateContains(string $testValue, string $value): bool
    {
        // Check if test value contains the expected value (case-insensitive)
        return stripos($testValue, $value) !== false;
    }

    private function evaluateShouldBe(string $testValue, string $value): bool
    {
        // For "should be", we can check multiple expected values separated by comma
        $expectedValues = array_map('trim', explode(',', $value));
        $testValue = trim($testValue);
        
        foreach ($expectedValues as $expectedValue) {
            if (strcasecmp($testValue, $expectedValue) === 0) {
                return true;
            }
        }
        
        return false;
    }

    public static function getLabels(): array
    {
        return [
            self::GREATER_THAN_OR_EQUAL->value => 'Greater than or equal (>=)',
            self::LESS_THAN_OR_EQUAL->value => 'Less than or equal (<=)',
            self::EQUAL->value => 'Equal (==)',
            self::RANGE->value => 'Range (-)',
            self::CONTAINS->value => 'Contains',
            self::SHOULD_BE->value => 'Should be',
        ];
    }
}