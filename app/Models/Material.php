<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }
}
