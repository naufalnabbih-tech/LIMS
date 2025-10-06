<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMat extends Model
{
    protected $fillable = [
        'name',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(RawMatCategory::class,'category_id');
    }

    public function references()
    {
        return $this->hasMany(Reference::class,'rawmat_id');
    }
}
