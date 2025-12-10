<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaDocumentFormat extends Model
{
    protected $fillable = [
        'name',
        'prefix',
        'year_month',
        'middle_part',
        'suffix',
        'is_active',
        'description',
        'custom_fields',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'custom_fields' => 'array',
    ];

    /**
     * Generate document number berdasarkan format ini
     * Format: {prefix}-{YYMM}/{middle_part}/{YYYY}-{suffix}
     * Contoh: TI/COA-2503/MT/2025-S0
     */
    public function generateDocumentNumber()
    {
        $year = date('Y'); // 2025
        $number = $this->year_month;

        // Build document number
        $parts = [
            $this->prefix . '-' . $number,
            $this->middle_part,
            $year . '-' . $this->suffix
        ];

        return implode('/', array_filter($parts));
    }    /**
     * Get sequence number untuk bulan ini (per format)
     */
    public function getNextSequence()
    {
        return \App\Models\CoA::where('format_id', $this->id)
            ->whereIn('status', ['approved', 'printed', 'archived']) // Count semua yang sudah punya sequence
            ->whereYear('approved_at', date('Y'))
            ->whereMonth('approved_at', date('m'))
            ->count() + 1;
    }

    /**
     * Generate full number dengan sequence
     * Contoh: 388/TI/COA-2503/MT/2025-S0
     */
    public function generateFullNumber()
    {
        $sequence = $this->getNextSequence();
        $docNumber = $this->generateDocumentNumber();

        return "{$sequence}/{$docNumber}";
    }
}
