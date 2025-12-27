<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sample;
use App\Models\Category;
use App\Models\Material;
use App\Models\Status;
use App\Models\User;
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

    // Operator data
    public $selectedMonth;
    public $selectedYear;
    public $solderOperators = [];
    public $chemicalOperators = [];

    // Modal data
    public $showModal = false;
    public $selectedOperator = null;
    public $operatorSamples = [];
    public $modalSampleType = '';

    public function mount()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->loadStatistics();
        $this->loadRecentActivities();
        $this->loadChartData();
        $this->loadOperatorData();
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

    public function loadOperatorData()
    {
        // Get only operators
        $analysts = User::whereHas('role', function($q) {
            $q->where('name', 'operator');
        })->get();

        $this->solderOperators = [];
        $this->chemicalOperators = [];

        foreach ($analysts as $analyst) {
            // Calculate solder data
            $solderData = $this->calculateOperatorStats($analyst->id, 'solder');
            if ($solderData['jumlah_sample'] > 0 || true) { // Show all operators
                $this->solderOperators[] = array_merge(['operator' => $analyst], $solderData);
            }

            // Calculate chemical data
            $chemicalData = $this->calculateOperatorStats($analyst->id, 'chemical');
            if ($chemicalData['jumlah_sample'] > 0 || true) { // Show all operators
                $this->chemicalOperators[] = array_merge(['operator' => $analyst], $chemicalData);
            }
        }
    }

    private function calculateOperatorStats($operatorId, $sampleType)
    {
        // Build query for samples
        $query = Sample::where('sample_type', $sampleType)
            ->where(function($q) use ($operatorId) {
                $q->where('primary_analyst_id', $operatorId)
                  ->orWhere('secondary_analyst_id', $operatorId);
            })
            ->whereNotNull('analysis_started_at');

        // Filter by month if selected
        if ($this->selectedMonth) {
            $startOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
            $endOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth();
            $query->whereBetween('analysis_started_at', [$startOfMonth, $endOfMonth]);
        } else {
            // If no month selected, filter by year only
            $query->whereYear('analysis_started_at', $this->selectedYear);
        }

        $samples = $query->with('handovers')->get();

        $jumlahSample = $samples->count();

        // Calculate total waktu based on actual work periods
        $totalSeconds = 0;

        foreach ($samples as $sample) {
            $operatorWorkTime = $this->calculateOperatorWorkTime($sample, $operatorId);
            $totalSeconds += $operatorWorkTime;
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $totalWaktu = "{$hours} Jam {$minutes} Menit";

        return [
            'jumlah_sample' => $jumlahSample,
            'total_waktu' => $totalWaktu,
            'total_seconds' => $totalSeconds
        ];
    }

    private function calculateOperatorWorkTime($sample, $operatorId)
    {
        $workTime = 0;

        // Get accepted handovers for this sample, ordered by taken_at
        $handovers = $sample->handovers()
            ->where('status', 'accepted')
            ->orderBy('taken_at', 'asc')
            ->get();

        // Case 1: Operator started the analysis (and was NOT handed over TO)
        // Check if there's any handover TO this operator - if yes, they didn't start it
        $wasHandedOverTo = $handovers->where('to_analyst_id', $operatorId)->first();

        if ($sample->analysis_started_at && !$wasHandedOverTo) {
            // This operator truly started the analysis (no one handed over to them)
            // Check if there's a handover FROM this operator
            $handoverOut = $handovers->where('from_analyst_id', $operatorId)->first();

            if ($handoverOut && $handoverOut->submitted_at) {
                // Operator worked from start until handover
                $workTime += $sample->analysis_started_at->diffInSeconds($handoverOut->submitted_at);
            } elseif ($sample->analysis_completed_at) {
                // No handover, operator completed the analysis
                $workTime += $sample->analysis_started_at->diffInSeconds($sample->analysis_completed_at);
            }
        }

        // Case 2: Operator received handover(s)
        $handoversToOperator = $handovers->where('to_analyst_id', $operatorId);

        foreach ($handoversToOperator as $handoverIn) {
            if (!$handoverIn->taken_at) continue;

            // Check if operator handed over again
            $handoverOut = $handovers
                ->where('from_analyst_id', $operatorId)
                ->where('submitted_at', '>', $handoverIn->taken_at)
                ->first();

            if ($handoverOut && $handoverOut->submitted_at) {
                // Operator worked from taken_at until next handover
                $workTime += $handoverIn->taken_at->diffInSeconds($handoverOut->submitted_at);
            } elseif ($sample->analysis_completed_at && $sample->analysis_completed_at > $handoverIn->taken_at) {
                // Operator completed the analysis after taking over
                $workTime += $handoverIn->taken_at->diffInSeconds($sample->analysis_completed_at);
            }
        }

        return abs($workTime);
    }

    public function updatedSelectedMonth()
    {
        $this->loadOperatorData();
    }

    public function openDetailModal($operatorId, $sampleType)
    {
        $this->selectedOperator = User::find($operatorId);
        $this->modalSampleType = $sampleType;

        // Build query for samples - show ALL samples worked by operator (including approved)
        $query = Sample::where('sample_type', $sampleType)
            ->where(function($q) use ($operatorId) {
                $q->where('primary_analyst_id', $operatorId)
                  ->orWhere('secondary_analyst_id', $operatorId);
            })
            ->whereNotNull('analysis_started_at') // Only samples that have started analysis
            ->with(['material', 'handovers']);

        // Filter by month if selected - consistent with calculateOperatorStats
        if ($this->selectedMonth) {
            $startOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
            $endOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth();
            $query->whereBetween('analysis_started_at', [$startOfMonth, $endOfMonth]);
        } else {
            // If no month selected, filter by year only
            $query->whereYear('analysis_started_at', $this->selectedYear);
        }

        $this->operatorSamples = $query->orderBy('analysis_started_at', 'desc')->get();

        // Calculate actual work time for each sample for this specific operator
        foreach ($this->operatorSamples as $sample) {
            $sample->operator_work_time = $this->calculateOperatorWorkTime($sample, $operatorId);
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedOperator = null;
        $this->operatorSamples = [];
        $this->modalSampleType = '';
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app')
            ->title('Dashboard');
    }
}
