<div class="min-h-screen bg-gray-50 py-6" x-data="{ showApproveModal: false, showRejectModal: false, show: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Results Review</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Status: <span class="font-semibold">{{ $sample->status_label }}</span>
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button wire:click="backToSamples"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer">
                        ← Back to Samples
                    </button>
                    @if ($canReview)
                        <button wire:click="reviewSample"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            Mark as Reviewed
                        </button>
                    @endif
                    @if ($canApprove)
                        <button @click="showApproveModal = true"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer">
                            Approve Sample
                        </button>
                        <button @click="showRejectModal = true"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer">
                            Reject Sample
                        </button>
                        <button wire:click="openApprovalForm('approve')"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer ">
                            Approve with Notes
                        </button>
                        <button wire:click="openApprovalForm('reject')"
                            class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer">
                            Reject with Notes
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms x-init="setTimeout(() => show = false, 3000)"
                class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Sample Information -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Sample Details Card -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Sample Information</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <!-- Status Badge -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Status</label>
                            <span
                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $sample->status_color }}">
                                {{ $sample->status_label }}
                            </span>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <p class="text-sm text-gray-900">{{ $sample->category->name ?? 'N/A' }}</p>
                        </div>

                        <!-- Raw Material -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Raw Material</label>
                            <p class="text-sm text-gray-900">{{ $sample->material->name ?? 'N/A' }}</p>
                        </div>

                        <!-- Reference -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reference</label>
                            <p class="text-sm text-gray-900">{{ $reference->name ?? 'N/A' }}</p>
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <p class="text-sm text-gray-900">{{ $sample->supplier }}</p>
                        </div>

                        <!-- Batch Lot -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Batch Lot</label>
                            <p class="text-sm text-gray-900">{{ $sample->batch_lot }}</p>
                        </div>

                        <!-- Vehicle/Container Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle/Container Number</label>
                            <p class="text-sm text-gray-900">{{ $sample->vehicle_container_number }}</p>
                        </div>

                        <!-- Submission Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submission Time</label>
                            <p class="text-sm text-gray-900">{{ $sample->submission_time->format('M d, Y \a\t H:i') }}
                            </p>
                        </div>

                        <!-- Submitted By -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitted By</label>
                            <p class="text-sm text-gray-900">{{ $sample->submittedBy->name ?? 'N/A' }}</p>
                        </div>

                        <!-- CoA -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Certificate of Analysis
                                (CoA)</label>
                            @if ($sample->has_coa && $sample->coa_file_path)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ asset('storage/' . $sample->coa_file_path) }}" target="_blank"
                                        class="text-sm text-blue-600 hover:text-blue-800 underline">View CoA File</a>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No CoA provided</p>
                            @endif
                        </div>

                        <!-- Sample Notes -->
                        @if ($sample->review_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sample Notes</label>
                                <div class="p-3 bg-gray-50 rounded-md border border-gray-200">
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $sample->review_notes }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Analysis Information Cards -->
                @if ($sample->analysis_method)
                    <!-- Analysis Information -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Analysis Details</h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <!-- Analysis Method -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Method</label>
                                <p class="text-sm font-semibold text-gray-900">{{ ucfirst($sample->analysis_method) }}
                                </p>
                            </div>

                            <!-- Primary Analyst -->
                            @if ($sample->primary_analyst_id)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary Analyst</label>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $sample->primaryAnalyst->name ?? 'N/A' }}</p>
                                </div>
                            @endif

                            <!-- Secondary Analyst -->
                            @if ($sample->analysis_method === 'joint' && $sample->secondary_analyst_id)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Secondary
                                        Analyst</label>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $sample->secondaryAnalyst->name ?? 'N/A' }}</p>
                                </div>
                            @endif

                            <!-- Analysis Started -->
                            @if ($sample->analysis_started_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Started</label>
                                    <p class="text-sm text-gray-900">
                                        {{ $sample->analysis_started_at->format('M d, Y \a\t H:i') }}</p>
                                </div>
                            @endif

                            <!-- Analysis Completed -->
                            @if ($sample->analysis_completed_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Analysis
                                        Completed</label>
                                    <p class="text-sm text-gray-900">
                                        {{ $sample->analysis_completed_at->format('M d, Y \a\t H:i') }}</p>
                                </div>

                                <!-- Duration -->
                                @if ($sample->analysis_started_at && $sample->analysis_completed_at)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Analysis
                                            Duration</label>
                                        @php
                                            $duration = $sample->analysis_started_at->diff(
                                                $sample->analysis_completed_at,
                                            );
                                            $durationText = '';
                                            if ($duration->days > 0) {
                                                $durationText .=
                                                    $duration->days . ' day' . ($duration->days > 1 ? 's' : '') . ' ';
                                            }
                                            if ($duration->h > 0) {
                                                $durationText .=
                                                    $duration->h . ' hour' . ($duration->h > 1 ? 's' : '') . ' ';
                                            }
                                            if ($duration->i > 0) {
                                                $durationText .=
                                                    $duration->i . ' minute' . ($duration->i > 1 ? 's' : '');
                                            }
                                            $durationText = trim($durationText) ?: 'Less than a minute';
                                        @endphp
                                        <p class="text-sm font-medium text-green-600">{{ $durationText }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Analysis Results & Status History -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Analysis Results Card -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Analysis Results</h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Reference:</span>
                                <span class="text-sm font-medium text-blue-600">{{ $reference->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        @if (!empty($analysisResults))
                            <!-- Results Summary -->
                            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                @php
                                    $totalTests = collect($analysisResults)->sum(
                                        fn($r) => count($r['test_results'] ?? []),
                                    );
                                    $passedTests = count(
                                        array_filter($analysisResults, fn($r) => $r['status'] === 'pass'),
                                    );
                                    $failedTests = count(
                                        array_filter($analysisResults, fn($r) => $r['status'] === 'fail'),
                                    );
                                @endphp

                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $totalTests }}</div>
                                    <div class="text-sm text-blue-800">Test Readings Completed</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $passedTests }}</div>
                                    <div class="text-sm text-green-800">Parameters Passed</div>
                                </div>
                                <div class="text-center p-4 bg-red-50 rounded-lg">
                                    <div class="text-2xl font-bold text-red-600">{{ $failedTests }}</div>
                                    <div class="text-sm text-red-800">Parameters Failed</div>
                                </div>
                            </div>

                            <!-- Detailed Results -->
                            <div class="space-y-6">
                                @foreach ($analysisResults as $parameter => $data)
                                    <div
                                        class="border border-gray-200 rounded-lg overflow-hidden
                                        {{ $data['status'] === 'pass'
                                            ? 'border-green-200'
                                            : ($data['status'] === 'fail'
                                                ? 'border-red-200'
                                                : 'border-gray-200') }}">

                                        <!-- Parameter Header -->
                                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-900">
                                                        {{ $data['spec_name'] }}</h4>
                                                    <p class="text-sm text-gray-600">Specification:
                                                        {{ $data['spec'] }}</p>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    @if ($data['status'] === 'pass')
                                                        <span
                                                            class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Pass
                                                        </span>
                                                    @elseif ($data['status'] === 'fail')
                                                        <span
                                                            class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Fail
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                                            Not Tested
                                                        </span>
                                                    @endif

                                                @if ($canEdit && !empty($data['test_results']))
                                                        <button wire:click="openEditForm('{{ $parameter }}')"
                                                            class="inline-flex items-center px-3 py-1 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer">
                                                            <svg class="w-4 h-4 mr-1" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            Edit
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Test Results -->
                                        <div class="px-4 py-4">
                                            @if (!empty($data['test_results']))
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    @foreach ($data['test_results'] as $result)
                                                        <div
                                                            class="p-4 border rounded-lg
                                                            {{ $result['passes'] ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                                                            <div class="flex items-center justify-between mb-2">
                                                                <span class="text-xs font-medium text-gray-500">Reading
                                                                    #{{ $result['reading_number'] }}</span>
                                                                <span
                                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                                    {{ $result['passes'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                    {{ $result['passes'] ? 'Pass' : 'Fail' }}
                                                                </span>
                                                            </div>
                                                            <div class="flex items-baseline space-x-2 mb-2">
                                                                <span class="text-2xl font-bold text-gray-900">
                                                                    @if (is_numeric($result['value']))
                                                                        {{ rtrim(rtrim(number_format($result['value'], 4, '.', ''), '0'), '.') }}
                                                                    @else
                                                                        {{ $result['value'] }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="text-xs text-gray-500 space-y-1">
                                                                <div>Tested:
                                                                    {{ $result['tested_at']->format('M d, Y H:i') }}
                                                                </div>
                                                                <div>By: {{ $result['tested_by'] }}</div>
                                                                @if ($result['notes'])
                                                                    <div class="mt-2 p-2 bg-white rounded border">
                                                                        <span class="font-medium">Notes:</span>
                                                                        {{ $result['notes'] }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <!-- Summary for this parameter -->
                                                <div class="mt-4 pt-3 border-t border-gray-200">
                                                    <div class="text-sm text-gray-600">
                                                        <strong>{{ count($data['test_results']) }}</strong> readings
                                                        completed
                                                        @php
                                                            $passedReadings = collect($data['test_results'])
                                                                ->where('passes', true)
                                                                ->count();
                                                        @endphp
                                                        • <span class="text-green-600">{{ $passedReadings }}
                                                            passed</span>
                                                        • <span
                                                            class="text-red-600">{{ count($data['test_results']) - $passedReadings }}
                                                            failed</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center py-8">
                                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                        </path>
                                                    </svg>
                                                    <p class="mt-2 text-sm text-gray-500">No test results available for
                                                        this parameter</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No Analysis Results</h3>
                                <p class="mt-1 text-sm text-gray-500">No analysis results found for this sample.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status History Card -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status History</h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Time In</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Interval</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Analyst</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($statusHistory as $index => $history)
                                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $history['id'] }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <div>{{ $history['time_in']->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $history['time_in']->format('H:i:s') }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    @if ($history['status'] == 'Pending') bg-gray-100 text-gray-800
                                                    @elseif($history['status'] == 'In Progress') bg-blue-100 text-blue-800
                                                    @elseif($history['status'] == 'Analysis Completed') bg-amber-100 text-amber-800
                                                    @elseif($history['status'] == 'Review') bg-purple-100 text-purple-800
                                                    @elseif($history['status'] == 'Reviewed') bg-purple-100 text-purple-800
                                                    @elseif($history['status'] == 'Approved') bg-green-100 text-green-800
                                                    @elseif($history['status'] == 'Rejected') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $history['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                @if ($history['previous_time'])
                                                    @php
                                                        $interval = $history['previous_time']->diff(
                                                            $history['time_in'],
                                                        );
                                                        $intervalText = '';
                                                        if ($interval->d > 0) {
                                                            $intervalText .= $interval->d . 'd ';
                                                        }
                                                        if ($interval->h > 0) {
                                                            $intervalText .= $interval->h . 'h ';
                                                        }
                                                        if ($interval->i > 0) {
                                                            $intervalText .= $interval->i . 'm';
                                                        }
                                                        if (empty($intervalText)) {
                                                            $intervalText = '< 1m';
                                                        }
                                                    @endphp
                                                    {{ trim($intervalText) }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $history['analyst'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval/Rejection Modal -->
    @if ($showApprovalForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
            x-data="{ show: true }" x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click.self="$wire.closeApprovalForm()">

            <div class="relative top-4 mx-auto my-4 w-11/12 md:w-2/3 lg:w-1/2 max-w-2xl shadow-2xl rounded-2xl bg-white overflow-hidden"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Modal Header -->
                <div
                    class="bg-gradient-to-r {{ $approvalAction === 'approve' ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600' }} px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            @if ($approvalAction === 'approve')
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">
                                {{ $approvalAction === 'approve' ? 'Approve Sample' : 'Reject Sample' }}
                            </h3>
                            <p class="text-white/90 text-sm">Sample #{{ $sample->id }}</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeApprovalForm"
                        class="text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm p-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="px-6 py-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            {{ $approvalAction === 'approve' ? 'Approval' : 'Rejection' }} Notes
                        </label>
                        <textarea wire:model.defer="reviewNotes" rows="4"
                            placeholder="Enter your {{ $approvalAction === 'approve' ? 'approval' : 'rejection' }} notes..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </textarea>
                        @error('reviewNotes')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" wire:click="closeApprovalForm"
                        class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="submitReview" wire:loading.attr="disabled"
                        class="px-6 py-2.5 {{ $approvalAction === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span
                            wire:loading.remove>{{ $approvalAction === 'approve' ? 'Approve Sample' : 'Reject Sample' }}</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif


    <!-- Approve Confirmation Modal -->
    <div x-show="showApproveModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">

        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" @click="showApproveModal = false" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal container - centered -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Modal panel -->
            <div
                class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all max-w-lg w-full mx-auto">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Approve Sample
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to approve this sample? This action will finalize the analysis
                                    results and mark the sample as approved.
                                </p>
                                <p class="text-sm text-red-600 mt-2 font-medium">
                                    ⚠️ This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="approveSample" @click="showApproveModal = false" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove>Approve Sample</span>
                        <span wire:loading>Approving...</span>
                    </button>
                    <button @click="showApproveModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div x-show="showRejectModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">

        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" @click="showRejectModal = false" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal container - centered -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Modal panel -->
            <div
                class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all max-w-lg w-full mx-auto">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Reject Sample
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to reject this sample? This will mark the sample as rejected
                                    and the analysis will not be approved.
                                </p>
                                <p class="text-sm text-red-600 mt-2 font-medium">
                                    ⚠️ This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="rejectSample" @click="showRejectModal = false" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove>Reject Sample</span>
                        <span wire:loading>Rejecting...</span>
                    </button>
                    <button @click="showRejectModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Test Results Modal -->
    @if ($showEditForm)
        <div class="fixed inset-0 overflow-y-auto z-50" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"
                    wire:click="closeEditForm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    Edit Test Results - {{ ucwords(str_replace('_', ' ', $editingParameter)) }}
                                </h3>

                                <div class="space-y-4">
                                    @foreach ($editingTestResults as $index => $result)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <label class="text-sm font-medium text-gray-700">
                                                    Reading #{{ $result['reading_number'] }}
                                                </label>
                                                <span class="text-xs text-gray-500">
                                                    Tested:
                                                    {{ \Carbon\Carbon::parse($result['tested_at'])->format('M d, Y H:i') }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                                    @php
                                                        $specData = $analysisResults[$editingParameter] ?? [];
                                                        $operator = $specData['operator'] ?? null;
                                                    @endphp

                                                    @if ($operator === 'should_be')
                                                        <input type="text"
                                                            wire:model="editingTestResults.{{ $index }}.value"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    @else
                                                        <input type="number" step="0.0001"
                                                            wire:model="editingTestResults.{{ $index }}.value"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    @endif
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes
                                                        (Optional)</label>
                                                    <input type="text"
                                                        wire:model="editingTestResults.{{ $index }}.notes"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                </div>
                                            </div>

                                            <div class="mt-2 text-xs text-gray-500">
                                                Tested by: {{ $result['tested_by'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveEditedResults" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer disabled:opacity-50">
                            <span wire:loading.remove>Save Changes</span>
                            <span wire:loading>Saving...</span>
                        </button>
                        <button wire:click="closeEditForm"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
