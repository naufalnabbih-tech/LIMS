<div>
    @if($showHandOverForm)
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-gray-900/50 overflow-y-auto h-full w-full z-50"
         wire:click="hide">
        <!-- Modal Content -->
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white"
             wire:click.stop>

            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-900">
                    Hand Over Sample
                </h3>
                <button wire:click="hide"
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Sample Info -->
            @if($selectedSample)
            <div class="mt-4 p-4 bg-gray-50 rounded">
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">Sample ID:</span> {{ $selectedSample->id }}
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">Material:</span> {{ $selectedSample->material->name ?? 'N/A' }}
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">Batch/Lot:</span> {{ $selectedSample->batch_lot }}
                </p>
            </div>
            @endif

            <!-- Form -->
            <form wire:submit.prevent="submitHandOver" class="mt-4">

                <!-- Reason -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="reason"
                           placeholder="e.g., Shift ended, Emergency, etc."
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror">
                    @error('reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Notes (Optional)
                    </label>
                    <textarea wire:model="notes"
                              rows="3"
                              placeholder="Additional notes..."
                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"></textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2 pt-4 border-t">
                    <button type="button"
                            wire:click="hide"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Submit Hand Over
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
