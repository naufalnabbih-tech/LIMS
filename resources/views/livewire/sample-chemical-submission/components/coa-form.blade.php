<div>
    <!-- Create CoA Modal -->
    @if ($showCoAModal)
        <div class="fixed inset-0 bg-gray-900/75 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Create Certificate of Analysis</h3>
                <button type="button" wire:click="closeCoAModal"
                    class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="space-y-4">
                <!-- Sample Info (Read-only) -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-2">Sample Information</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-600">Lot No:</span>
                            <p class="font-semibold text-gray-900">{{ $coaData['batch_lot'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Brands:</span>
                            <p class="font-semibold text-gray-900">{{ $coaData['material'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Date of Inspection:</span>
                            <p class="font-semibold text-gray-900">{{ $coaData['inspection_date'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Document Number -->
                <div class="space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document Format *</label>
                        <select wire:model.live="coaFormatId" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" hidden>-- Pilih Format --</option>
                            @foreach($availableFormats as $format)
                                <option value="{{ $format['id'] ?? $format->id }}">
                                    {{ $format['name'] ?? $format->name }} ({{ ($format['prefix'] ?? $format->prefix) . '-' . ($format['year_month'] ?? $format->year_month) }})
                                </option>
                            @endforeach
                        </select>
                        @error('coaFormatId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document Number</label>
                        <div class="space-y-2">
                            <!-- Display Full Number (with sequence) -->
                            <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                                <span class="text-sm font-semibold">No. {{ $coaFullNumber }}</span>
                            </div>
                            <!-- Show what will be saved (without sequence number) -->
                            <p class="text-xs text-gray-500">
                                <span class="font-medium">Disimpan sebagai:</span> {{ $coaDocumentNumber }}
                            </p>
                        </div>
                        @error('coaDocumentNumber') <span class="text-red-600 text-sm"s>{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Net Weight -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Net Weight</label>
                    <input type="text" wire:model="coaNetWeight"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., 25 kg, 500 g">
                    @error('coaNetWeight') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- PO Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">PO No.</label>
                    <input type="text" wire:model="coaPoNo"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Purchase Order Number">
                    @error('coaPoNo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Dynamic Custom Fields --}}
                @php
                    $selectedFormat = collect($availableFormats)->firstWhere('id', $coaFormatId);

                    // Check if this is an existing CoA being edited with saved custom fields definition
                    // If so, use the saved definition to avoid affecting old CoAs
                    if (isset($coaData['_custom_fields_definition'])) {
                        $customFields = $coaData['_custom_fields_definition'];
                    } else {
                        $customFields = $selectedFormat['custom_fields'] ?? [];
                    }
                @endphp

                <!-- Test Specifications (Editable) -->
                @if(!empty($coaData['tests']))
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Test Specifications</p>
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach($coaData['tests'] as $index => $test)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="font-medium text-gray-900">{{ $test['name'] ?? 'Test' }}</p>
                                        <p class="text-xs text-gray-500">Result: {{ $test['result'] ?? '-' }}</p>
                                    </div>

                                    @if(($test['operator'] ?? null) === 'range' || (isset($test['min']) && isset($test['max'])))
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Min</label>
                                                <input type="number" step="0.01" wire:model="coaData.tests.{{ $index }}.min"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Max</label>
                                                <input type="number" step="0.01" wire:model="coaData.tests.{{ $index }}.max"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                        </div>
                                    @elseif(($test['operator'] ?? null) === 'should_be')
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Specification</label>
                                            <input type="text" wire:model="coaData.tests.{{ $index }}.value"
                                                class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-600">Spec: {{ $test['spec'] ?? '-' }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($customFields))
                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <p class="text-sm font-medium text-gray-700 mb-3">Custom Fields</p>
                        <div class="space-y-3">
                            @foreach($customFields as $field)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $field['label'] ?? 'Field' }}
                                        @if($field['required'] ?? false) <span class="text-red-600">*</span> @endif
                                    </label>

                                    @if(($field['type'] ?? 'text') === 'textarea')
                                        <textarea wire:model="customFieldValues.{{ $field['key'] }}"
                                            rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($field['required'] ?? false) required @endif></textarea>
                                    @elseif(($field['type'] ?? 'text') === 'number')
                                        <input type="number" wire:model="customFieldValues.{{ $field['key'] }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($field['required'] ?? false) required @endif>
                                    @elseif(($field['type'] ?? 'text') === 'date')
                                        <input type="date" wire:model="customFieldValues.{{ $field['key'] }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($field['required'] ?? false) required @endif>
                                    @else
                                        <input type="text" wire:model="customFieldValues.{{ $field['key'] }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($field['required'] ?? false) required @endif>
                                    @endif

                                    @error('customFieldValues.' . $field['key'])
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" wire:click="closeCoAModal"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="button" wire:click="createCoA"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                    Create CoA
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
