<div>
    @if ($showHandOverForm)
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 overflow-y-auto"
            wire:click="hide">

            <!-- Modal Card -->
            <div class="relative mx-auto my-10 w-11/12 md:w-4/5 lg:w-3/5 xl:w-1/2 max-w-4xl"
                wire:click.stop
                x-data>
                <div class="shadow-2xl rounded-2xl overflow-hidden bg-white">

                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-5 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-blue-100">Workflow</p>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-xl font-semibold text-white">Hand Over Sample</h3>
                                </div>
                            </div>
                        </div>
                        <button wire:click="hide"
                            class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-6 bg-gray-50/60">
                        <!-- Error Display -->
                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <h4 class="text-sm font-medium text-red-800">Please correct the following errors:</h4>
                                </div>
                                <ul class="text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <!-- Sample Summary -->
                            @if ($selectedSample)
                                <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-4 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50/70 to-cyan-50/60 pointer-events-none"></div>
                                    <div class="relative flex items-start justify-between">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center shadow-inner">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-blue-600">Selected Sample</p>
                                                <p class="text-base font-semibold text-gray-900">{{ $selectedSample->material->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="hidden sm:flex items-center space-x-2 bg-white/80 border border-blue-100 rounded-full px-3 py-1 text-xs font-medium text-gray-700 shadow-sm">
                                            <span class="text-gray-500">Batch</span>
                                            <span class="text-gray-900">{{ $selectedSample->batch_lot ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="relative grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                        <div class="bg-white/70 rounded-lg border border-gray-100 p-3">
                                            <p class="text-gray-500">Reference</p>
                                            <p class="text-gray-900 font-semibold">{{ $selectedSample->reference->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-white/70 rounded-lg border border-gray-100 p-3">
                                            <p class="text-gray-500">Category</p>
                                            <p class="text-gray-900 font-semibold">{{ $selectedSample->category->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-white/70 rounded-lg border border-gray-100 p-3">
                                            <p class="text-gray-500">Batch/Lot</p>
                                            <p class="text-gray-900 font-semibold">{{ $selectedSample->batch_lot ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Form -->
                            <form wire:submit.prevent="submitHandOver" class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-6">
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">Hand Over Details</h4>
                                        <p class="text-sm text-gray-500">Provide reason and context for hand over</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Reason -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Reason</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="text"
                                                wire:model="reason"
                                                placeholder="e.g., Shift ended, emergency, reassigning analyst"
                                                class="w-full px-4 py-3 pl-11 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('reason') border-red-500 ring-red-200 @enderror">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h6m-6 0v11a2 2 0 002 2h4a2 2 0 002-2V7" />
                                                </svg>
                                            </div>
                                        </div>
                                        @error('reason')
                                            <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Notes -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">
                                            Notes (Optional)
                                        </label>
                                        <div class="relative">
                                            <textarea wire:model="notes"
                                                rows="3"
                                                placeholder="Additional context, instructions, or handover checklist"
                                                class="w-full px-4 py-3 pl-11 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('notes') border-red-500 ring-red-200 @enderror"></textarea>
                                            <div class="absolute top-3 left-0 flex items-start pl-3 pointer-events-none text-gray-400">
                                                <svg class="w-4 h-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </div>
                                        </div>
                                        @error('notes')
                                            <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 pt-4 border-t border-gray-200">
                                    <button type="button" wire:click="hide"
                                        class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition cursor-pointer">
                                        Cancel
                                    </button>
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-semibold shadow-lg hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                                        <span wire:loading.remove>Submit Hand Over</span>
                                        <span wire:loading>Submitting...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
