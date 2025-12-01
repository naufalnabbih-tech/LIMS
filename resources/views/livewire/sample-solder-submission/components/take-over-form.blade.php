<div x-data="{ showConfirmation: false }">
    @if ($show && $sample)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center" style="z-index: 9999;"
            x-data="{ show: true }" x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click.self="$wire.close()">

            <div class="bg-white rounded-lg max-w-lg w-full mx-4 max-h-screen overflow-y-auto" x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <!-- Header -->
                <div class="bg-gradient-to-br from-orange-50 to-red-50 px-6 py-4 border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-orange-900">Take Over Sample</h3>
                                <p class="text-orange-700 text-sm">Take ownership and continue analysis</p>
                            </div>
                        </div>
                        <button type="button" wire:click="close" class="text-orange-700 hover:text-orange-900 p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Form Content -->
                <form wire:submit.prevent="submitTakeOver" class="p-6 space-y-6">
                    <!-- Sample Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Sample Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Material:</span>
                                <span class="text-gray-900 ml-2">{{ $sample->material->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Batch:</span>
                                <span class="text-gray-900 ml-2">{{ $sample->batch_lot ?? 'N/A' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-500">Previous Analyst:</span>
                                <span class="text-gray-900 ml-2">{{ $sample->primaryAnalyst->name ?? 'N/A' }}</span>
                            </div>
                            @if ($sample->handover_notes)
                                <div class="col-span-2">
                                    <span class="text-gray-500">Hand Over Notes:</span>
                                    <p class="text-gray-900 mt-1 text-sm bg-yellow-50 p-2 rounded">
                                        {{ $sample->handover_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Analysis Method Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Analysis Method <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="{ 'border-orange-500 bg-orange-50': @js($takeOverAnalysisMethod === 'individual') }">
                                <input type="radio" wire:model.live="takeOverAnalysisMethod" value="individual"
                                    class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-700">Individual Analysis</div>
                                    <div class="text-xs text-gray-500">Continue analysis on your own</div>
                                </div>
                            </label>
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="{ 'border-orange-500 bg-orange-50': @js($takeOverAnalysisMethod === 'joint') }">
                                <input type="radio" wire:model.live="takeOverAnalysisMethod" value="joint"
                                    class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-700">Joint Analysis</div>
                                    <div class="text-xs text-gray-500">Work together with another analyst</div>
                                </div>
                            </label>
                        </div>
                        @error('takeOverAnalysisMethod')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Secondary Analyst Selection (for joint analysis) -->
                    @if ($takeOverAnalysisMethod === 'joint')
                        <div x-show="true" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Secondary Analyst <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="takeOverSecondaryAnalystId"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="" hidden>Select secondary analyst...</option>
                                @foreach ($operators as $operator)
                                    @if ($operator->id != auth()->id())
                                        <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('takeOverSecondaryAnalystId')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        <button type="button" wire:click="close"
                            class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 cursor-pointer">
                            Cancel
                        </button>
                        <button type="button" @click="showConfirmation = true"
                            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 cursor-pointer"
                            wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="submitTakeOver">Take Over Sample</span>
                            <span wire:loading wire:target="submitTakeOver">Taking Over...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="showConfirmation" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">

            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" @click="showConfirmation = false" aria-hidden="true">
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
                                    Konfirmasi Take Over Sample
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Apakah Anda yakin ingin take over sample ini? Anda akan menjadi analyst utama
                                        untuk melanjutkan analisis.
                                    </p>

                                    <!-- Sample Details -->
                                    <div class="bg-gray-50 rounded-lg p-3 text-sm">
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 font-medium">Material:</span>
                                                <span
                                                    class="text-gray-900">{{ $sample->material->name ?? 'N/A' }}</span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-600 font-medium">Batch/Lot:</span>
                                                <span class="text-gray-900">{{ $sample->batch_lot ?? 'N/A' }}</span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-600 font-medium">Metode Analisis:</span>
                                                <span class="text-gray-900 capitalize">
                                                    <span
                                                        x-text="$wire.takeOverAnalysisMethod === 'joint' ? 'Joint Analysis' : 'Individual Analysis'"></span>
                                                </span>
                                            </div>

                                            @if ($takeOverAnalysisMethod === 'joint')
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600 font-medium">Secondary Analyst:</span>
                                                    <span class="text-gray-900">
                                                        @foreach ($operators as $operator)
                                                            @if ($operator->id == $takeOverSecondaryAnalystId)
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
                        <button wire:click="submitTakeOver" @click="showConfirmation = false"
                            wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Take Over Sample</span>
                            <span wire:loading>Processing...</span>
                        </button>
                        <button @click="showConfirmation = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
