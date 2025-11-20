<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'pending',
                'display_name' => 'Pending',
                'description' => 'Sample has been submitted and waiting for processing',
                'color' => '#6B7280',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'in_progress',
                'display_name' => 'In Progress',
                'description' => 'Sample is currently being analyzed',
                'color' => '#3B82F6',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'analysis_completed',
                'display_name' => 'Analysis Completed',
                'description' => 'Analysis has been completed, waiting for review',
                'color' => '#F59E0B',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'reviewed',
                'display_name' => 'Reviewed',
                'description' => 'Analysis results have been reviewed',
                'color' => '#8B5CF6',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'approved',
                'display_name' => 'Approved',
                'description' => 'Sample has been approved and passed all requirements',
                'color' => '#10B981',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'rejected',
                'display_name' => 'Rejected',
                'description' => 'Sample has been rejected and failed requirements',
                'color' => '#EF4444',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'submitted',
                'display_name' => 'Submitted',
                'description' => 'Sample has been submitted for processing',
                'color' => '#3B82F6',
                'sort_order' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'hand_over',
                'display_name' => 'Hand Over',
                'description' => 'Sample is in handover process, waiting to be taken by another analyst',
                'color' => '#F97316',  // Orange
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('statuses')->updateOrInsert(
                ['name' => $status['name']],
                array_merge($status, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
