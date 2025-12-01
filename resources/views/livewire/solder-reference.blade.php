<div class="flex flex-col h-[calc(100vh-8rem)]">
    <!-- Header Section -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Solder Reference</h2>
                <p class="text-sm text-gray-600 mt-1">Manage reference specifications for solders</p>
            </div>
            <div>
                <button wire:click="openAddModal()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer
                           {{ $solders->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $solders->isEmpty() ? 'disabled' : '' }}
                    title="{{ $solders->isEmpty() ? 'Tambahkan solder terlebih dahulu' : 'Tambah Data Baru' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Data
                </button>
            </div>
        </div>
    </div>

    <!-- Content Section - Full Height -->
    <div class="flex-1 bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden flex flex-col">

        <!-- Solders warning -->
        @if ($solders->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 m-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">
                            Tidak ada solder yang tersedia. Silakan tambahkan solder terlebih dahulu sebelum
                            menambah reference.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Content Container - Scrollable -->
        <div class="flex-1 overflow-hidden">
            <div class="h-full overflow-y-auto p-6">
                @if ($groupedReferences->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No references found</h3>
                        <p class="text-sm text-gray-500 mb-4">Get started by adding your first reference</p>
                        <button wire:click="openAddModal()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200
                                       {{ $solders->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $solders->isEmpty() ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add Reference
                        </button>
                    </div>
                @else
                    @foreach ($groupedReferences as $solderName => $referencesInGroup)
                        <div class="mb-8">
                            <!-- Solder Header -->
                            <div class="mb-4 sticky top-0 bg-white z-10 border-b border-gray-200 pb-2">
                                <h2 class="text-xl font-bold text-gray-800 border-b-2 border-blue-500 pb-2">
                                    {{ $solderName }}</h2>
                            </div>

                            <!-- References for this Solder -->
                            @foreach ($referencesInGroup as $reference)
                                <div class="mb-6 ml-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-700">{{ $reference->name }}</h3>
                                        <div class="flex space-x-2">
                                            <button wire:click="openEditModal({{ $reference->id }})"
                                                class="inline-flex items-center px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button wire:click="delete({{ $reference->id }})"
                                                wire:confirm="Are you sure you want to delete this reference?"
                                                class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto table-container">
                                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 tracking-wider">
                                                        Specifications</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 tracking-wider">
                                                        Operator</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 tracking-wider">
                                                        Value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @if ($reference->specificationsManytoMany->count() > 0)
                                                    @foreach ($reference->specificationsManytoMany as $spec)
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-6 py-4 text-sm">
                                                                <div class="flex flex-wrap gap-1">
                                                                    <span
                                                                        class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                                        {{ $spec->name }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                                                <span
                                                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                                                    {{ $spec->pivot->operator ?? '==' }}
                                                                </span>
                                                            </td>
                                                            <td
                                                                class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                                                @if ($spec->pivot->operator === '-')
                                                                    @php
                                                                        $ranges =
                                                                            json_decode($spec->pivot->value, true) ?:
                                                                            [];
                                                                    @endphp
                                                                    <div class="flex flex-wrap gap-1">
                                                                        @foreach ($ranges as $range)
                                                                            <span
                                                                                class="inline-flex items-center rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">
                                                                                {{ $range['min'] ?? 'N/A' }} -
                                                                                {{ $range['max'] ?? 'N/A' }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <span>{{ $spec->pivot->value ?? 'N/A' }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="px-6 py-4 text-sm">
                                                            <span class="italic text-gray-400">No specifications</span>
                                                        </td>
                                                        <td class="whitespace-nowrap px-6 py-4 text-sm">-</td>
                                                        <td class="whitespace-nowrap px-6 py-4 text-sm">-</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Pagination Footer -->
        @if ($references->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $references->firstItem() ?? 0 }}</span>
                            to
                            <span class="font-medium">{{ $references->lastItem() ?? 0 }}</span>
                            of
                            <span class="font-medium">{{ $references->total() }}</span>
                            results
                        </p>
                    </div>
                    <div class="sm:hidden">
                        <p class="text-sm text-gray-700">
                            Page {{ $references->currentPage() }} of {{ $references->lastPage() }}
                            ({{ $references->total() }} total)
                        </p>
                    </div>
                    <div>{{ $references->links() }}</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add Modal -->
    @if ($isAddModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 p-4">
            <div class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-8 shadow-2xl">
                <div class="flex items-center justify-between border-b pb-4">
                    <h3 class="text-2xl font-bold">Add New Solder Reference</h3>
                    <button wire:click="closeAddModal()" class="cursor-pointer rounded-full p-2 hover:bg-gray-100">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6">
                    <!-- Error Display -->
                    @if ($errors->any())
                        <div class="mb-4 rounded-r-lg border-l-4 border-red-500 bg-red-100 p-4 text-red-700">
                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit="store">
                        <div class="mb-5">
                            <label for="add-name" class="mb-2 block text-sm font-bold">Reference Name</label>
                            <input type="text" id="add-name" wire:model="name"
                                class="@error('name') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-3 shadow-sm"
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative mb-5">
                            <label for="add-solder" class="mb-2 block text-sm font-bold">Solder</label>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.1000ms="solderSearch"
                                    wire:click="openSolderDropdown" placeholder="Search solder..."
                                    class="@error('material_id') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-3 pr-10 shadow-sm"
                                    autocomplete="off">

                                <!-- Dropdown icon -->
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>

                                <!-- Dropdown List -->
                                @if ($showSolderDropdown && count($this->filteredSolders) > 0)
                                    <div
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach ($this->filteredSolders as $solder)
                                            <div wire:click="selectSolder({{ $solder->id }}, '{{ $solder->name }}')"
                                                class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                {{ $solder->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($showSolderDropdown && empty($this->solderSearch))
                                    <div
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach ($solders as $solder)
                                            <div wire:click="selectSolder({{ $solder->id }}, '{{ $solder->name }}')"
                                                class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                {{ $solder->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($showSolderDropdown)
                                    <div
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                                        <div class="px-4 py-3 text-gray-500">No solders found</div>
                                    </div>
                                @endif
                            </div>
                            @error('material_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-bold">Specifications (Optional)</label>
                            @if ($specifications->isEmpty())
                                <p class="text-sm text-gray-500">No specifications available</p>
                            @else
                                <div class="max-h-48 overflow-y-auto rounded-lg border border-gray-300 p-3">
                                    @foreach ($specifications as $specification)
                                        <label class="mb-2 flex cursor-pointer items-center space-x-2">
                                            <input type="checkbox"
                                                wire:click="toggleSpecification({{ $specification->id }})"
                                                {{ in_array($specification->id, $selectedSpecifications) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="text-sm">{{ $specification->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                            @error('selectedSpecifications')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dynamic Specification Value Inputs -->
                        <!-- Specification Values Section for Add Modal -->
                        @if (!empty($selectedSpecifications))
                            <div class="mb-5">
                                <label class="mb-2 block text-sm font-bold">Specification Values</label>
                                @foreach ($selectedSpecifications as $specId)
                                    @php
                                        $spec = $specifications->find($specId);
                                    @endphp
                                    @if ($spec)
                                        <div class="mb-3">
                                            <div class="mb-2 flex  items-center space-x-2">
                                                <div
                                                    class="inline-flex items-center  rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                    {{ $spec->name }}
                                                </div>

                                                <div class="w-20">
                                                    <select
                                                        wire:model.live="specificationOperators.{{ $specId }}"
                                                        class="@error('specificationOperators.' . $specId) border-red-500 @else border-gray-300 @enderror w-full cursor-pointer appearance-none rounded-lg border px-2 py-2 text-sm shadow-sm">
                                                        <option value=">=">&gt;=</option>
                                                        <option value="<=">&lt;=</option>
                                                        <option value="==">==</option>
                                                        <option value="-">Range</option>
                                                        <option value="should_be">Should be</option>
                                                    </select>
                                                    @error('specificationOperators.' . $specId)
                                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                @if (isset($specificationOperators[$specId]) && $specificationOperators[$specId] === '-')
                                                    <div class="space-y-2 flex-1">
                                                        @if (isset($specificationRanges[$specId]))
                                                            @foreach ($specificationRanges[$specId] as $index => $range)
                                                                <div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <input type="number" step="any"
                                                                            wire:model="specificationRanges.{{ $specId }}.{{ $index }}.min"
                                                                            placeholder="Min"
                                                                            class="@error('specificationRanges.' . $specId . '.' . $index . '.min') border-red-500 @else border-gray-300 @enderror w-24 rounded-lg border px-2 py-2 text-sm shadow-sm">
                                                                        <span class="text-gray-500">-</span>
                                                                        <input type="number" step="any"
                                                                            wire:model="specificationRanges.{{ $specId }}.{{ $index }}.max"
                                                                            placeholder="Max"
                                                                            class="@error('specificationRanges.' . $specId . '.' . $index . '.max') border-red-500 @else border-gray-300 @enderror w-24 rounded-lg border px-2 py-2 text-sm shadow-sm">
                                                                        @if ($index > 0)
                                                                            <button type="button"
                                                                                wire:click="removeRangeRow({{ $specId }}, {{ $index }})"
                                                                                class="text-red-500 hover:text-red-700">
                                                                                <svg class="h-4 w-4" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                                </svg>
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                    @error('specificationRanges.' . $specId . '.' . $index .
                                                                        '.min')
                                                                        <p class="mt-1 text-xs text-red-500">
                                                                            {{ $message }}</p>
                                                                    @enderror
                                                                    @error('specificationRanges.' . $specId . '.' . $index .
                                                                        '.max')
                                                                        <p class="mt-1 text-xs text-red-500">
                                                                            {{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="flex-1">
                                                        @if (isset($specificationOperators[$specId]) && in_array($specificationOperators[$specId], ['should_be']))
                                                            <input type="text"
                                                                wire:model="specificationValues.{{ $specId }}"
                                                                placeholder="Enter {{ $specificationOperators[$specId] === 'should_be' ? 'expected values (comma-separated)' : 'text to contain' }} for {{ $spec->name }}"
                                                                class="@error('specificationValues.' . $specId) border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-3 py-2 text-sm shadow-sm">
                                                        @else
                                                            <input type="number" step="any"
                                                                wire:model="specificationValues.{{ $specId }}"
                                                                placeholder="Enter numeric value for {{ $spec->name }}"
                                                                class="@error('specificationValues.' . $specId) border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-3 py-2 text-sm shadow-sm">
                                                        @endif
                                                    </div>
                                                @endif

                                                @error('specificationValues.' . $specId)
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                                @error('specificationRanges.' . $specId)
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-6 flex justify-end border-t pt-5">
                            <button type="button" wire:click="closeAddModal()"
                                class="mr-3 cursor-pointer rounded-lg bg-gray-100 px-6 py-2 text-gray-700 hover:bg-gray-200">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="store"
                                class="{{ $solders->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }} cursor-pointer rounded-lg bg-blue-500 px-6 py-2 text-white hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-50"
                                {{ $solders->isEmpty() ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="store">Save Reference</span>
                                <span wire:loading wire:target="store">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if ($isEditModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 p-4">
            <div class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-8 shadow-2xl">
                <div class="flex items-center justify-between border-b pb-4">
                    <h3 class="text-2xl font-bold">Edit Solder Reference</h3>
                    <button wire:click="closeEditModal()" class="cursor-pointer rounded-full p-2 hover:bg-gray-100">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6">
                    <!-- Error Display -->
                    @if ($errors->any())
                        <div class="mb-4 rounded-r-lg border-l-4 border-red-500 bg-red-100 p-4 text-red-700">
                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit="update">
                        <div class="mb-5">
                            <label for="edit-name" class="mb-2 block text-sm font-bold">Reference Name</label>
                            <input type="text" id="edit-name" wire:model="name"
                                class="@error('name') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-3 shadow-sm"
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative mb-5">
                            <label for="edit-solder" class="mb-2 block text-sm font-bold">Solder</label>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.1000ms="solderSearch"
                                    wire:click="openSolderDropdown" placeholder="Search solder..."
                                    class="@error('material_id') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-3 pr-10 shadow-sm"
                                    autocomplete="off">

                                <!-- Dropdown icon -->
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>

                                <!-- Dropdown List -->
                                @if ($showSolderDropdown && count($this->filteredSolders) > 0)
                                    <div
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach ($this->filteredSolders as $solder)
                                            <div wire:click="selectSolder({{ $solder->id }}, '{{ $solder->name }}')"
                                                class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                {{ $solder->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($showSolderDropdown && empty($this->solderSearch))
                                    <div
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach ($solders as $solder)
                                            <div wire:click="selectSolder({{ $solder->id }}, '{{ $solder->name }}')"
                                                class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                {{ $solder->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($showSolderDropdown)
                                    <div
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                                        <div class="px-4 py-3 text-gray-500">No solders found</div>
                                    </div>
                                @endif
                            </div>
                            @error('material_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-bold">Specifications (Optional)</label>
                            @if ($specifications->isEmpty())
                                <p class="text-sm text-gray-500">No specifications available</p>
                            @else
                                <div class="max-h-48 overflow-y-auto rounded-lg border border-gray-300 p-3">
                                    @foreach ($specifications as $specification)
                                        <label class="mb-2 flex cursor-pointer items-center space-x-2">
                                            <input type="checkbox"
                                                wire:click="toggleSpecification({{ $specification->id }})"
                                                {{ in_array($specification->id, $selectedSpecifications) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="text-sm">{{ $specification->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                            @error('selectedSpecifications')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dynamic Specification Value Inputs -->
                        <!-- Specification Values Section for Edit Modal -->
                        @if (!empty($selectedSpecifications))
                            <div class="mb-5">
                                <label class="mb-2 block text-sm font-bold">Specification Values</label>
                                @foreach ($selectedSpecifications as $specId)
                                    @php
                                        $spec = $specifications->find($specId);
                                    @endphp
                                    @if ($spec)
                                        <div class="mb-3">
                                            <div class="mb-2 flex items-center space-x-2">
                                                <div
                                                    class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                    {{ $spec->name }}
                                                </div>
                                                <div class="w-24">
                                                    <select
                                                        wire:model.live="specificationOperators.{{ $specId }}"
                                                        class="@error('specificationOperators.' . $specId) border-red-500 @else border-gray-300 @enderror w-full cursor-pointer appearance-none rounded-lg border px-3 py-2 text-sm shadow-sm">
                                                        <option value=">=">>=</option>
                                                        <option value="<=">&lt;=</option>
                                                        <option value="==">==</option>
                                                        <option value="-">Range</option>
                                                        <option value="should_be">Should be</option>
                                                    </select>
                                                    @error('specificationOperators.' . $specId)
                                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                @if (isset($specificationOperators[$specId]) && $specificationOperators[$specId] === '-')
                                                    <div class="space-y-2 flex-1">
                                                        @if (isset($specificationRanges[$specId]))
                                                            @foreach ($specificationRanges[$specId] as $index => $range)
                                                                <div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <input type="text"
                                                                            wire:model="specificationRanges.{{ $specId }}.{{ $index }}.min"
                                                                            placeholder="Min"
                                                                            class="@error('specificationRanges.' . $specId . '.' . $index . '.min') border-red-500 @else border-gray-300 @enderror w-24 rounded-lg border px-3 py-2 text-sm shadow-sm">
                                                                        <span class="text-gray-500">-</span>
                                                                        <input type="text"
                                                                            wire:model="specificationRanges.{{ $specId }}.{{ $index }}.max"
                                                                            placeholder="Max"
                                                                            class="@error('specificationRanges.' . $specId . '.' . $index . '.max') border-red-500 @else border-gray-300 @enderror w-24 rounded-lg border px-3 py-2 text-sm shadow-sm">
                                                                        @if ($index > 0)
                                                                            <button type="button"
                                                                                wire:click="removeRangeRow({{ $specId }}, {{ $index }})"
                                                                                class="text-red-500 hover:text-red-700">
                                                                                <svg class="h-4 w-4" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                                </svg>
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                    @error('specificationRanges.' . $specId . '.' . $index .
                                                                        '.min')
                                                                        <p class="mt-1 text-xs text-red-500">
                                                                            {{ $message }}</p>
                                                                    @enderror
                                                                    @error('specificationRanges.' . $specId . '.' . $index .
                                                                        '.max')
                                                                        <p class="mt-1 text-xs text-red-500">
                                                                            {{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="flex-1">
                                                        @if (isset($specificationOperators[$specId]) && in_array($specificationOperators[$specId], ['should_be']))
                                                            <input type="text"
                                                                wire:model="specificationValues.{{ $specId }}"
                                                                placeholder="Enter {{ $specificationOperators[$specId] === 'should_be' ? 'expected values (comma-separated)' : 'text to contain' }} for {{ $spec->name }}"
                                                                class="@error('specificationValues.' . $specId) border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-3 py-2 text-sm shadow-sm">
                                                        @else
                                                            <input type="number" step="any"
                                                                wire:model="specificationValues.{{ $specId }}"
                                                                placeholder="Enter numeric value for {{ $spec->name }}"
                                                                class="@error('specificationValues.' . $specId) border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-3 py-2 text-sm shadow-sm">
                                                        @endif
                                                    </div>
                                                @endif

                                                @error('specificationValues.' . $specId)
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                                @error('specificationRanges.' . $specId)
                                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-6 flex justify-end border-t pt-5">
                            <button type="button" wire:click="closeEditModal()"
                                class="mr-3 cursor-pointer rounded-lg bg-gray-100 px-6 py-2 text-gray-700 hover:bg-gray-200">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="update"
                                class="cursor-pointer rounded-lg bg-blue-500 px-6 py-2 text-white hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-50">
                                <span wire:loading.remove wire:target="update">Update Reference</span>
                                <span wire:loading wire:target="update">Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
