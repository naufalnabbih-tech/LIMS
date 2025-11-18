<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OperatorType;


class Reference extends Model
{
    protected $fillable = [
        'name',
        'material_id'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }



    public function specifications()
    {
        return $this->belongsToMany(
            Specification::class,
            'reference_specification',
            'reference_id',
            'specification_id'
        )->withPivot([
                    'operator',
                    'value',
                    'max_value',
                    'text_value'
                ])->withTimestamps();
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    // Helper get specification value
    public function getSpecValue($specificationId)
    {
        $spec = $this->specifications()->where('specification_id', $specificationId)->first();

        if (!$spec) {
            return null;
        }

        return [
            'operator' => $spec->pivot->operator,
            'value' => $spec->pivot->value,
            'max_value' => $spec->pivot->max_value,
            'text_value' => $spec->pivot->text_value,
        ];
    }
}
