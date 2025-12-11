<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Sample;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OperatorWorkload extends Component
{
    public $selectedMonth;
    public $selectedType = 'chemical'; // chemical or solder

    public function mount()
    {
        // Default to current month
        $this->selectedMonth = now()->format('Y-m');
    }

    public function updatedSelectedMonth()
    {
        // Refresh data when month changes
        $this->dispatch('monthChanged');
    }

    public function setType($type)
    {
        $this->selectedType = $type;
    }

    private function getOperatorWorkloadData($sampleType)
    {
        $startDate = Carbon::parse($this->selectedMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($this->selectedMonth . '-01')->endOfMonth();

        // Get all samples for this type and month
        $samples = Sample::where('sample_type', $sampleType)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('analysis_started_at')
            ->with(['handovers' => function($query) {
                $query->where('status', 'accepted')
                      ->orderBy('taken_at', 'asc');
            }])
            ->get();

        // Get all operators (users with role 'operator')
        $allOperators = User::whereHas('role', function($query) {
            $query->where('name', 'operator');
        })->get();

        // Calculate work time for each operator
        $operatorData = [];
        foreach ($allOperators as $operator) {
            $totalSeconds = 0;
            $sampleCount = 0;

            foreach ($samples as $sample) {
                $workTime = $this->calculateAnalystWorkTime($sample, $operator->id);
                if ($workTime > 0) {
                    $totalSeconds += $workTime;
                    $sampleCount++;
                }
            }

            // Include all operators, even with 0 work time
            $operatorData[$operator->id] = [
                'total_seconds' => $totalSeconds,
                'total_samples' => $sampleCount,
            ];
        }

        // Format data for display
        $operators = $allOperators->map(function ($operator) use ($operatorData) {
            $data = $operatorData[$operator->id];
            $hours = floor($data['total_seconds'] / 3600);
            $minutes = floor(($data['total_seconds'] % 3600) / 60);

            return [
                'id' => $operator->id,
                'name' => $operator->name,
                'total_time' => "{$hours} Jam {$minutes} Menit",
                'total_samples' => $data['total_samples'],
                'total_seconds' => $data['total_seconds'],
            ];
        })
        ->sortByDesc('total_seconds')
        ->values();

        return $operators;
    }

    /**
     * Calculate work time for a specific analyst on a sample
     * Handles handover scenarios by splitting time accurately
     */
    private function calculateAnalystWorkTime($sample, $analystId)
    {
        if (!$sample->analysis_started_at || !$sample->analysis_completed_at) {
            return 0;
        }

        $acceptedHandovers = $sample->handovers->sortBy('taken_at');

        // If no handovers, calculate full time if this analyst is the primary analyst
        if ($acceptedHandovers->isEmpty()) {
            if ($sample->primary_analyst_id == $analystId) {
                return $sample->analysis_started_at->diffInSeconds($sample->analysis_completed_at);
            }
            return 0;
        }

        // With handovers, we need to calculate time segments
        $totalSeconds = 0;
        $previousTime = $sample->analysis_started_at;
        $previousAnalystId = $sample->primary_analyst_id;

        foreach ($acceptedHandovers as $handover) {
            if (!$handover->taken_at) {
                continue;
            }

            // Calculate time for the previous analyst
            if ($previousAnalystId == $analystId) {
                $totalSeconds += $previousTime->diffInSeconds($handover->taken_at);
            }

            // Update for next segment
            $previousTime = $handover->taken_at;
            $previousAnalystId = $handover->to_analyst_id;
        }

        // Calculate final segment (from last handover to completion)
        if ($previousAnalystId == $analystId) {
            $totalSeconds += $previousTime->diffInSeconds($sample->analysis_completed_at);
        }

        return $totalSeconds;
    }

    public function getSolderOperators()
    {
        return $this->getOperatorWorkloadData('solder');
    }

    public function getChemicalOperators()
    {
        return $this->getOperatorWorkloadData('chemical');
    }

    public function render()
    {
        // Generate month options (last 12 months)
        $monthOptions = collect();
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $monthOptions->push([
                'value' => $date->format('Y-m'),
                'label' => $date->format('F Y'),
            ]);
        }

        return view('livewire.operator-workload', [
            'monthOptions' => $monthOptions,
        ]);
    }
}
