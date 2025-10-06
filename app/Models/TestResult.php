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
        'unit',
        'reading_number',
        'tested_at',
        'tested_by',
        'notes',
        'status'
    ];

    protected $casts = [
        'test_value' => 'decimal:4',
        'tested_at' => 'datetime',
        'reading_number' => 'integer'
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

    public function evaluateAgainstSpecification($targetValue, $operator, $maxValue = null): bool
    {
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
