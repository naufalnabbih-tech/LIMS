<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\RawMaterialSample;
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
        $this->sample = RawMaterialSample::with([
            'category',
            'rawMaterial',
            'reference',
            'submittedBy',
            'statusRelation',
            'primaryAnalyst',
            'secondaryAnalyst',
            'testResults.testedBy',
            'handoverSubmittedBy',
            'handoverTakenBy',
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

        // 1. Sample Submitted/Created
        $statusHistory[] = [
            'id' => $counter++,
            'time_in' => $this->sample->created_at,
            'status' => $getStatusData('pending')['display_name'],
            'status_color' => $getStatusData('pending')['color_class'],
            'analyst' => $this->sample->submittedBy->name ?? 'System',
            'previous_time' => null,
        ];

        // 2. Analysis Started
        if ($this->sample->analysis_started_at) {
            $analysts = [];
            if ($this->sample->primaryAnalyst) {
                $analysts[] = $this->sample->primaryAnalyst->name;
            }
            if ($this->sample->analysis_method === 'joint' && $this->sample->secondaryAnalyst) {
                $analysts[] = $this->sample->secondaryAnalyst->name;
            }
            $analystText = !empty($analysts) ? implode(' & ', $analysts) : 'Unknown Analyst';

            $statusHistory[] = [
                'id' => $counter++,
                'time_in' => $this->sample->analysis_started_at,
                'status' => $getStatusData('in_progress')['display_name'],
                'status_color' => $getStatusData('in_progress')['color_class'],
                'analyst' => $analystText,
                'previous_time' => end($statusHistory)['time_in'],
            ];
        }

        // 3. Analysis Completed
        if ($this->sample->analysis_completed_at) {
            $analysts = [];
            if ($this->sample->primaryAnalyst) {
                $analysts[] = $this->sample->primaryAnalyst->name;
            }
            if ($this->sample->analysis_method === 'joint' && $this->sample->secondaryAnalyst) {
                $analysts[] = $this->sample->secondaryAnalyst->name;
            }
            $analystText = !empty($analysts) ? implode(' & ', $analysts) : 'Unknown Analyst';

            $statusHistory[] = [
                'id' => $counter++,
                'time_in' => $this->sample->analysis_completed_at,
                'status' => $getStatusData('analysis_completed')['display_name'],
                'status_color' => $getStatusData('analysis_completed')['color_class'],
                'analyst' => $analystText,
                'previous_time' => end($statusHistory)['time_in'],
            ];
        }

        // Collect events
        $events = [];

        if ($this->sample->handover_submitted_at) {
            $events[] = [
                'time' => $this->sample->handover_submitted_at,
                'status' => $getStatusData('submitted_to_handover')['display_name'],
                'status_color' => $getStatusData('submitted_to_handover')['color_class'],
                'analyst' => $this->sample->handoverSubmittedBy->name ?? 'Unknown',
            ];
        }

        if ($this->sample->handover_taken_at) {
            $events[] = [
                'time' => $this->sample->handover_taken_at,
                'status' => $getStatusData('in_progress')['display_name'],
                'status_color' => $getStatusData('in_progress')['color_class'],
                'analyst' => $this->sample->handoverTakenBy->name ?? 'Unknown',
            ];
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
        return view('livewire.components.sample-details', [
            'currentStatusName' => $this->getCurrentStatusName(),
            'statusHistory' => $this->getStatusHistory(),
        ]);
    }
}
