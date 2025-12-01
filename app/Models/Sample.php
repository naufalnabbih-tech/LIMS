<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = [
        'sample_type',
        'category_id',
        'material_id',
        'reference_id',

        //sample details
        'supplier',
        'batch_lot',
        'vehicle_container_number',
        'has_coa',
        'coa_file_path',

        //submission
        'submission_time',
        'entry_time',
        'submitted_by',

        'status_id',

        //analysis workflow
        'analysis_method',
        'primary_analyst_id',
        'secondary_analyst_id',
        'analysis_started_at',
        'analysis_completed_at',


        //review & approval
        'reviewed_at',
        'approved_at',
        'reviewed_by',
        'approved_by',
        //rejection
        'rejected_at',
        'rejected_by',
        //results & notes
        'analysis_results',
        'notes',
    ];

    protected $casts = [
        'has_coa' => 'boolean',
        'submission_time' => 'datetime',
        'entry_time' => 'datetime',
        'analysis_started_at' => 'datetime',
        'analysis_completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'analysis_results' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function primaryAnalyst()
    {
        return $this->belongsTo(User::class, 'primary_analyst_id');
    }

    public function secondaryAnalyst()
    {
        return $this->belongsTo(User::class, 'secondary_analyst_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    // Alias for backward compatibility
    public function statusRelation()
    {
        return $this->status();
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function handovers()
    {
        return $this->hasMany(SampleHandover::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function scopeRawMaterial($q)
    {
        return $q->where('sample_type', 'raw_material');
    }

    public function scopeSolder($q)
    {
        return $q->where('sample_type', 'solder');
    }

    public function isPending()
    {
        if (!$this->status) return false;
        return in_array(strtolower($this->status->name), ['pending', 'submitted']);
    }

    public function isInAnalysis()
    {
        if (!$this->status) return false;
        return in_array(strtolower($this->status->name), ['in_progress', 'in progress', 'analysis_started']);
    }

    public function isCompleted()
    {
        if (!$this->status) return false;
        return in_array(strtolower($this->status->name), ['completed', 'analysis_completed']);
    }

    public function hasActiveHandover()
    {
        return $this->handovers()->where('status', 'pending')->exists();
    }

    public function getActiveHandover()
    {
        return $this->handovers()->where('status', 'pending')->first();
    }

    // Accessor for status label
    public function getStatusLabelAttribute()
    {
        if ($this->status) {
            return $this->status->display_name ?? $this->status->name;
        }
        return 'Pending';
    }

    // Accessor for status color (Tailwind CSS classes)
    public function getStatusColorAttribute()
    {
        if (!$this->status) {
            return 'bg-gray-100 text-gray-800';
        }

        // Map hex colors to Tailwind classes
        $colorMap = [
            '#6B7280' => 'bg-gray-100 text-gray-800',       // Gray - Pending
            '#3B82F6' => 'bg-blue-100 text-blue-800',       // Blue - In Progress
            '#F59E0B' => 'bg-amber-100 text-amber-800',     // Amber - Analysis Completed
            '#F97316' => 'bg-orange-100 text-orange-800',   // Orange - Hand Over
            '#8B5CF6' => 'bg-purple-100 text-purple-800',   // Purple - Reviewed
            '#10B981' => 'bg-green-100 text-green-800',     // Green - Approved
            '#EF4444' => 'bg-red-100 text-red-800',         // Red - Rejected
        ];

        return $colorMap[$this->status->color] ?? 'bg-gray-100 text-gray-800';
    }
}
