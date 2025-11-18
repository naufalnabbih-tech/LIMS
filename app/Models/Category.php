<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'type',
        'name',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    // Scopes
    public function scopeRawMaterial($query)
    {
        return $query->where('type', 'raw_material');
    }

    public function scopeSolder($query)
    {
        return $query->where('type', 'solder');
    }
}
