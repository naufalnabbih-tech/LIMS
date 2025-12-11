<div class="p-6 max-w-7xl mx-auto">

    <div class="mb-6">

        <div class="p-6 max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Certificate of Analysis Management</h1>
                <p class="text-gray-600 mt-1">Manage and track all CoA documents</p>
            </div>

            <!-- Alert Messages -->
            @if (session('message'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filters Section -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search Document Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document Number</label>
                        <input type="text" wire:model.live="searchDocNumber" placeholder="Search..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="searchStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            <option value="draft">Draft</option>
                            <option value="pending_review">Pending Review</option>
                            <option value="approved">Approved</option>
                            <option value="printed">Printed</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" wire:model.live="searchDateFrom"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" wire:model.live="searchDateTo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Document Number</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Sample</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Release Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Approved By</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coas as $coa)
                            <tr wire:key="row-{{ $coa->id }}"
                                class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $coa->document_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $coa->sample?->material?->name ?? 'N/A' }}
                                    <br>
                                    <span class="text-xs text-gray-500">Batch:
                                        {{ $coa->sample?->batch_lot ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $coa->status_color }}">
                                        {{ $coa->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $coa->release_date?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $coa->approver?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <button wire:click="viewCoA({{ $coa->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="viewCoA({{ $coa->id }})"
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors disabled:opacity-50 cursor-pointer">
                                        <span wire:loading.remove wire:target="viewCoA({{ $coa->id }})">View</span>
                                        <span wire:loading wire:target="viewCoA({{ $coa->id }})">Loading...</span>
                                    </button>

                                    @if ($coa->isDraft() || $coa->isPending())
                                        <button wire:click="editCoA({{ $coa->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="editCoA({{ $coa->id }})"
                                            class="px-3 py-1 bg-amber-100 text-amber-700 rounded hover:bg-amber-200 transition-colors disabled:opacity-50 cursor-pointer">
                                            <span wire:loading.remove wire:target="editCoA({{ $coa->id }})">Edit</span>
                                            <span wire:loading wire:target="editCoA({{ $coa->id }})">Loading...</span>
                                        </button>
                                    @endif

                                    @if ($coa->isDraft())
                                        <button wire:click="submitForReview({{ $coa->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="submitForReview({{ $coa->id }})"
                                            class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition-colors disabled:opacity-50 cursor-pointer">
                                            <span wire:loading.remove wire:target="submitForReview({{ $coa->id }})">Submit</span>
                                            <span wire:loading wire:target="submitForReview({{ $coa->id }})">Submitting...</span>
                                        </button>
                                    @endif

                                    @if (auth()->user()?->hasPermission('approve_coa') && $coa->isPending())
                                        <button wire:click="approveCoA({{ $coa->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="approveCoA({{ $coa->id }})"
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors disabled:opacity-50 cursor-pointer">
                                            <span wire:loading.remove wire:target="approveCoA({{ $coa->id }})">Approve</span>
                                            <span wire:loading wire:target="approveCoA({{ $coa->id }})">Approving...</span>
                                        </button>
                                    @endif

                                    @if (auth()->user()?->hasPermission('approve_coa') && ($coa->isApproved() || $coa->isPrinted()))
                                        <button wire:click="printCoA({{ $coa->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="printCoA({{ $coa->id }})"
                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors disabled:opacity-50 cursor-pointer">
                                            <span wire:loading.remove wire:target="printCoA({{ $coa->id }})">Print</span>
                                            <span wire:loading wire:target="printCoA({{ $coa->id }})">Printing...</span>
                                        </button>
                                    @endif

                                    @if ($coa->isDraft())
                                        <button wire:click="deleteCoA({{ $coa->id }})"
                                            wire:confirm="Are you sure you want to delete this CoA?"
                                            wire:loading.attr="disabled"
                                            wire:target="deleteCoA({{ $coa->id }})"
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors disabled:opacity-50 cursor-pointer">
                                            <span wire:loading.remove wire:target="deleteCoA({{ $coa->id }})">Delete</span>
                                            <span wire:loading wire:target="deleteCoA({{ $coa->id }})">Deleting...</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No CoA records found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $coas->links() }}
                </div>
            </div>

            <!-- Create Modal -->
            @if ($showModal)
                <div class="fixed inset-0 bg-gray-900/75 overflow-y-auto h-full w-full z-50" wire:key="create-modal">
                    <div
                        class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $modalMode === 'create' ? 'Create CoA' : 'Edit CoA' }}
                            </h3>
                            <button type="button" wire:click="closeModal"
                                class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="space-y-4">
                            <!-- Display All Errors -->
                            @if ($errors->any())
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                    <p class="text-red-800 font-semibold text-sm mb-2">Validation Errors:</p>
                                    <ul class="list-disc list-inside text-red-700 text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Format Selection (Create Mode Only) -->
                            @if ($modalMode === 'create' && count($availableFormats) > 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">CoA Format</label>
                                    <select wire:model.live="formatId"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                        <option value="">Select Format...</option>
                                        @foreach ($availableFormats as $format)
                                            <option value="{{ $format['id'] }}">
                                                {{ $format['name'] ?? 'Format ' . $format['id'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('formatId')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <!-- Document Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Document Number</label>
                                @if ($modalMode === 'edit')
                                    {{-- Show existing document number as readonly in edit mode --}}
                                    <input type="text" value="{{ $documentNumber ?: 'Auto-generate on approval' }}" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                                    <p class="text-xs text-gray-500 mt-1">Document number cannot be changed after creation</p>
                                @else
                                    <input type="text" wire:model="documentNumber"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Will be auto-generated on approval">
                                @endif
                                @error('documentNumber')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Net Weight -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Net Weight</label>
                                <input type="text" wire:model="netWeight"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="e.g., 25 kg, 500 g">
                                @error('netWeight')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- PO No -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PO No</label>
                                <input type="text" wire:model="poNo"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Purchase Order Number">
                                @error('poNo')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea wire:model="notes" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Additional notes..."></textarea>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button type="button" wire:click="saveCoA" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                                <span wire:loading.remove>{{ $modalMode === 'create' ? 'Create' : 'Save' }}</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- View Modal -->
            @if ($showViewModal && $coaId)
                <div class="fixed inset-0 bg-gray-900/75 overflow-y-auto h-full w-full z-50 flex items-center justify-center"
                    wire:key="view-modal-{{ $coaId }}">
                    <div
                        class="relative mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">View CoA -
                                {{ $documentNumber ?: 'Loading...' }}</h3>
                            <button type="button" wire:click="closeViewModal"
                                class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="space-y-2">
                            <!-- Document Number -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Number</label>
                                    <p class="text-gray-900">{{ $documentNumber }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <span
                                        class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $this->getStatusColor($status) }}">
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Release Date</label>
                                    <p class="text-gray-900">{{ $approvedAt ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Net Weight</label>
                                    <p class="text-gray-900">{{ $netWeight ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">PO No</label>
                                    <p class="text-gray-900">{{ $poNo ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- Approvals -->
                            @if ($status === 'approved' || $status === 'printed')
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                                        <p class="text-gray-900">{{ $approvedBy ?: 'Authorized Signatory' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved At</label>
                                        <p class="text-gray-900">{{ $approvedAt ?? '-' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                    <p class="text-gray-900">{{ $approverRole ?: 'Authorized Signatory' }}</p>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if ($notes)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $notes }}</p>
                                </div>
                            @endif

                            <!-- Preview Template -->
                            <div class="mt-3 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-gray-700">Print Preview</h4>
                                    <span class="text-xs text-gray-500">Preview only</span>
                                </div>
                                <div class="p-4 bg-white text-sm text-gray-900 max-h-96 overflow-y-auto">
                                    <!-- Mini Preview of CoA Template -->
                                    <div class="border border-gray-300 p-3 text-xs">
                                        <div class="flex justify-between items-start border-b border-gray-300 pb-2 mb-2">
                                            <div>
                                                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-6 object-contain">
                                            </div>
                                            <div class="text-right text-[10px] space-y-0.5">
                                                <p>Form No: F-LAB-026</p>
                                                <p>Rev No: 0</p>
                                            </div>
                                        </div>

                                        <div class="text-center mb-2">
                                            <h2 class="text-sm font-bold underline">CERTIFICATE OF ANALYSIS</h2>
                                            <p class="font-semibold text-xs">No. {{ $documentNumber }}</p>
                                        </div>

                                        <div class="grid grid-cols-[80px_5px_1fr] gap-y-0.5 mb-2 text-[10px]">
                                            <div class="font-semibold">Brand</div>
                                            <div>:</div>
                                            <div class="font-bold">{{ $data['material'] ?? '-' }}</div>
                                            <div class="font-semibold">Lot No</div>
                                            <div>:</div>
                                            <div>{{ $data['batch_lot'] ?? '-' }}</div>
                                            <div class="font-semibold">Date of Inspection</div>
                                            <div>:</div>
                                            <div>{{ $data['inspection_date'] ?? '-' }}</div>
                                            <div class="font-semibold">Date of Release</div>
                                            <div>:</div>
                                            <div>{{ $approvedAt ?? $data['release_date'] ?? '-' }}</div>
                                            <div class="font-semibold">Net Weight</div>
                                            <div>:</div>
                                            <div>{{ $netWeight ?? '-' }}</div>
                                            <div class="font-semibold">PO No</div>
                                            <div>:</div>
                                            <div>{{ $poNo ?? '-' }}</div>
                                            @php
                                                // Only show custom fields if they were saved at creation time
                                                // This preserves original CoAs from being affected by format changes
                                                $customFieldsDefinition = $data['_custom_fields_definition'] ?? null;

                                                $customFields = [];
                                                if (!empty($customFieldsDefinition)) {
                                                    foreach ($customFieldsDefinition as $field) {
                                                        $fieldKey = $field['key'] ?? '';
                                                        $fieldLabel = $field['label'] ?? '';
                                                        $fieldValue = $data[$fieldKey] ?? '';
                                                        if ($fieldKey && $fieldLabel) {
                                                            $customFields[] = [
                                                                'label' => $fieldLabel,
                                                                'value' => $fieldValue
                                                            ];
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @foreach ($customFields as $customField)
                                                <div class="font-semibold">{{ $customField['label'] }}</div>
                                                <div>:</div>
                                                <div>{{ $customField['value'] ?: '-' }}</div>
                                            @endforeach
                                        </div>

                                        <p class="mb-1 italic text-gray-700 text-[9px]">
                                            The undersigned hereby certifies the following data to be true specification of the obtained results of tests and assays.
                                        </p>

                                        <table class="w-full border-collapse border border-gray-400 mb-2 text-[9px]">
                                            <thead>
                                                <tr class="bg-gray-100 text-center font-bold">
                                                    <th class="border border-gray-400 px-1 py-0.5 text-[8px]">TESTS</th>
                                                    <th class="border border-gray-400 px-1 py-0.5 text-[8px]">SPECIFICATION</th>
                                                    <th class="border border-gray-400 px-1 py-0.5 text-[8px]">RESULTS</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @php
                                                    $tests = $data['tests'] ?? [];
                                                @endphp
                                                @forelse ($tests as $test)
                                                    <tr>
                                                        <td class="border border-gray-400 px-1 py-0.5 text-left text-[8px]">{{ $test['name'] ?? '-' }}</td>
                                                        <td class="border border-gray-400 px-1 py-0.5 text-[8px]">{{ $test['spec'] ?? '-' }}</td>
                                                        <td class="border border-gray-400 px-1 py-0.5 text-[8px]">{{ $test['result'] ?? '-' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="border border-gray-400 px-1 py-0.5 text-center text-gray-500 text-[8px]">No test data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <div class="flex justify-end mt-3 pr-4">
                                            <div class="text-center text-[8px]">
                                                <div class="h-8"></div>
                                                @if ($approvedBy && $approvedBy !== '-')
                                                    <p class="font-bold border-b border-gray-700 inline-block mb-0.5 text-[9px]">{{ $approvedBy }}</p>
                                                    <p class="text-[8px]">{{ $approverRole !== '-' ? $approverRole : 'Authorized Signatory' }}</p>
                                                @else
                                                    <p class="font-bold border-b border-gray-700 inline-block mb-0.5 text-[9px]">Authorized Signatory</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end gap-3 mt-3 pt-2 border-t border-gray-200">
                            @if (auth()->user()?->hasPermission('approve_coa') && $status === 'pending_review')
                                <button type="button" wire:click="approveCoA({{ $coaId }})"
                                    onclick="return confirm('Approve this CoA?')"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                                    Approve CoA
                                </button>
                            @endif

                            <button type="button" wire:click="closeViewModal"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors cursor-pointer">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @script
    <script>
        $wire.on('openPrintWindow', (event) => {
            const printUrl = event.url;
            console.log('Opening print window for:', printUrl);
            const printWindow = window.open(printUrl, '_blank', 'width=1200,height=800');
            if (printWindow) {
                printWindow.addEventListener('load', () => {
                    setTimeout(() => {
                        printWindow.print();
                    }, 500);
                });
            } else {
                alert('Popup blocked! Please allow popups for this site.');
            }
        });
    </script>
    @endscript
