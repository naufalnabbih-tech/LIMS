<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleHandover extends Model
{
    protected $fillable = [
        'sample_id',
        'from_analyst_id',
        'to_analyst_id',
        'new_analysis_method',        // FIXED: was 'new_analyst_method'
        'new_secondary_analyst_id',
        'notes',
        'reason',
        'handed_over_at',             // FIXED: was 'submitted_at'
        'handed_over_by',             // FIXED: was 'submitted_by'
        'taken_at',
        'taken_by',
        'status',
    ];

    protected $casts = [
        'handed_over_at' => 'datetime',  // FIXED: was 'submitted_at'
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

    // Scopes
    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopeCompleted($q)
    {
        return $q->where('status', 'completed');
    }

    public function scopeForUser($q, $userId)
    {
        return $q->where('to_analyst_id', $userId);
    }

    // Helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
