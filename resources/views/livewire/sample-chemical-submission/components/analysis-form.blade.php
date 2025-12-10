<div>
    <!-- Analysis Form Modal -->
    @if ($showAnalysisForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="$wire.hide()">

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
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Chemical Material</label>
                                        <div class="text-base font-medium text-gray-900">
                                            {{ $selectedAnalysisSample->material->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                        <div class="text-base font-medium text-gray-900">
                                            {{ $selectedAnalysisSample->category->name ?? 'N/A' }}
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
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
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
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <span class="flex items-center space-x-1">
                                                <span>Secondary Analyst</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <select wire:model.live="secondaryAnalystId"
                                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm transition-all duration-200 @error('secondaryAnalystId') border-red-500 ring-2 ring-red-200 @enderror">
                                            <option value="" hidden>Select an analyst to assist</option>
                                            @foreach ($operators as $operator)
                                                @if ($operator->id != auth()->id())
                                                <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('secondaryAnalystId')
                                            <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>

                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" wire:click="hide"
                            class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-200 cursor-pointer">
                            Cancel
                        </button>
                        <button type="button" wire:click="validateBeforeConfirm" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                            <span wire:loading.remove wire:target="validateBeforeConfirm">Start Analysis</span>
                            <span wire:loading wire:target="validateBeforeConfirm">Validating...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        @if($showConfirmation)
        <div x-show="true" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] overflow-y-auto" x-cloak>

            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showConfirmation', false)" aria-hidden="true">
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
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Konfirmasi Start Analysis
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Apakah Anda yakin ingin memulai analisis untuk sample ini? Status sample akan
                                        berubah menjadi "In Progress".
                                    </p>

                                    <!-- Analysis Details -->
                                    <div class="bg-gray-50 rounded-lg p-3 text-sm">
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 font-medium">Metode Analisis:</span>
                                                <span class="text-gray-900 capitalize">
                                                    <span
                                                        x-text="$wire.analysisMethod === 'joint' ? 'Joint Analysis' : 'Individual Analysis'"></span>
                                                </span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-600 font-medium">Primary Analyst:</span>
                                                <span class="text-gray-900">{{ auth()->user()->name }}</span>
                                            </div>

                                            @if ($analysisMethod === 'joint')
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600 font-medium">Secondary Analyst:</span>
                                                    <span class="text-gray-900">
                                                        @foreach ($operators as $operator)
                                                            @if ($operator->id == $secondaryAnalystId)
                                                                {{ $operator->name }}
                                                            @endif
                                                        @endforeach
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="startAnalysisProcess" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="startAnalysisProcess">Start Analysis</span>
                            <span wire:loading wire:target="startAnalysisProcess">Starting...</span>
                        </button>
                        <button wire:click="$set('showConfirmation', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
