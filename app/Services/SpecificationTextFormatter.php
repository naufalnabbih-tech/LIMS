<?php

namespace App\Services;

class SpecificationTextFormatter
{
    public function format(object $pivot): string
    {
        if (!$pivot->operator) {
            return 'As per reference';
        }

        return match (true) {
            $pivot->operator === '-' && $pivot->value !== null
            => "{$pivot->value} - {$pivot->max_value}",

            $pivot->operator === 'should_be' && $pivot->text_value !== null
            => "= {$pivot->text_value}",

            $pivot->value !== null
            => $this->formatStandardOperator($pivot->operator, $pivot->value),

            default => 'As per reference'
        };
    }

    private function formatStandardOperator(string $operator, $value): string
    {
        $displayOperator = $operator === '==' ? '=' : $operator;
        return "$displayOperator $value";
    }
}
