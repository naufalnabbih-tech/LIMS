<?php

namespace App\Services;

class AnalysisCalculationService
{
    /**
     * Calculate parameter values (average, final, variance) from readings
     *
     * @param array $readings Array of readings with 'value' key
     * @param string|null $operator Operator type (should_be, >=, etc)
     * @return array Calculated values (average_value, final_value, variance, high_variance)
     */
    public function calculateParameterValues(array $readings, ?string $operator): array
    {
        // For "should_be" operator, handle as text values
        if ($operator === 'should_be') {
            return $this->calculateTextValues($readings);
        }

        // For numeric operators, calculate as before
        return $this->calculateNumericValues($readings);
    }

    /**
     * Calculate values for text-based operators (should_be)
     */
    private function calculateTextValues(array $readings): array
    {
        $textValues = [];
        foreach ($readings as $reading) {
            if (!empty($reading['value'])) {
                $textValues[] = $reading['value'];
            }
        }

        if (!empty($textValues)) {
            // For text values, just use the last reading as final value
            $finalValue = end($textValues);
            return [
                'final_value' => $finalValue,
                'average_value' => $finalValue, // Use final as average for display
            ];
        }

        return [
            'average_value' => null,
            'final_value' => null,
        ];
    }

    /**
     * Calculate values for numeric operators (>=, >, <=, <, =, ==, -)
     */
    private function calculateNumericValues(array $readings): array
    {
        $values = [];
        foreach ($readings as $reading) {
            if (!empty($reading['value']) && is_numeric($reading['value'])) {
                $values[] = floatval($reading['value']);
            }
        }

        if (empty($values)) {
            return [
                'average_value' => null,
                'final_value' => null,
                'variance' => null,
                'high_variance' => false,
            ];
        }

        $average = array_sum($values) / count($values);
        $finalValue = end($values);

        // Calculate variance if multiple readings
        $variance = null;
        $highVariance = false;

        if (count($values) > 1) {
            $variance = $this->calculateStandardDeviation($values);
            $highVariance = $variance > ($average * 0.05); // 5% threshold
        }

        return [
            'average_value' => $average,
            'final_value' => $finalValue,
            'variance' => $variance,
            'high_variance' => $highVariance,
        ];
    }

    /**
     * Calculate standard deviation of values
     *
     * @param array $values Array of numeric values
     * @return float Standard deviation
     */
    private function calculateStandardDeviation(array $values): float
    {
        $mean = array_sum($values) / count($values);
        $variance = 0;

        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }

        return sqrt($variance / count($values));
    }

}
