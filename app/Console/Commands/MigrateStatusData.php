<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RawMaterialSample;
use App\Models\Status;

class MigrateStatusData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:status-data {--dry-run : Show what would be migrated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing status string data to status_id foreign key';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        $this->info('🚀 Starting status data migration...');

        // Get all status mappings
        $statuses = Status::all()->keyBy('name');

        if ($statuses->isEmpty()) {
            $this->error('❌ No statuses found in statuses table. Please run StatusSeeder first.');
            return 1;
        }

        $this->info('📋 Found ' . $statuses->count() . ' statuses in statuses table');

        // Get samples that need migration (have status but no status_id)
        $samplesToMigrate = RawMaterialSample::whereNotNull('status')
            ->whereNull('status_id')
            ->get();

        if ($samplesToMigrate->isEmpty()) {
            $this->info('✅ No samples need migration. All samples already have status_id set.');
            return 0;
        }

        $this->info('📦 Found ' . $samplesToMigrate->count() . ' samples to migrate');

        $migrated = 0;
        $skipped = 0;

        foreach ($samplesToMigrate as $sample) {
            $statusName = $sample->status;

            if (!$statusName) {
                $this->warn("⚠️  Sample ID {$sample->id} has null status, skipping");
                $skipped++;
                continue;
            }

            $status = $statuses->get($statusName);

            if (!$status) {
                $this->warn("⚠️  Sample ID {$sample->id} has unknown status '{$statusName}', skipping");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("Would migrate Sample ID {$sample->id}: '{$statusName}' -> status_id {$status->id}");
            } else {
                $sample->update(['status_id' => $status->id]);
                $this->line("✅ Migrated Sample ID {$sample->id}: '{$statusName}' -> status_id {$status->id}");
            }

            $migrated++;
        }

        if ($dryRun) {
            $this->info("🔍 DRY RUN SUMMARY:");
            $this->info("  - Would migrate: {$migrated} samples");
            $this->info("  - Would skip: {$skipped} samples");
            $this->info("");
            $this->info("Run without --dry-run to perform the actual migration");
        } else {
            $this->info("🎉 MIGRATION COMPLETE:");
            $this->info("  - Migrated: {$migrated} samples");
            $this->info("  - Skipped: {$skipped} samples");
        }

        return 0;
    }
}
