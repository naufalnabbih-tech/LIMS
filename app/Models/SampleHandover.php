<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleHandover extends Model
{
    protected $fillable = [
        'sample_id',
        'from_analyst_id',
        'to_analyst_id',
        'new_analyst_method',
        'new_secondary_analyst_id',
        'notes',
        'reason',
        'submitted_at',
        'taken_at',
        'submitted_by',
        'taken_by',
        'status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'taken_at' => 'datetime',
    ];

    // Relations
    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function fromAnalyst()
    {
        return $this->belongsTo(User::class, 'from_analyst_id');
    }

    public function toAnalyst()
    {
        return $this->belongsTo(User::class, 'to_analyst_id');
    }

    public function newSecondaryAnalyst()
    {
        return $this->belongsTo(User::class, 'new_secondary_analyst_id');
    }

    public function handedOverBy()
    {
        return $this->belongsTo(User::class, 'handed_over_by');
    }

    public function takenBy()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }

    //scopes
    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopeCompleted($q)
    {
        return $q->where('status', 'completed');
    }
}
