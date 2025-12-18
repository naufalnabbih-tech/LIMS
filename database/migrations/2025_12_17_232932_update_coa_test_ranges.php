<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing CoA records to populate min/max ranges from TestResults
        $coas = \DB::table('coas')->get();

        foreach ($coas as $coa) {
            $data = json_decode($coa->data, true);
            $updated = false;

            if (!isset($data['tests']) || !is_array($data['tests'])) {
                continue;
            }

            foreach ($data['tests'] as $key => $test) {
                // For tests with "-" spec, try to get min/max from TestResult
                if (($test['spec'] ?? '') === '-' && isset($test['name'])) {
                    $testResult = \DB::table('test_results')
                        ->where('sample_id', $data['sample_id'] ?? null)
                        ->where('parameter_name', $test['name'])
                        ->first();

                    if ($testResult && ($testResult->spec_min_value || $testResult->spec_max_value)) {
                        $data['tests'][$key]['operator'] = 'range';
                        $data['tests'][$key]['min'] = $testResult->spec_min_value;
                        $data['tests'][$key]['max'] = $testResult->spec_max_value;
                        $data['tests'][$key]['spec'] = ($testResult->spec_min_value ?? '-') . ' - ' . ($testResult->spec_max_value ?? '-');
                        $updated = true;
                    }
                }
                // For should_be tests, try to get the text value from TestResult
                elseif (($test['spec'] ?? '') === 'should_be ' && isset($test['name'])) {
                    $testResult = \DB::table('test_results')
                        ->where('sample_id', $data['sample_id'] ?? null)
                        ->where('parameter_name', $test['name'])
                        ->first();

                    if ($testResult && $testResult->test_value_text) {
                        $data['tests'][$key]['operator'] = 'should_be';
                        $data['tests'][$key]['value'] = $testResult->test_value_text;
                        $data['tests'][$key]['spec'] = $testResult->test_value_text;
                        $updated = true;
                    }
                }
            }

            if ($updated) {
                \DB::table('coas')->where('id', $coa->id)->update([
                    'data' => json_encode($data)
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration adds fields but doesn't remove schema, so no action needed on rollback
    }
};
