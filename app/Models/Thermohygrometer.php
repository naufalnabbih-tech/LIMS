<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thermohygrometer extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function conditions(): HasMany
    {
        return $this->hasMany(ThermohygrometerCondition::class);
    }
}
