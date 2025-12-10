<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestResult extends Model
{
    protected $fillable = [
        'sample_id',
        'specification_id',
        'parameter_name',
        'test_value',
        'test_value_text',
        'reading_number',
        'tested_at',
        'tested_by',
        'edited_at',
        'edited_by',
        'notes',
        'status',
        'spec_operator',
        'spec_min_value',
        'spec_max_value',
        'spec_unit'
    ];

    protected $casts = [
        'test_value' => 'decimal:4',
        'tested_at' => 'datetime',
        'edited_at' => 'datetime',
        'reading_number' => 'integer',
        'spec_min_value' => 'float',
        'spec_max_value' => 'float'
    ];

    public function sample(): BelongsTo
    {
        return $this->belongsTo(RawMaterialSample::class, 'sample_id');
    }

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }

    public function testedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tested_by');
    }

    public function editedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function evaluateAgainstSpecification($targetValue, $operator, $maxValue = null): bool
    {
        // Handle text-based should_be operator
        if ($operator === 'should_be') {
            $testTextValue = trim($this->test_value_text ?? '');
            $targetTextValue = trim($targetValue ?? '');
            return strcasecmp($testTextValue, $targetTextValue) === 0;
        }

        // Handle numeric operators
        $testValue = floatval($this->test_value);
        $target = floatval($targetValue);

        switch ($operator) {
            case '>=':
                return $testValue >= $target;
            case '>':
                return $testValue > $target;
            case '<=':
                return $testValue <= $target;
            case '<':
                return $testValue < $target;
            case '=':
            case '==':
                return abs($testValue - $target) < 0.001;
            case '-':
            case 'range':
                if ($maxValue !== null) {
                    return $testValue >= $target && $testValue <= floatval($maxValue);
                }
                return true;
            default:
                return true;
        }
    }
}
