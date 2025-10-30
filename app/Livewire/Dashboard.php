<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RawMaterialSample;
use App\Models\RawMatCategory;
use App\Models\RawMat;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $submissionsInProgress = 0;
    public $totalCategories = 0;
    public $pendingTests = 0;
    public $completedTests = 0;
    public $recentActivities = [];

    // Chart data
    public $monthlySubmissions = [];
    public $statusDistribution = [];

    public function mount()
    {
        $this->loadStatistics();
        $this->loadRecentActivities();
        $this->loadChartData();
    }

    public function loadStatistics()
    {
        // Get status IDs
        $inProgressStatus = Status::where('name', 'in_progress')->first();
        $inProgressRestartStatus = Status::where('name', 'in_progress_restart')->first();
        $pendingStatus = Status::where('name', 'pending')->first();
        $completedStatus = Status::where('name', 'approved')->first();

        // Count submissions in progress (including restart)
        $this->submissionsInProgress = RawMaterialSample::whereIn('status_id', [
            $inProgressStatus?->id,
            $inProgressRestartStatus?->id
        ])->count();

        // Count total categories
        $this->totalCategories = RawMatCategory::count();

        // Count pending tests
        $this->pendingTests = RawMaterialSample::where('status_id', $pendingStatus?->id)->count();

        // Count completed tests (approved)
        $this->completedTests = RawMaterialSample::where('status_id', $completedStatus?->id)->count();
    }

    public function loadRecentActivities()
    {
        $activities = [];

        // Get recent sample submissions (last 5)
        $recentSamples = RawMaterialSample::with(['rawMaterial', 'submittedBy', 'statusRelation'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentSamples as $sample) {
            $activities[] = [
                'type' => 'sample_submission',
                'icon' => 'clipboard-list',
                'color' => 'blue',
                'title' => 'New sample submitted',
                'description' => ($sample->rawMaterial?->name ?? 'Unknown') . ' • ' . ($sample->batch_lot ?? 'N/A'),
                'time' => $sample->created_at,
                'time_human' => $sample->created_at->diffForHumans(),
            ];
        }

        // Get recent completed samples (last 3)
        $completedStatus = Status::where('name', 'approved')->first();
        $recentCompleted = RawMaterialSample::with(['rawMaterial', 'statusRelation'])
            ->where('status_id', $completedStatus?->id)
            ->orderBy('approved_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentCompleted as $sample) {
            $activities[] = [
                'type' => 'sample_completed',
                'icon' => 'check-circle',
                'color' => 'green',
                'title' => 'Sample analysis completed',
                'description' => ($sample->rawMaterial?->name ?? 'Unknown') . ' • Batch ' . ($sample->batch_lot ?? 'N/A'),
                'time' => $sample->approved_at ?? $sample->updated_at,
                'time_human' => ($sample->approved_at ?? $sample->updated_at)->diffForHumans(),
            ];
        }

        // Get recently added raw materials (last 2)
        $recentRawMats = RawMat::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        foreach ($recentRawMats as $rawMat) {
            $activities[] = [
                'type' => 'rawmat_added',
                'icon' => 'plus',
                'color' => 'blue',
                'title' => 'New raw material added',
                'description' => $rawMat->name . ' • ' . ($rawMat->category?->name ?? 'Uncategorized'),
                'time' => $rawMat->created_at,
                'time_human' => $rawMat->created_at->diffForHumans(),
            ];
        }

        // Sort all activities by time (most recent first)
        usort($activities, function ($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        // Take only the 4 most recent
        $this->recentActivities = array_slice($activities, 0, 4);
    }

    public function loadChartData()
    {
        // Monthly submissions for the last 6 months
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = RawMaterialSample::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        $this->monthlySubmissions = $monthlyData;

        // Status distribution
        $statuses = Status::withCount('rawMaterialSamples as samples_count')->get();
        $statusData = [];
        foreach ($statuses as $status) {
            if ($status->samples_count > 0) {
                $statusData[] = [
                    'label' => $status->display_name ?? ucfirst($status->name),
                    'count' => $status->samples_count,
                    'color' => $this->getStatusColor($status->name)
                ];
            }
        }
        $this->statusDistribution = $statusData;
    }

    private function getStatusColor($statusName)
    {
        $colors = [
            'pending' => '#fbbf24', // amber
            'in_progress' => '#3b82f6', // blue
            'in_progress_restart' => '#8b5cf6', // purple
            'analysis_completed' => '#10b981', // green
            'reviewed' => '#06b6d4', // cyan
            'approved' => '#22c55e', // green
            'submitted_to_handover' => '#f59e0b', // orange
            'restart_analysis' => '#ef4444', // red
        ];

        return $colors[$statusName] ?? '#6b7280'; // gray default
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app')
            ->title('Dashboard');
    }
}
