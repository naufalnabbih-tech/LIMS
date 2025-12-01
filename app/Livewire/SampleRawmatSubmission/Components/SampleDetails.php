<?php

namespace App\Livewire\SampleRawmatSubmission\Components;

use Livewire\Component;
use App\Models\Sample;
use App\Models\Status;

class SampleDetails extends Component
{
    public $sample;
    public $show = false;

    protected $listeners = [
        'openSampleDetails' => 'open',
        'closeSampleDetails' => 'close'
    ];

    /**
     * Open the sample details modal
     *
     * @param int $sampleId
     * @return void
     */
    public function open($sampleId)
    {
        $this->sample = Sample::with([
            'category',
            'material',
            'reference',
            'submittedBy',
            'statusRelation',
            'primaryAnalyst',
            'secondaryAnalyst',
            'testResults.testedBy',
            'handovers.fromAnalyst',
            'handovers.toAnalyst',
            'handovers.newSecondaryAnalyst',
            'reviewedBy',
            'approvedBy'
        ])->find($sampleId);

        $this->show = true;
    }

    /**
     * Close the sample details modal
     *
     * @return void
     */
    public function close()
    {
        $this->show = false;
        $this->sample = null;
    }

    /**
     * Emit event to edit sample
     *
     * @return void
     */
    public function editSample()
    {
        if ($this->sample) {
            $this->dispatch('editSample', sampleId: $this->sample->id);
            $this->close();
        }
    }

    /**
     * Print sample label
     *
     * @return void
     */
    public function printSampleLabel()
    {
        if ($this->sample) {
            $this->dispatch('printSampleLabel', sampleId: $this->sample->id);
        }
    }

    /**
     * Get current status name
     *
     * @return string
     */
    public function getCurrentStatusName()
    {
        if (!$this->sample) {
            return '';
        }

        return $this->sample->statusRelation
            ? $this->sample->statusRelation->name
            : ($this->sample->status ?? '');
    }

