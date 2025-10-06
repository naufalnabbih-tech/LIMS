<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OperatorType;

class SolderReference extends Model
{
    protected $fillable = [
        'name',
        'solder_id'
    ];

    public function solder()
    {
        return $this->belongsTo(Solder::class, 'solder_id');
    }

    public function specificationsManytoMany()
    {
        return $this->belongsToMany(Specification::class, 'solder_reference_specification', 'solder_reference_id', 'specification_id')
                    ->withPivot('value', 'operator', 'max_value')
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
