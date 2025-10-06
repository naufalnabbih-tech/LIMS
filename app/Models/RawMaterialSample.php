<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterialSample extends Model
{
    protected $fillable = [
        'category_id',
        'raw_mat_id',
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
        'status_id',
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
        'rejected_at',
        'rejected_by',
        'handover_to_analyst_id',
        'handover_notes',
        'analysis_results',
        // Original fields for history tracking
        'original_primary_analyst_id',
        'original_secondary_analyst_id',
        'original_analysis_method',
        // Hand over tracking fields
        'handover_submitted_by',
        'handover_submitted_at',
        'handover_taken_by',
        'handover_taken_at',
    ];

    protected $casts = [
        'submission_time' => 'datetime',
        'entry_time' => 'datetime',
        'analysis_started_at' => 'datetime',
        'analysis_completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'has_coa' => 'boolean',
        'handover_submitted_at' => 'datetime',
        'handover_taken_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RawMatCategory::class, 'category_id');
    }

    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMat::class, 'raw_mat_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reference(): BelongsTo
    {
        return $this->belongsTo(Reference::class, 'reference_id');
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

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function handoverToAnalyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handover_to_analyst_id');
    }

    public function originalPrimaryAnalyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_primary_analyst_id');
    }

    public function originalSecondaryAnalyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_secondary_analyst_id');
    }

    public function handoverSubmittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handover_submitted_by');
    }

    public function handoverTakenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handover_taken_by');
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(TestResult::class, 'sample_id');
    }

    public function statusRelation(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function getStatusColorAttribute(): string
    {
        // If using new status relationship and status is loaded
        if ($this->relationLoaded('statusRelation') && $this->statusRelation && is_object($this->statusRelation) && $this->statusRelation->color) {
            // Use Tailwind classes based on color
            $colorMap = [
                '#6B7280' => 'bg-gray-100 text-gray-800',
                '#3B82F6' => 'bg-blue-100 text-blue-800',
                '#F59E0B' => 'bg-amber-100 text-amber-800',
                '#8B5CF6' => 'bg-purple-100 text-purple-800',
                '#10B981' => 'bg-green-100 text-green-800',
                '#EF4444' => 'bg-red-100 text-red-800',
            ];

            return $colorMap[$this->statusRelation->color] ?? 'bg-gray-100 text-gray-800';
        }

        // Fallback to old status field using string value
        $statusString = '';
        if (is_string($this->status)) {
            $statusString = $this->status;
        } elseif (isset($this->attributes['status'])) {
            $statusString = $this->attributes['status'];
        }

        return match($statusString) {
            'pending' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'analysis_completed' => 'bg-amber-100 text-amber-800',
            'submitted_to_handover' => 'bg-yellow-100 text-yellow-800',
            'review' => 'bg-purple-100 text-purple-800',
            'reviewed' => 'bg-purple-100 text-purple-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'restart_analysis' => 'bg-amber-100 text-amber-800',
            'in_progress_restart' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        // If using new status relationship and status is loaded
        if ($this->relationLoaded('statusRelation') && $this->statusRelation && is_object($this->statusRelation)) {
            return $this->statusRelation->display_name;
        }

        // Fallback to old status field using string value
        $statusString = '';
        if (is_string($this->status)) {
            $statusString = $this->status;
        } elseif (isset($this->attributes['status'])) {
            $statusString = $this->attributes['status'];
        }

        return match($statusString) {
            'pending' => 'Pending',
            'submitted' => 'Submitted',
            'in_progress' => 'In Progress',
            'analysis_completed' => 'Analysis Completed',
            'submitted_to_handover' => 'Submitted to Hand Over',
            'review' => 'Review',
            'reviewed' => 'Reviewed',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'restart_analysis' => 'Restart Analysis',
            'in_progress_restart' => 'In Progress (Restart)',
            default => 'Unknown',
        };
    }
}
