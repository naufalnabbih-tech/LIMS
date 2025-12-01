<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $fillable = [
        'name',
    ];

    public function referenceManytoMany()
    {
       return $this->belongsToMany(Reference::class, 'reference_specification', 'specification_id', 'reference_id');
    }

    // Alias for backward compatibility - now uses unified references table
    public function solderReferenceManytoMany()
    {
       return $this->referenceManytoMany();
    }

    public function chemicalReferenceManytoMany()
    {
       return $this->referenceManytoMany();
    }

    public function references()
    {
        return $this->belongsToMany(Reference::class, 'reference_specification', 'specification_id', 'reference_id')
            ->withPivot(['operator', 'value', 'max_value', 'text_value'])
            ->withTimestamps();
    }
}
