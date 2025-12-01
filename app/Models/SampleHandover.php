<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleHandover extends Model
{
    protected $fillable = [
        'sample_id',
        'from_analyst_id',
        'to_analyst_id',
        'new_analysis_method',
        'new_secondary_analyst_id',
        'notes',
        'reason',
        'submitted_at',               // Match database column
        'submitted_by',               // Match database column
        'taken_at',
        'taken_by',
        'status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',  // Match database column
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

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
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

    public function scopeAccepted($q)
    {
        return $q->where('status', 'accepted');
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

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }
}
