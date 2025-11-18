<div>
    <!-- Analysis Form Modal -->
    @if ($showAnalysisForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click.self="$wire.hide()">

            <div class="relative top-4 mx-auto my-4 w-11/12 md:w-4/5 lg:w-3/5 xl:w-1/2 max-w-4xl shadow-2xl rounded-2xl bg-white overflow-hidden max-h-[calc(100vh-2rem)]"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-5 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white">Start Analysis</h3>
                                <p class="text-green-100 text-sm">Begin laboratory analysis process</p>
                            </div>
                        </div>
                        <button type="button" wire:click="hide"
                            class="text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm p-2 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="overflow-y-auto flex-1 max-h-[calc(100vh-10rem)]">
                    <div class="px-6 py-6">
                        @if ($selectedAnalysisSample)
                            <!-- Sample Information Display -->
                            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Sample Information</h4>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Raw
                                            Material</label>
                                        <div class="text-base font-medium text-gray-900">
                                            {{ $selectedAnalysisSample->rawMaterial->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                        <div class="text-base font-medium text-gray-900">
                                            {{ $selectedAnalysisSample->category->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1">Batch/Lot</label>
                                        <div class="text-base font-medium text-gray-900">
                                            {{ $selectedAnalysisSample->batch_lot }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Submission
                                            Time</label>
                                        <div class="text-base font-medium text-gray-900">
                                            {{ $selectedAnalysisSample->submission_time->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('analysisMethod')
                                <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 p-3 rounded-r-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium">{{ $message }}</p>
                                        </div>
                                    </div>
                                </div>
                            @enderror

                            <!-- Analysis Method Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-4">Analysis
                                    Method</label>
                                <div class="space-y-3">
                                    <div class="flex items-start space-x-3">
                                        <input wire:model.live="analysisMethod" type="radio" id="individual"
                                            value="individual"
                                            class="mt-1 w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 focus:ring-2">
                                        <div class="flex-1">
                                            <label for="individual"
                                                class="text-sm font-medium text-gray-900 cursor-pointer">
                                                Individual Analysis
                                            </label>
                                            <p class="text-xs text-gray-500 mt-1">Analysis conducted by a single
                                                analyst</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <input wire:model.live="analysisMethod" type="radio" id="joint"
                                            value="joint"
                                            class="mt-1 w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 focus:ring-2">
                                        <div class="flex-1">
                                            <label for="joint"
                                                class="text-sm font-medium text-gray-900 cursor-pointer">
                                                Joint Analysis
                                            </label>
                                            <p class="text-xs text-gray-500 mt-1">Analysis conducted by multiple
                                                analysts working together</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Analyst Selection -->
                            @if ($analysisMethod === 'individual')
                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">Assigned
                                        Analyst</label>
                                    <div
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-base font-medium text-gray-900">
                                        {{ auth()->user()->name }} (Current User)
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Individual analysis will be conducted by
                                        you</p>
                                </div>
                            @elseif ($analysisMethod === 'joint')
                                <div class="mb-6 space-y-4">
                                    <label class="block text-sm font-semibold text-gray-900">Assigned
                                        Analysts</label>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary
                                            Analyst</label>
                                        <div
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-base font-medium text-gray-900">
                                            {{ auth()->user()->name }} (Current User)
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">You will be the primary analyst</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary
                                            Analyst</label>
                                        <select wire:model="secondaryAnalystId"
                                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm transition-all duration-200">
                                            <option value="">Select an analyst to assist</option>
                                            @foreach ($operators as $operator)
                                                <option value="{{ $operator->id }}">{{ $operator->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" wire:click="hide"
                            class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="button" wire:click="startAnalysisProcess" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Start Analysis</span>
                            <span wire:loading>Starting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