    /**
     * Build status history for the sample
     *
     * @return array
     */
    public function getStatusHistory()
    {
        if (!$this->sample) {
            return [];
        }

        $allStatuses = Status::all()->keyBy('name');

        $getStatusData = function($statusName) use ($allStatuses) {
            $status = $allStatuses->get($statusName);
            if ($status) {
                $colorMap = [
                    '#6B7280' => 'bg-gray-100 text-gray-800',
                    '#3B82F6' => 'bg-blue-100 text-blue-800',
                    '#F59E0B' => 'bg-amber-100 text-amber-800',
                    '#8B5CF6' => 'bg-purple-100 text-purple-800',
                    '#10B981' => 'bg-green-100 text-green-800',
                    '#EF4444' => 'bg-red-100 text-red-800',
                ];
                return [
                    'display_name' => $status->display_name,
                    'color_class' => $colorMap[$status->color] ?? 'bg-gray-100 text-gray-800'
                ];
            }
            return [
                'display_name' => ucfirst(str_replace('_', ' ', $statusName)),
                'color_class' => 'bg-gray-100 text-gray-800'
            ];
        };

        $statusHistory = [];
        $counter = 1;

        // Get all handovers ordered by time to track analyst changes
        $handovers = $this->sample->handovers()
            ->with(['fromAnalyst', 'toAnalyst'])
            ->orderBy('submitted_at')
            ->get();

        // Helper function to get the correct analyst at a specific time
        $getAnalystAtTime = function($timestamp) use ($handovers) {
            // Find the most recent handover BEFORE this timestamp that was accepted
            $relevantHandover = $handovers
                ->where('taken_at', '!=', null)
                ->where('taken_at', '<=', $timestamp)
                ->sortByDesc('taken_at')
                ->first();

            if ($relevantHandover) {
                return $relevantHandover->toAnalyst;
            }

            // If no handover before this time, use the original primary analyst
            // Get from the first handover's fromAnalyst, or current primaryAnalyst
            $firstHandover = $handovers->first();
            if ($firstHandover && $firstHandover->fromAnalyst) {
                return $firstHandover->fromAnalyst;
            }

            return $this->sample->primaryAnalyst;
        };

        // 1. Sample Submitted/Created
        $statusHistory[] = [
            'id' => $counter++,
            'time_in' => $this->sample->created_at,
            'status' => $getStatusData('pending')['display_name'],
            'status_color' => $getStatusData('pending')['color_class'],
            'analyst' => $this->sample->submittedBy->name ?? 'System',
            'previous_time' => null,
        ];

        // Collect ALL events to sort chronologically
        $events = [];

        // 2. Analysis Started
        if ($this->sample->analysis_started_at) {
            $analyst = $getAnalystAtTime($this->sample->analysis_started_at);
            $analysts = [];
            if ($analyst) {
                $analysts[] = $analyst->name;
            }
            if ($this->sample->analysis_method === 'joint' && $this->sample->secondaryAnalyst) {
                $analysts[] = $this->sample->secondaryAnalyst->name;
            }
            $analystText = !empty($analysts) ? implode(' & ', $analysts) : 'Unknown Analyst';

            $events[] = [
                'time' => $this->sample->analysis_started_at,
                'status' => $getStatusData('in_progress')['display_name'],
                'status_color' => $getStatusData('in_progress')['color_class'],
                'analyst' => $analystText,
            ];
        }

        // 3. Analysis Completed
        if ($this->sample->analysis_completed_at) {
            $analyst = $getAnalystAtTime($this->sample->analysis_completed_at);
            $analysts = [];
            if ($analyst) {
                $analysts[] = $analyst->name;
            }
            if ($this->sample->analysis_method === 'joint' && $this->sample->secondaryAnalyst) {
                $analysts[] = $this->sample->secondaryAnalyst->name;
            }
            $analystText = !empty($analysts) ? implode(' & ', $analysts) : 'Unknown Analyst';

            $events[] = [
                'time' => $this->sample->analysis_completed_at,
                'status' => $getStatusData('analysis_completed')['display_name'],
                'status_color' => $getStatusData('analysis_completed')['color_class'],
                'analyst' => $analystText,
            ];
        }

        // 4. Handovers
        foreach ($handovers as $handover) {
            // Hand over submitted
            if ($handover->submitted_at) {
                $events[] = [
                    'time' => $handover->submitted_at,
                    'status' => $getStatusData('hand_over')['display_name'],
                    'status_color' => $getStatusData('hand_over')['color_class'],
                    'analyst' => ($handover->fromAnalyst->name ?? 'Unknown') . ' → ' . ($handover->toAnalyst->name ?? 'Unknown'),
                ];
            }

            // Hand over taken/accepted
            if ($handover->taken_at) {
                $events[] = [
                    'time' => $handover->taken_at,
                    'status' => $getStatusData('in_progress')['display_name'],
                    'status_color' => $getStatusData('in_progress')['color_class'],
                    'analyst' => $handover->toAnalyst->name ?? 'Unknown',
                ];
            }
        }

        if ($this->sample->reviewed_at) {
            $events[] = [
                'time' => $this->sample->reviewed_at,
                'status' => $getStatusData('reviewed')['display_name'],
                'status_color' => $getStatusData('reviewed')['color_class'],
                'analyst' => $this->sample->reviewedBy->name ?? 'QC Manager',
            ];
        }

        if ($this->sample->approved_at) {
            $events[] = [
                'time' => $this->sample->approved_at,
                'status' => $getStatusData('approved')['display_name'],
                'status_color' => $getStatusData('approved')['color_class'],
                'analyst' => $this->sample->approvedBy->name ?? 'QC Manager',
            ];
        }

        // Sort events chronologically
        usort($events, function ($a, $b) {
            return $a['time']->timestamp <=> $b['time']->timestamp;
        });

        // Add sorted events to status history
        foreach ($events as $event) {
            $lastEntry = !empty($statusHistory) ? end($statusHistory) : null;
            $statusHistory[] = [
                'id' => $counter++,
                'time_in' => $event['time'],
                'status' => $event['status'],
                'status_color' => $event['status_color'],
                'analyst' => $event['analyst'],
                'previous_time' => $lastEntry ? $lastEntry['time_in'] : null,
            ];
        }

        // Handle rejected status
        $currentStatusName = $this->getCurrentStatusName();
        if ($currentStatusName === 'rejected') {
            $lastEntry = !empty($statusHistory) ? end($statusHistory) : null;
            if (!$lastEntry || $lastEntry['status'] !== 'Rejected') {
                $statusHistory[] = [
                    'id' => $counter++,
                    'time_in' => $this->sample->updated_at,
                    'status' => $getStatusData('rejected')['display_name'],
                    'status_color' => $getStatusData('rejected')['color_class'],
                    'analyst' => 'QC Manager',
                    'previous_time' => $lastEntry ? $lastEntry['time_in'] : null,
                ];
            }
        }

        return $statusHistory;
    }

    public function render()
    {
        return view('livewire.sample-rawmat-submission.components.sample-details', [
            'currentStatusName' => $this->getCurrentStatusName(),
            'statusHistory' => $this->getStatusHistory(),
        ]);
    }
}
