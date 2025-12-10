<?php

namespace Database\Seeders;

use App\Models\CoaDocumentFormat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoaDocumentFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoaDocumentFormat::create([
            'name' => 'Format Default PT Timah Industri',
            'prefix' => 'TI/COA',
            'year_month' => date('ym'),
            'middle_part' => 'MT',
            'suffix' => 'S0',
            'is_active' => true,
            'is_default' => true,
            'description' => 'Format default PT Timah Industri'
        ]);

        // Example dengan nomor berbeda
        CoaDocumentFormat::create([
            'name' => 'Format Custom 2512',
            'prefix' => 'TI/COA',
            'year_month' => '2512',
            'middle_part' => 'MT',
            'suffix' => 'S0',
            'is_active' => true,
            'is_default' => false,
            'description' => 'Format dengan nomor: 2512'
        ]);
    }
}
