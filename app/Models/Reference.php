<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OperatorType;


class Reference extends Model
{
    protected $fillable = [
        'name',
        'rawmat_id'
    ];

    public function rawmat()
    {
        return $this->belongsTo(RawMat::class, 'rawmat_id');
    }

    public function specificationsManytoMany()
    {
        return $this->belongsToMany(Specification::class, 'reference_specification', 'reference_id', 'specification_id')
            ->withPivot(
                'value',
                'operator',
                'max_value',
                'text_value'
            )
            ->withTimestamps();
    }

    public function evaluateSpecification(int $specificationId, float $testValue): bool
    {
        $pivotData = $this->specificationsManytoMany()
            ->where('specification_id', $specificationId)
            ->first()?->pivot;

        if (!$pivotData) {
            return false;
        }

        $operator = OperatorType::from($pivotData->operator);

        return $operator->evaluate(
            $testValue,
            $pivotData->value
        );
    }
}
