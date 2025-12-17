<div class="flex flex-col h-[calc(100vh-8rem)]">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Chemical Reference</h2>
                <p class="text-sm text-gray-600 mt-1">Manage reference specifications for chemicals</p>
            </div>
            <div>
                <button wire:click="openAddModal()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer
                           {{ $chemicals->isEmpty() || $specifications->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $chemicals->isEmpty() || $specifications->isEmpty() ? 'disabled' : '' }}
                    title="{{ $chemicals->isEmpty() ? 'Tambahkan chemical terlebih dahulu' : ($specifications->isEmpty() ? 'Tambahkan specification terlebih dahulu' : 'Tambah Data Baru') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Data
                </button>
            </div>
        </div>
    </div>

    <div class="flex-1 bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden flex flex-col">

        @if ($chemicals->isEmpty() || $specifications->isEmpty())
            <div
                class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 m-4 rounded-r-lg space-y-4 divide-y divide-yellow-400/50">
                @if ($chemicals->isEmpty())
                    <div class="pb-[1%]">
                        Tidak ada chemical yang tersedia. Silakan tambahkan chemical terlebih dahulu sebelum menambah
                        reference.
                    </div>
                @endif
                @if ($specifications->isEmpty())
                    <div>
                        Tidak ada specification yang tersedia! Silakan tambahkan specification terlebih dahulu sebelum
                        menambah reference.
                    </div>
                @endif
            </div>
        @endif

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
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer
                                   {{ $chemicals->isEmpty() || $specifications->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $chemicals->isEmpty() || $specifications->isEmpty() ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add Reference
                        </button>
                    </div>
                @else
                    @foreach ($groupedReferences as $chemicalName => $referencesInGroup)
                        <div class="mb-8">
                            <div class="mb-4 sticky top-0 bg-white z-10 border-b border-gray-200 pb-2">
                                <h2 class="text-xl font-bold text-gray-800 border-b-2 border-blue-500 pb-2">
                                    {{ $chemicalName }}</h2>
                            </div>

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
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 tracking-wider">
                                                        Unit</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @if ($reference->specificationsManytoMany->count() > 0)
                                                    @foreach ($reference->specificationsManytoMany as $spec)
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-6 py-4 text-sm">
                                                                <div class="flex flex-wrap gap-1">
                                                                    <span
                                                                        class="inline-flex items-center rounded-md bg-sky-50 text-sky-700 border border-sky-200 px-2.5 py-0.5 text-xs font-medium">
                                                                        {{ $spec->name }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                                                <span
                                                                    class="inline-flex items-center rounded-md bg-violet-50 text-violet-700 border border-violet-200 px-2 py-1 text-xs font-medium">
                                                                    {{ $spec->pivot->operator ?? '==' }}
                                                                </span>
                                                            </td>
                                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                                                @if ($spec->pivot->operator === '-')
                                                                    <span
                                                                        class="inline-flex items-center rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-1 text-xs font-medium">
                                                                        {{ $spec->pivot->value ?? 'N/A' }} -
                                                                        {{ $spec->pivot->max_value ?? 'N/A' }}
                                                                    </span>
                                                                @elseif ($spec->pivot->operator === 'should_be')
                                                                    <span
                                                                        class="inline-flex items-center rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-1 text-xs font-medium">{{ $spec->pivot->text_value ?? 'N/A' }}</span>
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-1 text-xs font-medium">{{ $spec->pivot->value ?? 'N/A' }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                                                <span
                                                                    class="inline-flex items-center rounded-md bg-amber-50 text-amber-700 border border-amber-200 px-2 py-1 text-xs font-medium">{{ $spec->pivot->unit ?? '-' }}</span>
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

        @if ($references->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $references->links() }}
            </div>
        @endif
    </div>

    {{-- SHARED MODAL CONTENT LOGIC --}}
    @foreach (['Add' => $isAddModalOpen, 'Edit' => $isEditModalOpen] as $mode => $isOpen)
        @if ($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 p-4 transition-opacity">
                <div class="relative max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-2xl bg-white p-8 shadow-2xl">

                    <div class="flex items-center justify-between border-b pb-4 mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $mode }} Chemical Reference</h3>
                            <p class="text-sm text-gray-500 mt-1">Manage details and specifications</p>
                        </div>
                        <button wire:click="close{{ $mode }}Modal()"
                            class="cursor-pointer rounded-full p-2 hover:bg-gray-100 transition-colors">
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border-l-4 border-red-500 bg-red-50 p-4 text-red-700">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <ul class="list-inside list-disc text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form wire:submit="{{ $mode === 'Add' ? 'store' : 'update' }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="mb-2 block text-sm font-bold text-gray-700">Reference Name</label>
                                <input type="text" wire:model="name" placeholder="e.g. Standard A"
                                    class="@error('name') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="relative" x-data="{ dropdownOpen: @entangle('showChemicalDropdown') }" @click.away="dropdownOpen = false">
                                <label class="mb-2 block text-sm font-bold text-gray-700">Chemical Material</label>
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="chemicalSearch"
                                        @click="$wire.openChemicalDropdown()" placeholder="Search chemical..."
                                        class="@error('material_id') border-red-500 @else border-gray-300 @enderror w-full rounded-lg border px-4 py-2.5 pr-10 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        autocomplete="off">

                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                            :class="dropdownOpen ? 'transform rotate-180' : ''"
                                            fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>

                                    <div x-show="dropdownOpen"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-100 rounded-lg shadow-xl max-h-60 overflow-y-auto custom-scrollbar"
                                        style="display: none;">

                                        <ul class="py-1 text-base text-gray-700">
                                            @if (count($this->filteredChemicals) > 0)
                                                @foreach ($this->filteredChemicals as $chemical)
                                                    <li>
                                                        <button type="button"
                                                            wire:click="selectChemical({{ $chemical->id }}, '{{ $chemical->name }}')"
                                                            @click="dropdownOpen = false"
                                                            class="w-full px-4 py-2.5 text-sm text-left hover:bg-gray-50 hover:text-gray-700 cursor-pointer transition-colors">
                                                            {{ $chemical->name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            @elseif(empty($this->chemicalSearch))
                                                @foreach ($chemicals as $chemical)
                                                    <li>
                                                        <button type="button"
                                                            wire:click="selectChemical({{ $chemical->id }}, '{{ $chemical->name }}')"
                                                            @click="dropdownOpen = false"
                                                            class="w-full px-4 py-2.5 text-sm text-left hover:bg-gray-50 hover:text-gray-700 cursor-pointer transition-colors">
                                                            {{ $chemical->name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li>
                                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">No chemicals found</div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                @error('material_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <div class="mb-6">
                            <label class="mb-3 block text-sm font-bold text-gray-700">Select Specifications</label>

                            @if ($specifications->isEmpty())
                                <div
                                    class="p-4 bg-gray-50 rounded-lg text-sm text-gray-500 text-center border border-gray-200">
                                    No specifications data available.
                                </div>
                            @else
                                <div
                                    class="max-h-64 overflow-y-auto rounded-xl border border-gray-200 p-4 bg-gray-50/50 custom-scrollbar">
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                        @foreach ($specifications as $specification)
                                            <label
                                                class="group relative flex items-center justify-center p-2 text-sm border rounded-lg cursor-pointer select-none transition-all duration-200
                                                {{ in_array($specification->id, $selectedSpecifications)
                                                    ? 'bg-blue-600 border-blue-600 text-white shadow-md shadow-blue-200'
                                                    : 'bg-white border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600' }}">

                                                <input type="checkbox"
                                                    wire:click="toggleSpecification({{ $specification->id }})"
                                                    class="absolute opacity-0 w-0 h-0"
                                                    {{ in_array($specification->id, $selectedSpecifications) ? 'checked' : '' }}>

                                                <span class="truncate px-1 font-medium"
                                                    title="{{ $specification->name }}">
                                                    {{ $specification->name }}
                                                </span>

                                                @if (in_array($specification->id, $selectedSpecifications))
                                                    <svg class="w-3 h-3 absolute -top-1 -right-1 bg-white text-blue-600 rounded-full border border-blue-600"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @error('selectedSpecifications')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        @if (!empty($selectedSpecifications))
                            <div class="space-y-4 bg-gray-50 p-5 rounded-xl border border-gray-200 mb-6">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Set
                                    Specification Values</h4>

                                <div class="space-y-3">
                                    @foreach ($selectedSpecifications as $specId)
                                        @php $spec = $specifications->find($specId); @endphp
                                        @if ($spec)
                                            <div
                                                class="bg-white p-3 sm:p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-3">

                                                    <div class="sm:w-1/4 min-w-[120px]">
                                                        <span
                                                            class="text-sm font-bold text-gray-800 break-words">{{ $spec->name }}</span>
                                                    </div>

                                                    <div class="flex-1 flex flex-col sm:flex-row gap-2">

                                                        <div class="sm:w-1/3 min-w-[110px]" x-data="{
                                                            open: false,
                                                            operator: @entangle('specificationOperators.' . $specId).live,
                                                            labels: {
                                                                '>=': 'Greater (>=)',
                                                                '<=': 'Less (<=)',
                                                                '==': 'Equal (==)',
                                                                '-': 'Range',
                                                                'should_be': 'Should Be'
                                                            },
                                                            toggle() {
                                                                this.open = !this.open
                                                            },
                                                            close() { this.open = false }
                                                        }" @click.away="close()">
                                                            <div class="relative">
                                                                <button type="button" @click="toggle()"
                                                                    class="relative w-full py-2 px-3 text-left border rounded-lg shadow-sm cursor-pointer focus:outline-none transition-all duration-200 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 bg-white text-gray-700">
                                                                    <span class="block truncate text-xs font-medium"
                                                                        x-text="labels[operator] || 'Greater (>=)'"></span>

                                                                    <span
                                                                        class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                                                            :class="open ? 'transform rotate-180' : ''"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M19 9l-7 7-7-7"></path>
                                                                        </svg>
                                                                    </span>
                                                                </button>

                                                                <div x-show="open"
                                                                    x-transition:enter="transition ease-out duration-100"
                                                                    x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                                    x-transition:leave="transition ease-in duration-75"
                                                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                                                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                                                    style="display: none;"
                                                                    class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-lg shadow-xl max-h-60 overflow-y-auto custom-scrollbar">

                                                                    <ul class="py-1 text-base text-gray-700">
                                                                        @foreach (['>=' => 'Greater (>=)', '<=' => 'Less (<=)', '==' => 'Equal (==)', '-' => 'Range', 'should_be' => 'Should Be'] as $val => $label)
                                                                            <li>
                                                                                <button type="button"
                                                                                    @click="operator = '{{ $val }}'; close()"
                                                                                    class="group flex items-center justify-between w-full px-4 py-3 text-xs text-left hover:bg-gray-50 hover:text-gray-700 transition-colors cursor-pointer"
                                                                                    :class="operator == '{{ $val }}' ?
                                                                                        'bg-indigo-50 text-gray-700 font-semibold' :
                                                                                        ''">

                                                                                    <span>{{ $label }}</span>

                                                                                    <svg x-show="operator == '{{ $val }}'"
                                                                                        class="w-4 h-4 text-gray-600"
                                                                                        fill="none" viewBox="0 0 24 24"
                                                                                        stroke="currentColor">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M5 13l4 4L19 7" />
                                                                                    </svg>
                                                                                </button>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex-1">
                                                            @if (isset($specificationOperators[$specId]) && $specificationOperators[$specId] === '-')
                                                                <div class="space-y-2">
                                                                    @if (isset($specificationRanges[$specId]))
                                                                        @foreach ($specificationRanges[$specId] as $index => $range)
                                                                            <div class="flex items-center gap-1">
                                                                                <input type="number" step="any"
                                                                                    wire:model="specificationRanges.{{ $specId }}.{{ $index }}.min"
                                                                                    placeholder="Min"
                                                                                    class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500">
                                                                                <span
                                                                                    class="text-gray-400 text-xs">-</span>
                                                                                <input type="number" step="any"
                                                                                    wire:model="specificationRanges.{{ $specId }}.{{ $index }}.max"
                                                                                    placeholder="Max"
                                                                                    class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500">

                                                                                @if ($index > 0)
                                                                                    <button type="button"
                                                                                        wire:click="removeRangeRow({{ $specId }}, {{ $index }})"
                                                                                        class="text-gray-400 hover:text-red-500">
                                                                                        <svg class="w-4 h-4"
                                                                                            fill="none"
                                                                                            viewBox="0 0 24 24"
                                                                                            stroke="currentColor">
                                                                                            <path
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M6 18L18 6M6 6l12 12" />
                                                                                        </svg>
                                                                                    </button>
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            @elseif (isset($specificationOperators[$specId]) && $specificationOperators[$specId] === 'should_be')
                                                                <input type="text"
                                                                    wire:model="specificationTextValues.{{ $specId }}"
                                                                    placeholder="Text (e.g. Clear)"
                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500">
                                                            @else
                                                                <input type="number" step="any"
                                                                    wire:model="specificationValues.{{ $specId }}"
                                                                    placeholder="Value"
                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500">
                                                            @endif
                                                            @error("specificationValues.$specId")
                                                                <span
                                                                    class="text-[10px] text-red-500">{{ $message }}</span>
                                                            @enderror
                                                            @error("specificationRanges.$specId.*")
                                                                <span class="text-[10px] text-red-500">Invalid range</span>
                                                            @enderror
                                                        </div>

                                                        <div class="sm:w-20 w-full">
                                                            <input type="text"
                                                                wire:model="specificationUnits.{{ $specId }}"
                                                                placeholder="Unit"
                                                                class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs bg-gray-50 text-gray-600 focus:ring-2 focus:ring-blue-500 text-center">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-8 flex justify-end gap-3 border-t pt-5">
                            <button type="button" wire:click="close{{ $mode }}Modal()"
                                class="px-5 py-2.5 rounded-lg bg-white border border-gray-300 text-gray-700 font-medium text-sm hover:bg-gray-50 transition-all shadow-sm cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                <span wire:loading.remove>{{ $mode === 'Add' ? 'Save Data' : 'Update Data' }}</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        @endif
    @endforeach

</div>
