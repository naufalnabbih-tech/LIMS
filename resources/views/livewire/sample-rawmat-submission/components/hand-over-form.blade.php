<div>
    @if ($show && $sample)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center" style="z-index: 9999;"
             x-data="{ show: true }" x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="$wire.close()">

            <div class="bg-white rounded-lg max-w-lg w-full mx-4 max-h-screen overflow-y-auto"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <!-- Header -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 px-6 py-4 border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-yellow-900">Submit to Hand Over</h3>
                                <p class="text-yellow-700 text-sm">Transfer sample to another analyst</p>
                            </div>
                        </div>
                        <button type="button" wire:click="close" class="text-yellow-700 hover:text-yellow-900 p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Form Content -->
                <form wire:submit.prevent="submitToHandOver" class="p-6 space-y-6">
                    <!-- Sample Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Sample Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Material:</span>
                                <span class="text-gray-900 ml-2">{{ $sample->rawMaterial->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Batch:</span>
                                <span class="text-gray-900 ml-2">{{ $sample->batch_lot ?? 'N/A' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-500">Current Analyst:</span>
                                <span class="text-gray-900 ml-2">{{ $sample->primaryAnalyst->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hand Over Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Hand Over Notes
                        </label>
                        <textarea wire:model="handOverNotes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add any notes or instructions for the next analyst to take over this sample..."></textarea>
                        @error('handOverNotes')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        <button type="button" wire:click="close" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="submitToHandOver">Submit Hand Over</span>
                            <span wire:loading wire:target="submitToHandOver">Submitting...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
