<div>
    @if ($show && $sample)
        <div class="fixed inset-0 bg-gray-900/75 overflow-y-auto h-full w-full z-50" x-data="{ show: true }"
            x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click.self="$wire.close()">

        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-lg rounded-md bg-white"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95">

            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-200">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Sample Details</h3>
                </div>
                <button type="button" wire:click="close"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center gap-4">
                    <span
                        class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $sample->status_color }}">
                        {{ $sample->status_label }}
                    </span>
                    <span class="text-sm text-gray-500">
                        Created {{ $sample->created_at->format('M d, Y \a\t H:i') }}
                    </span>
                </div>

                <!-- Sample Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $sample->category->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Raw Material</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $sample->material->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Supplier</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $sample->supplier }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Batch Lot</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $sample->batch_lot }}</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle/Container Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $sample->vehicle_container_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Submission Time</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $sample->submission_time->format('M d, Y \a\t H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Entry Time</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $sample->entry_time->format('M d, Y \a\t H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Submitted By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $sample->submittedBy->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Certificate of Analysis -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Certificate of Analysis (CoA)</label>
                    @if ($sample->has_coa && $sample->coa_file_path)
                        <div class="mt-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-green-600">CoA file uploaded</span>
                            <a href="{{ asset('storage/' . $sample->coa_file_path) }}" target="_blank"
                                class="text-sm text-blue-600 hover:text-blue-800 underline">
                                View File
                            </a>
                        </div>
                    @else
                        <div class="mt-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-500">No CoA file provided</span>
                        </div>
                    @endif
                </div>

                <!-- Notes -->
                @if ($sample->notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md border border-gray-200">
                            <p class="text-sm text-gray-900">{{ $sample->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Analysis Information Section -->
                @if ($currentStatusName !== 'pending' && $sample->analysis_method)
                    @php
                        // Check if there's an accepted handover
                        $acceptedHandover = $sample->handovers->firstWhere('status', 'accepted');
                    @endphp

                    @if($acceptedHandover)
                        <!-- SECTION 1: Initial Analysis (Before Handover) -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Initial Analysis Information
                                <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Before Handover</span>
                            </label>
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Analysis Method -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Analysis Method</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ ucfirst($sample->analysis_method) }}
                                        </p>
                                    </div>

                                    <!-- Primary Analyst (Initial) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Primary Analyst</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ $acceptedHandover->fromAnalyst->name ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <!-- Secondary Analyst (Initial - for joint) -->
                                    @if ($sample->analysis_method === 'joint' && $sample->secondary_analyst_id)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Secondary Analyst</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ $sample->secondaryAnalyst->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Analysis Started At -->
                                    @if ($sample->analysis_started_at)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Analysis Started</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ $sample->analysis_started_at->format('M d, Y \a\t H:i') }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Handed Over At -->
                                    @if ($acceptedHandover->submitted_at)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Handed Over At</label>
                                            <p class="mt-1 text-sm font-semibold text-orange-600">
                                                {{ $acceptedHandover->submitted_at->format('M d, Y \a\t H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Duration before handover -->
                                @if ($sample->analysis_started_at && $acceptedHandover->submitted_at)
                                    <div class="mt-4 pt-4 border-t border-blue-200">
                                        <label class="block text-sm font-medium text-gray-700">Duration (Until Handover)</label>
                                        @php
                                            $duration = $sample->analysis_started_at->diff($acceptedHandover->submitted_at);
                                            $durationText = '';
                                            if ($duration->days > 0) {
                                                $durationText .= $duration->days . ' day' . ($duration->days > 1 ? 's' : '') . ' ';
                                            }
                                            if ($duration->h > 0) {
                                                $durationText .= $duration->h . ' hour' . ($duration->h > 1 ? 's' : '') . ' ';
                                            }
                                            if ($duration->i > 0) {
                                                $durationText .= $duration->i . ' minute' . ($duration->i > 1 ? 's' : '');
                                            }
                                            $durationText = trim($durationText) ?: 'Less than a minute';
                                        @endphp
                                        <p class="mt-1 text-sm font-semibold text-blue-600">{{ $durationText }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- SECTION 2: Continuation Analysis (After Takeover) -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Continuation Analysis Information
                                <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-700 rounded">After Takeover</span>
                            </label>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Analysis Method (After Takeover) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Analysis Method</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ ucfirst($acceptedHandover->new_analysis_method ?? 'N/A') }}
                                        </p>
                                    </div>

                                    <!-- Primary Analyst (Who took over) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Primary Analyst</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ $acceptedHandover->toAnalyst->name ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <!-- Secondary Analyst (New - for joint) -->
                                    @if ($acceptedHandover->new_analysis_method === 'joint' && $acceptedHandover->new_secondary_analyst_id)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Secondary Analyst</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ $acceptedHandover->newSecondaryAnalyst->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Taken Over At -->
                                    @if ($acceptedHandover->taken_at)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Taken Over At</label>
                                            <p class="mt-1 text-sm font-semibold text-green-600">
                                                {{ $acceptedHandover->taken_at->format('M d, Y \a\t H:i') }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Analysis Completed At -->
                                    @if ($sample->analysis_completed_at)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Analysis Completed</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ $sample->analysis_completed_at->format('M d, Y \a\t H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Duration after takeover -->
                                @if ($acceptedHandover->taken_at && $sample->analysis_completed_at)
                                    <div class="mt-4 pt-4 border-t border-green-200">
                                        <label class="block text-sm font-medium text-gray-700">Duration (After Takeover)</label>
                                        @php
                                            $duration = $acceptedHandover->taken_at->diff($sample->analysis_completed_at);
                                            $durationText = '';
                                            if ($duration->days > 0) {
                                                $durationText .= $duration->days . ' day' . ($duration->days > 1 ? 's' : '') . ' ';
                                            }
                                            if ($duration->h > 0) {
                                                $durationText .= $duration->h . ' hour' . ($duration->h > 1 ? 's' : '') . ' ';
                                            }
                                            if ($duration->i > 0) {
                                                $durationText .= $duration->i . ' minute' . ($duration->i > 1 ? 's' : '');
                                            }
                                            $durationText = trim($durationText) ?: 'Less than a minute';
                                        @endphp
                                        <p class="mt-1 text-sm font-semibold text-green-600">{{ $durationText }}</p>
                                    </div>
                                @elseif ($acceptedHandover->taken_at && !$sample->analysis_completed_at)
                                    <div class="mt-4 pt-4 border-t border-green-200">
                                        <label class="block text-sm font-medium text-gray-700">Analysis In Progress</label>
                                        @php
                                            $elapsed = $acceptedHandover->taken_at->diff(now());
                                            $elapsedText = '';
                                            if ($elapsed->days > 0) {
                                                $elapsedText .= $elapsed->days . ' day' . ($elapsed->days > 1 ? 's' : '') . ' ';
                                            }
                                            if ($elapsed->h > 0) {
                                                $elapsedText .= $elapsed->h . ' hour' . ($elapsed->h > 1 ? 's' : '') . ' ';
                                            }
                                            if ($elapsed->i > 0) {
                                                $elapsedText .= $elapsed->i . ' minute' . ($elapsed->i > 1 ? 's' : '');
                                            }
                                            $elapsedText = trim($elapsedText) ?: 'Just started';
                                        @endphp
                                        <p class="mt-1 text-sm font-semibold text-orange-600">Running for {{ $elapsedText }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    @else
                        <!-- NO HANDOVER - Single Analysis Information (Original) -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Analysis Information</label>
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Analysis Method -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Analysis Method</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ ucfirst($sample->analysis_method) }}
                                        </p>
                                    </div>

                                    <!-- Primary Analyst -->
                                    @if ($sample->primary_analyst_id)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Primary Analyst</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ $sample->primaryAnalyst->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Secondary Analyst (for joint analysis) -->
                                    @if ($sample->analysis_method === 'joint' && $sample->secondary_analyst_id)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Secondary Analyst</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ $sample->secondaryAnalyst->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Analysis Started At -->
                                    @if ($sample->analysis_started_at)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Analysis Started</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ $sample->analysis_started_at->format('M d, Y \a\t H:i') }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Analysis Completed At -->
                                    @if ($sample->analysis_completed_at)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Analysis Completed</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ $sample->analysis_completed_at->format('M d, Y \a\t H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Duration Calculation -->
                                @if ($sample->analysis_started_at && $sample->analysis_completed_at)
                                    <div class="mt-4 pt-4 border-t border-blue-200">
                                        <label class="block text-sm font-medium text-gray-700">Analysis Duration</label>
                                        @php
                                            $duration = $sample->analysis_started_at->diff($sample->analysis_completed_at);
                                            $durationText = '';
                                            if ($duration->days > 0) {
                                                $durationText .= $duration->days . ' day' . ($duration->days > 1 ? 's' : '') . ' ';
                                            }
                                            if ($duration->h > 0) {
                                                $durationText .= $duration->h . ' hour' . ($duration->h > 1 ? 's' : '') . ' ';
                                            }
                                            if ($duration->i > 0) {
                                                $durationText .= $duration->i . ' minute' . ($duration->i > 1 ? 's' : '');
                                            }
                                            $durationText = trim($durationText) ?: 'Less than a minute';
                                        @endphp
                                        <p class="mt-1 text-sm font-semibold text-green-600">{{ $durationText }}</p>
                                    </div>
                                @elseif ($sample->analysis_started_at && !$sample->analysis_completed_at)
                                    <div class="mt-4 pt-4 border-t border-blue-200">
                                        <label class="block text-sm font-medium text-gray-700">Analysis In Progress</label>
                                        @php
                                            $elapsed = $sample->analysis_started_at->diff(now());
                                            $elapsedText = '';
                                            if ($elapsed->days > 0) {
                                                $elapsedText .= $elapsed->days . ' day' . ($elapsed->days > 1 ? 's' : '') . ' ';
                                            }
                                            if ($elapsed->h > 0) {
                                                $elapsedText .= $elapsed->h . ' hour' . ($elapsed->h > 1 ? 's' : '') . ' ';
                                            }
                                            if ($elapsed->i > 0) {
                                                $elapsedText .= $elapsed->i . ' minute' . ($elapsed->i > 1 ? 's' : '');
                                            }
                                            $elapsedText = trim($elapsedText) ?: 'Just started';
                                        @endphp
                                        <p class="mt-1 text-sm font-semibold text-orange-600">Running for {{ $elapsedText }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Analysis Results Section -->
                @if (in_array($currentStatusName, ['analysis_completed', 'reviewed', 'approved', 'rejected']) &&
                        $sample->testResults->count() > 0)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Analysis Results</label>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($sample->testResults->groupBy('parameter_name') as $parameterName => $results)
                                    <div class="bg-white rounded-lg p-4 border border-green-200">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ $parameterName }}</h4>
                                        @php
                                            $averageValue = $results->avg('test_value');
                                            $status = $results->first()->status ?? 'unknown';
                                            $statusColor =
                                                $status === 'pass'
                                                    ? 'text-green-600'
                                                    : ($status === 'fail'
                                                        ? 'text-red-600'
                                                        : 'text-gray-600');
                                            $statusBg =
                                                $status === 'pass'
                                                    ? 'bg-green-100'
                                                    : ($status === 'fail'
                                                        ? 'bg-red-100'
                                                        : 'bg-gray-100');
                                        @endphp

                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-lg font-bold text-gray-900">
                                                {{ rtrim(rtrim(number_format($averageValue, 4, '.', ''), '0'), '.') }}
                                            </span>
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBg }} {{ $statusColor }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            @if ($results->count() > 1)
                                                <div class="mb-1">{{ $results->count() }} readings</div>
                                            @endif
                                            <div>Tested: {{ $results->first()->tested_at->format('M d, Y H:i') }}</div>
                                            <div>By: {{ $results->first()->testedBy->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($sample->notes)
                                <div class="mt-4 pt-4 border-t border-green-200">
                                    <label class="block text-sm font-medium text-gray-700">Analysis Notes</label>
                                    <p class="mt-1 text-sm text-gray-600">{{ $sample->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Status History Section -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Status History</label>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Time In</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Interval</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                                <div class="text-xs text-gray-500">{{ $history['time_in']->format('H:i:s') }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $history['status_color'] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $history['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                @if ($history['previous_time'])
                                                    @php
                                                        $interval = $history['previous_time']->diff($history['time_in']);
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

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button wire:click="close"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg transition-colors duration-200">
                        Close
                    </button>
                    <button wire:click="printSampleLabel"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Print Sample Label</span>
                    </button>
                    @if ($currentStatusName !== 'approved' && $currentStatusName !== 'rejected')
                        <button wire:click="editSample"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            Edit Sample
                        </button>
                    @endif
                </div>
            </div>
        </div>
        </div>
    @endif
</div>
