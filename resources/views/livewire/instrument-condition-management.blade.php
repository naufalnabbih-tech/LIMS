  <div>
    <div class="flex flex-col">
        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Instrument Condition Management</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage instrument conditions, shifts, and maintenance schedules</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search"
                               type="text"
                               placeholder="Search conditions..."
                               class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Add Button -->
                    <button wire:click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Condition
                    </button>
                </div>
            </div>
        </div>

    <!-- Table Section -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <!-- Table Container -->
        <div class="overflow-x-auto rounded-t-xl table-container">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                            NO.
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shift</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($conditions as $index => $condition)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $conditions->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($condition->shift === 'Shift 1') bg-blue-100 text-blue-800
                                    @elseif($condition->shift === 'Shift 2') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ $condition->shift_display }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $condition->operator_name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $condition->time->format('H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $condition->date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end space-x-2">
                                    <button wire:click="openViewModal({{ $condition->id }})"
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </button>
                                    <button wire:click="delete({{ $condition->id }})"
                                            wire:confirm="Are you sure you want to delete this condition?"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No conditions found</h3>
                                    <p class="text-sm text-gray-500 mb-4">Get started by adding your first instrument condition</p>
                                    <button wire:click="openCreateModal"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Condition
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if ($conditions->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-xl">
                <div class="flex items-center justify-between">
                    <!-- Desktop Results Info -->
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $conditions->firstItem() ?? 0 }}</span>-<span
                                class="font-medium">{{ $conditions->lastItem() ?? 0 }}</span> of <span
                                class="font-medium">{{ $conditions->total() }}</span> conditions
                        </p>
                    </div>

                    <!-- Mobile Results Info -->
                    <div class="sm:hidden">
                        <p class="text-sm text-gray-700">
                            Page {{ $conditions->currentPage() }} of {{ $conditions->lastPage() }}
                        </p>
                    </div>

                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($conditions->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                <span class="hidden sm:inline">Previous</span>
                            </span>
                        @else
                            <button wire:click="previousPage"
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                <span class="hidden sm:inline">Previous</span>
                            </button>
                        @endif

                        {{-- Page Numbers (Desktop Only) --}}
                        <div class="hidden sm:flex items-center space-x-1">
                            @php
                                $currentPage = $conditions->currentPage();
                                $lastPage = $conditions->lastPage();
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($lastPage, $currentPage + 2);
                            @endphp

                            @if ($startPage > 1)
                                <button wire:click="gotoPage(1)"
                                    class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    1
                                </button>
                                @if ($startPage > 2)
                                    <span class="px-2 text-gray-500">...</span>
                                @endif
                            @endif

                            @for ($page = $startPage; $page <= $endPage; $page++)
                                @if ($page == $currentPage)
                                    <span
                                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-500 rounded-md">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endfor

                            @if ($endPage < $lastPage)
                                @if ($endPage < $lastPage - 1)
                                    <span class="px-2 text-gray-500">...</span>
                                @endif
                                <button wire:click="gotoPage({{ $lastPage }})"
                                    class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    {{ $lastPage }}
                                </button>
                            @endif
                        </div>

                        {{-- Next Page Link --}}
                        @if ($conditions->hasMorePages())
                            <button wire:click="nextPage"
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                <span class="hidden sm:inline">Next</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @else
                            <span
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                                <span class="hidden sm:inline">Next</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add/Edit/View Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/75 p-4 flex items-center justify-center z-50">
            <div class="relative w-full max-w-7xl bg-white rounded-2xl shadow-2xl p-8">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-2xl font-bold">
                        @if($selectedConditionId)
                            View Condition Entry
                        @else
                            Add Condition for All Instruments
                        @endif
                    </h3>
                    <button wire:click="closeModal" class="p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6">
                    <!-- Flash Messages -->
                    @if (session()->has('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.332 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Error Display -->
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Livewire Validation Alert -->
                    @if(!empty($validationErrors))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.332 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please complete all required fields</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside">
                                            @foreach($validationErrors as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($isViewing)
                        <!-- View Mode -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold mb-2">Shift</label>
                                    <p class="text-gray-900 bg-gray-50 rounded-lg py-3 px-4">{{ $shiftOptions[$shift] ?? $shift }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">Operator Name</label>
                                    <p class="text-gray-900 bg-gray-50 rounded-lg py-3 px-4">{{ $operator_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">Time</label>
                                    <p class="text-gray-900 bg-gray-50 rounded-lg py-3 px-4">{{ $time }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">Date</label>
                                    <p class="text-gray-900 bg-gray-50 rounded-lg py-3 px-4">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold mb-3">All Instrument Conditions</label>
                                <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Instrument Name
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Condition
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Description
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($instrumentConditions as $instrumentData)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $instrumentData['instrument_name'] }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                            @if($instrumentData['condition'] === 'good') bg-green-100 text-green-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ ucfirst($instrumentData['condition']) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        @if($instrumentData['description'])
                                                            {{ $instrumentData['description'] }}
                                                        @else
                                                            <span class="text-gray-400 italic">No description</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-5 border-t mt-6">
                            <button type="button" wire:click="closeModal"
                                class="px-6 py-2 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 cursor-pointer">
                                Close
                            </button>
                        </div>
                    @else
                        <!-- Add Form -->
                        <form wire:submit="save" id="conditionForm">
                            <div class="grid grid-cols-2 gap-4 mb-5">
                                <div>
                                    <label for="shift" class="block text-sm font-bold mb-2">Shift</label>
                                    <div class="relative">
                                        <select id="shift" wire:model="shift"
                                            class="shadow-sm border @error('shift') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 pr-10 cursor-pointer appearance-none"
                                            required>
                                            <option value="">Select Shift</option>
                                            @foreach($shiftOptions as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('shift')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <div class="flex items-start justify-between mb-2">
                                        <label class="block text-sm font-bold">Operators</label>
                                        <button type="button" wire:click="addOperator"
                                            class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded cursor-pointer">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Add Operator
                                        </button>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach($operators as $index => $operator)
                                            <div class="flex items-center space-x-2">
                                                @if($operator['is_current_user'] ?? false)
                                                    <!-- Current user - readonly -->
                                                    <div class="flex-1">
                                                        <input type="text" value="{{ $operator['name'] }}"
                                                            class="shadow-sm border border-gray-300 rounded-lg w-full py-2 px-3 bg-gray-50 text-gray-500 cursor-not-allowed text-sm"
                                                            readonly>
                                                    </div>
                                                    <div class="text-xs text-gray-500 px-2">You</div>
                                                @else
                                                    <!-- Operator selection dropdown -->
                                                    <div class="flex-1 relative">
                                                        <select wire:model.live="operators.{{ $index }}.id"
                                                            class="shadow-sm border @error('operators.'.$index.'.id') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-2 px-3 pr-8 cursor-pointer appearance-none text-sm"
                                                            required>
                                                            <option value="">Select Operator</option>
                                                            @foreach($availableOperators as $availableOperator)
                                                                <option value="{{ $availableOperator->id }}">{{ $availableOperator->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    @if(count($operators) > 1)
                                                        <button type="button" wire:click="removeOperator({{ $index }})"
                                                            class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded cursor-pointer">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                            @error('operators.'.$index.'.id')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        @endforeach
                                    </div>

                                    @if($isCurrentUserOperator && count($operators) == 1)
                                        <p class="text-xs text-gray-500 mt-2">You are logged in as operator. Click "Add Operator" to add additional operators for this shift.</p>
                                    @endif

                                    @error('operators')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    @error('operator_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-5">
                                <div>
                                    <label for="time" class="block text-sm font-bold mb-2">Time</label>
                                    <input type="time" id="time" wire:model.live="time"
                                        class="shadow-sm border @error('time') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">Shift will auto-update</p>
                                    @error('time')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date" class="block text-sm font-bold mb-2">Date</label>
                                    <input type="date" id="date" wire:model="date"
                                        class="shadow-sm border @error('date') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                        required>
                                    @error('date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- All Instruments Section -->
                            <div class="mb-6">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200 mb-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-lg font-semibold text-blue-900">Set Condition for All Instruments</h3>
                                            <p class="text-sm text-blue-700 mt-1">Configure the condition and notes for each instrument device</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                    <div class="max-h-96 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-10">
                                                <tr>
                                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/3 border-r border-gray-200">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM6 6v12h12V6H6zm3 3a1 1 0 112 0v6a1 1 0 11-2 0V9zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V9z"></path>
                                                            </svg>
                                                            Instrument Name
                                                        </div>
                                                    </th>
                                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/4 border-r border-gray-200">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Condition
                                                        </div>
                                                    </th>
                                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                            Description & Notes
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($instrumentConditions as $instrumentId => $instrumentData)
                                                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 border-l-4 border-transparent hover:border-blue-300"
                                                    x-data="{
                                                        condition: $wire.entangle('instrumentConditions.{{ $instrumentId }}.condition').live
                                                    }"
                                                    x-init="condition = '{{ $instrumentConditions[$instrumentId]['condition'] ?? '' }}'"
                                                    x-cloak>
                                                    <td class="px-4 py-4 text-sm font-semibold text-gray-800 align-top border-r border-gray-100">
                                                        <div class="flex items-center">
                                                            <div class="w-2 h-2 bg-blue-400 rounded-full mr-3 flex-shrink-0"></div>
                                                            <span class="truncate">{{ $instrumentData['instrument_name'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 text-sm text-gray-900 align-top border-r border-gray-100">
                                                        <div class="space-y-3">
                                                            @foreach($conditionOptions as $key => $label)
                                                                <label class="flex items-center cursor-pointer hover:bg-white hover:shadow-sm rounded-lg p-2 transition-all duration-150">
                                                                    <input type="radio"
                                                                        wire:model.live="instrumentConditions.{{ $instrumentId }}.condition"
                                                                        value="{{ $key }}"
                                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                                                    <span class="ml-3 text-sm font-medium text-gray-900 select-none">{{ $label }}</span>
                                                                    @if($key === 'good')
                                                                        <div class="ml-auto">
                                                                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                                                        </div>
                                                                    @else
                                                                        <div class="ml-auto">
                                                                            <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                                                                        </div>
                                                                    @endif
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        @error('instrumentConditions.' . $instrumentId . '.condition')
                                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td class="px-4 py-4 text-sm text-gray-900 align-top">
                                                        <div x-show="condition === 'damaged'"
                                                            x-transition.opacity
                                                            class="{{ (isset($instrumentConditions[$instrumentId]['condition']) && $instrumentConditions[$instrumentId]['condition'] === 'damaged') ? '' : 'hidden' }}"
                                                            x-cloak>
                                                            <div class="relative">
                                                                <textarea
                                                                    wire:model.live="instrumentConditions.{{ $instrumentId }}.description"
                                                                    class="shadow-sm border @error('instrumentConditions.' . $instrumentId . '.description') border-red-500 @else border-red-300 @enderror rounded-lg w-full py-2.5 px-3 text-sm resize-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-red-50"
                                                                    rows="3"
                                                                    placeholder="⚠️ Required: describe the damage in detail..."
                                                                    required></textarea>
                                                                <div class="absolute top-2 right-2">
                                                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.332 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            @error('instrumentConditions.' . $instrumentId . '.description')
                                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div x-show="condition === 'good'"
                                                            x-transition.opacity
                                                            class="{{ (isset($instrumentConditions[$instrumentId]['condition']) && $instrumentConditions[$instrumentId]['condition'] === 'good') ? '' : 'hidden' }}"
                                                            x-cloak>
                                                            <div class="relative">
                                                                <textarea
                                                                    wire:model.live="instrumentConditions.{{ $instrumentId }}.description"
                                                                    class="shadow-sm border @error('instrumentConditions.' . $instrumentId . '.description') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-2.5 px-3 text-sm resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-green-50"
                                                                    rows="3"
                                                                    placeholder="💡 Optional: add maintenance notes, observations, or comments..."></textarea>
                                                                <div class="absolute top-2 right-2">
                                                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            @error('instrumentConditions.' . $instrumentId . '.description')
                                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div x-show="!condition || (condition !== 'good' && condition !== 'damaged')"
                                                            x-transition.opacity
                                                            class="{{ (isset($instrumentConditions[$instrumentId]['condition']) && ($instrumentConditions[$instrumentId]['condition'] === 'good' || $instrumentConditions[$instrumentId]['condition'] === 'damaged')) ? 'hidden' : '' }}"
                                                            x-cloak>
                                                            <div class="flex items-center justify-center h-20 bg-gray-50 rounded-lg border border-gray-200">
                                                                <div class="text-center">
                                                                    <svg class="w-5 h-5 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                    <span class="text-gray-400 italic text-xs">Select condition first</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6 border-t border-gray-200 mt-6">
                                <button type="button" wire:click="closeModal"
                                    class="inline-flex items-center px-6 py-3 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 hover:border-gray-400 mr-4 cursor-pointer font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </button>
                                <button type="button" wire:click="validateAndSave" wire:loading.attr="disabled" wire:target="validateAndSave"
                                    class="inline-flex items-center px-8 py-3 rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <span wire:loading.remove wire:target="validateAndSave" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Save All Conditions
                                    </span>
                                    <span wire:loading wire:target="validateAndSave" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Saving...
                                    </span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
    </div>

    <style>
    [x-cloak] {
        display: none !important;
    }

    /* Prevent flash during Alpine initialization */
    [x-show] {
        transition: opacity 0.2s ease-in-out;
    }

    /* Ensure proper initial state */
    .alpine-loading [x-show] {
        opacity: 0;
    }
    </style>

    {{--
    <script>
        function validateAndSubmit() {
            const validationAlert = document.getElementById('validationAlert');
            const validationErrors = document.getElementById('validationErrors');
            const errors = [];

            // Check basic form fields
            const shift = document.getElementById('shift').value;
            const operatorName = document.getElementById('operator_name').value;
            const time = document.getElementById('time').value;
            const date = document.getElementById('date').value;

            if (!shift) errors.push('Please select a shift');
            if (!operatorName.trim()) errors.push('Please enter operator name');
            if (!time) errors.push('Please enter time');
            if (!date) errors.push('Please enter date');

            // Check if all instruments have conditions selected
            const conditionRadios = document.querySelectorAll('input[type="radio"][wire\\:model*="condition"]');
            const instrumentGroups = {};
            const instrumentNames = {};

            // Group radio buttons by instrument
            conditionRadios.forEach(radio => {
                const modelParts = radio.getAttribute('wire:model').match(/instrumentConditions\.(\d+)\.condition/);
                if (modelParts) {
                    const instrumentId = modelParts[1];
                    if (!instrumentGroups[instrumentId]) {
                        instrumentGroups[instrumentId] = [];
                        // Get instrument name from the table
                        const row = radio.closest('tr');
                        const nameCell = row.querySelector('td:first-child');
                        instrumentNames[instrumentId] = nameCell.textContent.trim();
                    }
                    instrumentGroups[instrumentId].push(radio);
                }
            });

            // Check if each instrument group has a selection
            Object.keys(instrumentGroups).forEach(instrumentId => {
                const radios = instrumentGroups[instrumentId];
                const isSelected = radios.some(radio => radio.checked);
                if (!isSelected) {
                    errors.push(`Please select condition for ${instrumentNames[instrumentId]}`);
                }
            });

            // Check if damaged instruments have descriptions
            Object.keys(instrumentGroups).forEach(instrumentId => {
                const radios = instrumentGroups[instrumentId];
                const damagedRadio = radios.find(radio => radio.value === 'damaged' && radio.checked);
                if (damagedRadio) {
                    const textarea = document.querySelector(`textarea[wire\\:model*="instrumentConditions.${instrumentId}.description"]`);
                    if (textarea && !textarea.value.trim()) {
                        errors.push(`Please describe damage for ${instrumentNames[instrumentId]}`);
                    }
                }
            });

            if (errors.length > 0) {
                // Show validation errors
                validationErrors.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
                validationAlert.classList.remove('hidden');

                // Scroll to top of modal to show error
                document.querySelector('.max-w-2xl.bg-white').scrollTop = 0;

                return false;
            } else {
                // Hide validation alert if showing
                validationAlert.classList.add('hidden');

                // Submit the form
                @this.call('save');
                return true;
            }
        }

        // Hide validation alert when user starts fixing issues
        document.addEventListener('change', function(e) {
            if (e.target.matches('select, input[type="text"], input[type="time"], input[type="date"], input[type="radio"], textarea')) {
                const validationAlert = document.getElementById('validationAlert');
                if (validationAlert && !validationAlert.classList.contains('hidden')) {
                    // Re-validate on change
                    setTimeout(() => {
                        const errors = [];
                        const shift = document.getElementById('shift')?.value;
                        const operatorName = document.getElementById('operator_name')?.value;
                        const time = document.getElementById('time')?.value;
                        const date = document.getElementById('date')?.value;

                        if (!shift) errors.push('Please select a shift');
                        if (!operatorName?.trim()) errors.push('Please enter operator name');
                        if (!time) errors.push('Please enter time');
                        if (!date) errors.push('Please enter date');

                        // Check instruments
                        const conditionRadios = document.querySelectorAll('input[type="radio"][wire\\:model*="condition"]');
                        const instrumentGroups = {};
                        const instrumentNames = {};

                        conditionRadios.forEach(radio => {
                            const modelParts = radio.getAttribute('wire:model').match(/instrumentConditions\.(\d+)\.condition/);
                            if (modelParts) {
                                const instrumentId = modelParts[1];
                                if (!instrumentGroups[instrumentId]) {
                                    instrumentGroups[instrumentId] = [];
                                    const row = radio.closest('tr');
                                    const nameCell = row.querySelector('td:first-child');
                                    instrumentNames[instrumentId] = nameCell.textContent.trim();
                                }
                                instrumentGroups[instrumentId].push(radio);
                            }
                        });

                        Object.keys(instrumentGroups).forEach(instrumentId => {
                            const radios = instrumentGroups[instrumentId];
                            const isSelected = radios.some(radio => radio.checked);
                            if (!isSelected) {
                                errors.push(`Please select condition for ${instrumentNames[instrumentId]}`);
                            }
                        });

                        Object.keys(instrumentGroups).forEach(instrumentId => {
                            const radios = instrumentGroups[instrumentId];
                            const damagedRadio = radios.find(radio => radio.value === 'damaged' && radio.checked);
                            if (damagedRadio) {
                                const textarea = document.querySelector(`textarea[wire\\:model*="instrumentConditions.${instrumentId}.description"]`);
                                if (textarea && !textarea.value.trim()) {
                                    errors.push(`Please describe damage for ${instrumentNames[instrumentId]}`);
                                }
                            }
                        });

                        if (errors.length === 0) {
                            validationAlert.classList.add('hidden');
                        } else {
                            const validationErrors = document.getElementById('validationErrors');
                            validationErrors.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
                        }
                    }, 100);
                }
            }
        });
    </script>
    --}}
</div>
