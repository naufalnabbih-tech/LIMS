<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoA extends Model
{
    protected $table = 'coas';

    protected $fillable = [
        'document_number',
        'sample_id',
        'sample_type',
        'format_id',
        'status',
        'release_date',
        'net_weight',
        'po_no',
        'approved_by',
        'approved_at',
        'notes',
        'file_path',
        'data'
    ];

    protected $casts = [
        'release_date' => 'datetime',
        'approved_at' => 'datetime',
        'data' => 'array'
    ];

    // Relationships
    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function format()
    {
        return $this->belongsTo(CoaDocumentFormat::class, 'format_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDocumentNumber($query, $number)
    {
        return $query->where('document_number', 'like', "%{$number}%");
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('issued_date', [$startDate, $endDate]);
    }

    // Status Methods
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isPending()
    {
        return $this->status === 'pending_review';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPrinted()
    {
        return $this->status === 'printed';
    }

    public function isArchived()
    {
        return $this->status === 'archived';
    }

    // Status Badge
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'pending_review' => 'Pending Review',
            'approved' => 'Approved',
            'printed' => 'Printed',
            'archived' => 'Archived',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'pending_review' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'printed' => 'bg-blue-100 text-blue-800',
            'archived' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
