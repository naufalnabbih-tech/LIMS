<div class="min-h-screen bg-gray-50 py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Laboratory Analysis</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Sample ID: <span class="font-semibold">#{{ $sample->id }}</span> |
                        Reference: <span class="font-semibold">{{ $reference->name ?? 'N/A' }}</span>
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Auto-save Indicator -->
                    <div class="flex items-center space-x-2 text-sm">
                        @if($isSaving)
                            <div class="flex items-center text-blue-600">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Saving...</span>
                            </div>
                        @elseif($lastSavedAt)
                            <div class="flex items-center text-green-600">
                                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Saved at {{ $lastSavedAt }}</span>
                            </div>
                        @endif
                    </div>

                    <button wire:click="backToSamples"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer">
                        ← Back to Samples
                    </button>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
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

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 10.586l1.293-1.293a1 1 0 001.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Sample Information -->
            <div class="lg:col-span-1">
                <!-- Sample Details Card -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Sample Information</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <!-- Status Badge -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
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
                    </div>
                </div>

                <!-- Analysis Information Card -->
                @if ($sample->analysis_method)
                    <div class="mt-6 bg-white shadow-sm rounded-lg border border-gray-200">
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
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Analysis Parameters -->
            <div class="lg:col-span-2">
                <!-- Analysis Parameters Card -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Analysis Parameters</h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Reference:</span>
                                <span class="text-sm font-medium text-blue-600">{{ $reference->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        @if (!empty($analysisResults))
                            <div class="space-y-6">
                                @foreach ($analysisResults as $parameter => $data)
                                    <div class="p-4 bg-gray-50 rounded-lg space-y-4">
                                        <!-- Parameter Header -->
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-900">
                                                    {{ $data['spec_name'] ?? str_replace('_', ' ', $parameter) }}
                                                </label>
                                                <p class="text-xs text-gray-500 mt-1">Spec: {{ $data['spec'] }}</p>
                                            </div>
                                            <div class="text-right">
                                                @if ($data['average_value'])
                                                    @php
                                                        // Check if result meets specification using average value
                                                        $passes = true;
                                                        $statusColor = 'bg-green-100 text-green-800';
                                                        $statusText = 'Pass';

                                                        if (
                                                            isset($data['target_value']) &&
                                                            isset($data['operator']) &&
                                                            $data['target_value'] !== null
                                                        ) {
                                                            $operator = $data['operator'];

                                                            // For "should_be" operator, compare as text
                                                            if ($operator === 'should_be') {
                                                                $testValue = trim($data['average_value']);
                                                                $targetValue = trim($data['target_value']);
                                                                $passes = strcasecmp($testValue, $targetValue) === 0;
                                                            } else {
                                                                // For numeric operators, compare as numbers
                                                                $testValue = floatval($data['average_value']);
                                                                $targetValue = floatval($data['target_value']);

                                                                switch ($operator) {
                                                                    case '>=':
                                                                        $passes = $testValue >= $targetValue;
                                                                        break;
                                                                    case '>':
                                                                        $passes = $testValue > $targetValue;
                                                                        break;
                                                                    case '<=':
                                                                        $passes = $testValue <= $targetValue;
                                                                        break;
                                                                    case '<':
                                                                        $passes = $testValue < $targetValue;
                                                                        break;
                                                                    case '=':
                                                                    case '==':
                                                                        $passes = abs($testValue - $targetValue) < 0.001;
                                                                        break;
                                                                    case '-':
                                                                        // Range operator
                                                                        $maxValue = isset($data['max_value']) ? floatval($data['max_value']) : null;
                                                                        if ($maxValue !== null) {
                                                                            $passes = $testValue >= $targetValue && $testValue <= $maxValue;
                                                                        }
                                                                        break;
                                                                }
                                                            }

                                                            if (!$passes) {
                                                                $statusColor = 'bg-red-100 text-red-800';
                                                                $statusText = 'Fail';
                                                            }
                                                        } else {
                                                            $statusColor = 'bg-blue-100 text-blue-800';
                                                            $statusText = 'Tested';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                                        {{ $statusText }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                                        Pending
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Reading Inputs -->
                                        @if (!$isCompleted)
                                            <div class="space-y-3">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    @foreach ($activeReadings[$parameter] ?? ['initial'] as $readingType)
                                                        <div class="space-y-1">
                                                            <div class="flex items-center justify-between">
                                                                <label
                                                                    class="block text-xs font-medium text-gray-700 capitalize">
                                                                    {{ $readingType }} Analysis
                                                                </label>
                                                                @if ($readingType !== 'initial')
                                                                    <button
                                                                        wire:click="removeReading('{{ $parameter }}', '{{ $readingType }}')"
                                                                        class="text-red-600 hover:text-red-800 text-xs">
                                                                        <svg class="w-4 h-4" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                clip-rule="evenodd"></path>
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                @if($data['operator'] === 'should_be')
                                                                    <input type="text"
                                                                        wire:model.blur="analysisResults.{{ $parameter }}.readings.{{ $readingType }}.value"
                                                                        placeholder="Enter value"
                                                                        class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                @else
                                                                    <input type="number" step="0.01"
                                                                        wire:model.blur="analysisResults.{{ $parameter }}.readings.{{ $readingType }}.value"
                                                                        placeholder="0.00"
                                                                        class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                @endif
                                                                @if(!empty($data['unit']))
                                                                    <span class="inline-flex items-center px-2.5 py-1.5 text-sm font-medium text-amber-800 bg-amber-50 border border-amber-200 rounded-md whitespace-nowrap">
                                                                        {{ $data['unit'] }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    @if (method_exists($this, 'canAddReading') && $this->canAddReading($parameter))
                                                        <div class="flex items-center justify-center">
                                                            <button wire:click="addReading('{{ $parameter }}')"
                                                                class="flex items-center justify-center w-10 h-10 border-2 border-dashed border-gray-300 hover:border-blue-500 rounded-lg text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                                                <svg class="w-5 h-5" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <!-- Display completed readings -->
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                @foreach ($activeReadings[$parameter] ?? ['initial'] as $readingType)
                                                    @if (!empty($data['readings'][$readingType]['value']))
                                                        <div class="space-y-1">
                                                            <label
                                                                class="block text-xs font-medium text-gray-700 capitalize">
                                                                {{ $readingType }} Analysis
                                                            </label>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="text-sm text-gray-900">
                                                                    {{ $data['readings'][$readingType]['value'] }}
                                                                </span>
                                                                @if(!empty($data['unit']))
                                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-amber-800 bg-amber-50 border border-amber-200 rounded">
                                                                        {{ $data['unit'] }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Average/Summary -->
                                        @if ($data['average_value'])
                                            <div class="pt-2 border-t border-gray-200">
                                                @if($data['operator'] === 'should_be')
                                                    <!-- For should_be operator, only show result -->
                                                    <div class="text-xs">
                                                        <span class="font-medium text-gray-700">Result:</span>
                                                        <span class="text-gray-900 ml-1">{{ $data['final_value'] }}</span>
                                                        @if(!empty($data['unit']))
                                                            <span class="text-amber-700 ml-1">{{ $data['unit'] }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <!-- For numeric operators, show average and final -->
                                                    <div class="grid grid-cols-2 gap-4 text-xs">
                                                        <div>
                                                            <span class="font-medium text-gray-700">Average:</span>
                                                            <span class="text-gray-900 ml-1">{{ $data['average_value'] }}</span>
                                                            @if(!empty($data['unit']))
                                                                <span class="text-amber-700 ml-1">{{ $data['unit'] }}</span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <span class="font-medium text-gray-700">Final:</span>
                                                            <span class="text-gray-900 ml-1">{{ $data['final_value'] }}</span>
                                                            @if(!empty($data['unit']))
                                                                <span class="text-amber-700 ml-1">{{ $data['unit'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if (!$isCompleted)
                                <!-- Notes Section -->
                                <div class="mt-8 pt-6 border-t border-gray-200">
                                    <label class="block text-sm font-medium text-gray-900 mb-2">Analysis Notes</label>
                                    <textarea wire:model="notes" rows="4"
                                        placeholder="Enter any observations, comments, or additional notes about the analysis..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>

                                <!-- Save Button -->
                                <div class="mt-6 flex justify-end" x-data="{
                                    showConfirmModal: false,
                                    init() {
                                        this.$wire.on('save-success', (message) => {
                                            this.showConfirmModal = false;
                                            // Scroll to flash message area after modal closes
                                            setTimeout(() => {
                                                window.scrollTo({
                                                    top: 0,
                                                    behavior: 'smooth'
                                                });
                                            }, 300);
                                        });
                                        this.$wire.on('save-error', (message) => {
                                            this.showConfirmModal = false;
                                            // Scroll to flash message area to show error
                                            setTimeout(() => {
                                                window.scrollTo({
                                                    top: 0,
                                                    behavior: 'smooth'
                                                });
                                            }, 300);
                                        });
                                    }
                                }">
                                    <button @click="showConfirmModal = true"
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer">
                                        Save Results
                                    </button>

                                    <!-- Confirmation Modal -->
                                    <div x-show="showConfirmModal"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                                        <!-- Background overlay -->
                                        <div class="fixed inset-0 transition-opacity"
                                            @click="showConfirmModal = false" aria-hidden="true">
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
                                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                                            <svg class="h-6 w-6 text-yellow-600" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                            </svg>
                                                        </div>
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                                Confirm Save Results
                                                            </h3>
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-500">
                                                                    Are you sure you want to save these analysis
                                                                    results? This action will finalize the test data and
                                                                    cannot be undone.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button wire:click="saveResults" @click="showConfirmModal = false"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                                                        Save Results
                                                    </button>
                                                    <button @click="showConfirmModal = false"
                                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No Reference Parameters</h3>
                                <p class="mt-1 text-sm text-gray-500">No analysis parameters found for this reference.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
