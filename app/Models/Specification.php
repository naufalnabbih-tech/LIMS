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

    public function solderReferenceManytoMany()
    {
       return $this->belongsToMany(SolderReference::class, 'solder_reference_specification', 'specification_id', 'solder_reference_id');
    }
}
