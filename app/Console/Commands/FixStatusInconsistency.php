<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RawMaterialSample;
use App\Models\Status;

class FixStatusInconsistency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:status-inconsistency {--dry-run : Show what would be fixed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix inconsistencies between status field and status_id field';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        $this->info('🔧 Checking for status inconsistencies...');

        // Get all status mappings
        $statuses = Status::all()->keyBy('name');

        // Find samples with inconsistent status
        $inconsistentSamples = RawMaterialSample::with('statusRelation')
            ->whereNotNull('status')
            ->whereNotNull('status_id')
            ->get()
            ->filter(function ($sample) use ($statuses) {
                $expectedStatus = $statuses->get($sample->status);
                return $expectedStatus && $sample->status_id != $expectedStatus->id;
            });

        if ($inconsistentSamples->isEmpty()) {
            $this->info('✅ No inconsistencies found. All samples have matching status fields.');
            return 0;
        }

        $this->info('🚨 Found ' . $inconsistentSamples->count() . ' samples with inconsistent status');

        $fixed = 0;
        foreach ($inconsistentSamples as $sample) {
            $expectedStatus = $statuses->get($sample->status);

            if ($dryRun) {
                $this->line("Would fix Sample ID {$sample->id}: status='{$sample->status}' (status_id: {$sample->status_id} -> {$expectedStatus->id})");
            } else {
                $sample->update(['status_id' => $expectedStatus->id]);
                $this->line("✅ Fixed Sample ID {$sample->id}: status='{$sample->status}' (status_id: {$sample->status_id} -> {$expectedStatus->id})");
            }

            $fixed++;
        }

        if ($dryRun) {
            $this->info("🔍 DRY RUN SUMMARY: Would fix {$fixed} samples");
            $this->info("Run without --dry-run to perform the actual fixes");
        } else {
            $this->info("🎉 FIXED: {$fixed} samples now have consistent status fields");
        }

        return 0;
    }
}
