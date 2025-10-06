<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solder extends Model
{
    protected $fillable = [
        'name',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(SolderCategory::class,'category_id');
    }

    public function references()
    {
        return $this->hasMany(SolderReference::class,'solder_id');
    }
}
