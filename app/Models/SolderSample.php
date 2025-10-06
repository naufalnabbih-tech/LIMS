<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolderSample extends Model
{
    protected $fillable = [
        'category_id',
        'solder_id',
        'reference_id',
        'supplier',
        'batch_lot',
        'vehicle_container_number',
        'has_coa',
        'coa_file_path',
        'submission_time',
        'entry_time',
        'submitted_by',
        'status',
        'notes',
        'analysis_method',
        'primary_analyst_id',
        'secondary_analyst_id',
        'analysis_started_at',
        'analysis_completed_at',
        'reviewed_at',
        'approved_at',
        'reviewed_by',
        'approved_by',
        'analysis_results',
    ];

    protected $casts = [
        'submission_time' => 'datetime',
        'entry_time' => 'datetime',
        'analysis_started_at' => 'datetime',
        'analysis_completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'has_coa' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SolderCategory::class, 'category_id');
    }

    public function solder(): BelongsTo
    {
        return $this->belongsTo(Solder::class, 'solder_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reference(): BelongsTo
    {
        return $this->belongsTo(SolderReference::class, 'reference_id');
    }

    public function primaryAnalyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'primary_analyst_id');
    }

    public function secondaryAnalyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secondary_analyst_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-orange-100 text-orange-800',
            'analysis_completed' => 'bg-purple-100 text-purple-800',
            'reviewed' => 'bg-indigo-100 text-indigo-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'submitted' => 'Submitted',
            'in_progress' => 'In Progress',
            'analysis_completed' => 'Analysis Completed',
            'reviewed' => 'Reviewed',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Unknown',
        };
    }
}
