<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sample;
use App\Models\Category;
use App\Models\Material;
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
    public $approvalData = []; // Data per sample type (solder, rawmat, chemical)
    public $chartPeriod = 'month'; // week, month, year

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
        $pendingStatus = Status::where('name', 'pending')->first();
        $completedStatus = Status::where('name', 'approved')->first();

        // Count submissions in progress
        $this->submissionsInProgress = Sample::where('status_id', $inProgressStatus?->id)->count();

        // Count total categories
        $this->totalCategories = Category::count();

        // Count pending tests
        $this->pendingTests = Sample::where('status_id', $pendingStatus?->id)->count();

        // Count completed tests (approved)
        $this->completedTests = Sample::where('status_id', $completedStatus?->id)->count();
    }

    public function loadRecentActivities()
    {
        $activities = [];

        // Get recent sample submissions (last 5)
        $recentSamples = Sample::with(['material', 'submittedBy', 'status'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentSamples as $sample) {
            $activities[] = [
                'type' => 'sample_submission',
                'icon' => 'clipboard-list',
                'color' => 'blue',
                'title' => 'New sample submitted',
                'description' => ($sample->material?->name ?? 'Unknown') . ' • ' . ($sample->batch_lot ?? 'N/A'),
                'time' => $sample->created_at,
                'time_human' => $sample->created_at->diffForHumans(),
            ];
        }

        // Get recent completed samples (last 3)
        $completedStatus = Status::where('name', 'approved')->first();
        $recentCompleted = Sample::with(['material', 'status'])
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
                'description' => ($sample->material?->name ?? 'Unknown') . ' • Batch ' . ($sample->batch_lot ?? 'N/A'),
                'time' => $sample->approved_at ?? $sample->updated_at,
                'time_human' => ($sample->approved_at ?? $sample->updated_at)->diffForHumans(),
            ];
        }

        // Get recently added materials (last 2)
        $recentMaterials = Material::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        foreach ($recentMaterials as $material) {
            $activities[] = [
                'type' => 'material_added',
                'icon' => 'plus',
                'color' => 'blue',
                'title' => 'New material added',
                'description' => $material->name . ' • ' . ($material->category?->name ?? 'Uncategorized'),
                'time' => $material->created_at,
                'time_human' => $material->created_at->diffForHumans(),
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
        $submissionsData = [];
        $now = Carbon::now();

        switch ($this->chartPeriod) {
            case 'week':
                // LOGIKA: Membagi bulan ini menjadi 4 minggu (Week 1, 2, 3, 4)
                $startOfMonth = $now->copy()->startOfMonth();

                for ($i = 1; $i <= 4; $i++) {
                    $weekLabel = 'Week ' . $i;

                    // Tentukan start date untuk minggu ini
                    if ($i == 1) {
                        $weekStart = $startOfMonth->copy();
                        $weekEnd = $startOfMonth->copy()->addDays(6)->endOfDay(); // Hari 1-7
                    } elseif ($i == 2) {
                        $weekStart = $startOfMonth->copy()->addDays(7);
                        $weekEnd = $startOfMonth->copy()->addDays(13)->endOfDay(); // Hari 8-14
                    } elseif ($i == 3) {
                        $weekStart = $startOfMonth->copy()->addDays(14);
                        $weekEnd = $startOfMonth->copy()->addDays(20)->endOfDay(); // Hari 15-21
                    } else {
                        // Week 4 mengambil sisa hari sampai akhir bulan
                        $weekStart = $startOfMonth->copy()->addDays(21);
                        $weekEnd = $now->copy()->endOfMonth(); // Hari 22-End
                    }

                    $count = Sample::whereBetween('created_at', [$weekStart, $weekEnd])->count();

                    $submissionsData[] = [
                        'month' => $weekLabel,
                        'count' => $count
                    ];
                }
                break;

            case 'month':
                // LOGIKA: Menampilkan Januari s/d Desember untuk TAHUN INI
                $currentYear = $now->year;

                for ($m = 1; $m <= 12; $m++) {
                    // Membuat tanggal awal dan akhir bulan untuk bulan $m di tahun ini
                    $date = Carbon::createFromDate($currentYear, $m, 1);
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth = $date->copy()->endOfMonth();

                    $count = Sample::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

                    $submissionsData[] = [
                        'month' => $date->format('F'), // Akan menghasilkan "January", "February", dst
                        'count' => $count
                    ];
                }
                break;

            case 'year':
            default:
                // LOGIKA: 5 Tahun Terakhir (termasuk tahun ini)
                for ($i = 4; $i >= 0; $i--) {
                    $year = $now->copy()->subYears($i)->year;
                    $startOfYear = Carbon::create($year, 1, 1)->startOfDay();
                    $endOfYear = Carbon::create($year, 12, 31)->endOfDay();

                    $count = Sample::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

                    $submissionsData[] = [
                        'month' => (string) $year, // Convert ke string agar chart.js membacanya sebagai label
                        'count' => $count
                    ];
                }
                break;
        }

        $this->monthlySubmissions = $submissionsData;

        // Load approval data per sample type (Solder, Rawmat, Chemical)
        $this->loadApprovalData();
    }

    public function loadApprovalData()
    {
        // Get approved status
        $approvedStatus = Status::where('name', 'approved')->first();

        if (!$approvedStatus) {
            $this->approvalData = [];
            return;
        }

        // Get data for last 12 months by sample type
        $labels = [];
        $solderData = [];
        $rawmatData = [];
        $chemicalData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $labels[] = $date->format('M');

            // Count approved samples by type for this month
            $solderCount = Sample::where('sample_type', 'solder')
                ->where('status_id', $approvedStatus->id)
                ->whereBetween('approved_at', [$startOfMonth, $endOfMonth])
                ->count();

            $rawmatCount = Sample::where('sample_type', 'raw_material')
                ->where('status_id', $approvedStatus->id)
                ->whereBetween('approved_at', [$startOfMonth, $endOfMonth])
                ->count();

            $chemicalCount = Sample::where('sample_type', 'chemical')
                ->where('status_id', $approvedStatus->id)
                ->whereBetween('approved_at', [$startOfMonth, $endOfMonth])
                ->count();

            $solderData[] = $solderCount;
            $rawmatData[] = $rawmatCount;
            $chemicalData[] = $chemicalCount;
        }

        $this->approvalData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Data Solder',
                    'data' => $solderData,
                    'backgroundColor' => '#22c55e', // green
                ],
                [
                    'label' => 'Data Rawmat',
                    'data' => $rawmatData,
                    'backgroundColor' => '#3b82f6', // blue
                ],
                [
                    'label' => 'Data Chemical',
                    'data' => $chemicalData,
                    'backgroundColor' => '#f97316', // orange
                ],
            ]
        ];
    }

    public function updateChartPeriod($period)
    {
        $this->chartPeriod = $period;
        $this->loadChartData();

        // Dispatch event to update chart in JavaScript
        $this->dispatch('chartDataUpdated', [
            'monthlySubmissions' => $this->monthlySubmissions,
            'approvalData' => $this->approvalData
        ]);
    }

    private function getStatusColor($statusName)
    {
        $colors = [
            'pending' => '#fbbf24', // amber
            'in_progress' => '#3b82f6', // blue
            'analysis_completed' => '#10b981', // green
            'reviewed' => '#06b6d4', // cyan
            'approved' => '#22c55e', // green
            'hand_over' => '#f97316', // orange
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
