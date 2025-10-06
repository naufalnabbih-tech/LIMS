<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InstrumentCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_id',
        'shift',
        'operator_name',
        'condition',
        'description',
        'time',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
    ];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public static function getShiftOptions()
    {
        return [
            'Shift 1' => 'Shift 1 (00:00-07:59)',
            'Shift 2' => 'Shift 2 (08:00-15:59)',
            'Shift 3' => 'Shift 3 (16:00-23:59)',
        ];
    }

    public static function getConditionOptions()
    {
        return [
            'good' => 'Good',
            'damaged' => 'Damaged',
        ];
    }

    public static function getCurrentShift($time = null)
    {
        $time = $time ? Carbon::parse($time, 'Asia/Jakarta') : Carbon::now('Asia/Jakarta');
        $hour = $time->format('H');

        if ($hour >= 0 && $hour <= 7) {
            return 'Shift 1';
        } elseif ($hour >= 8 && $hour <= 15) {
            return 'Shift 2';
        } else {
            return 'Shift 3';
        }
    }

    public function getShiftDisplayAttribute()
    {
        $options = self::getShiftOptions();
        return $options[$this->shift] ?? $this->shift;
    }
}