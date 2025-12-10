<div>
    <div class="flex flex-col">
        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Chemicals</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage chemical categories and items</p>
                </div>
                <div>
                    <button wire:click="openAddModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer
                           {{ $categories->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $categories->isEmpty() ? 'disabled' : '' }}
                        title="{{ $categories->isEmpty() ? 'Tambahkan kategori terlebih dahulu' : 'Tambah Data Baru' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Chemical
                    </button>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 2000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 2000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                {{ session('error') }}
            </div>
        @endif

    <!-- Table Section -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">

        <!-- Categories warning -->
        @if ($categories->isEmpty())
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
                            Tidak ada kategori yang tersedia. Silakan tambahkan kategori chemical terlebih dahulu
                            sebelum menambah data.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Table Container -->
        <div class="overflow-x-auto rounded-t-xl table-container">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                            NO
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chemical Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Internal Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category Name</th>
                        <th
                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($chemicals as $index => $chemical)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $chemicals->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $chemical->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $chemical->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $chemical->category->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end space-x-2">
                                    <button
                                        wire:click="openEditModal({{ $chemical->id }}, '{{ addslashes($chemical->name) }}', '{{ addslashes($chemical->code) }}', '{{ $chemical->category_id }}')"
                                        class="inline-flex items-center px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $chemical->id }})"
                                        wire:confirm="Are you sure you want to delete this chemical?"
                                        class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No chemicals found</h3>
                                    <p class="text-sm text-gray-500 mb-4">Get started by adding your first chemical
                                    </p>
                                    <button wire:click="openAddModal()"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer
                                                       {{ $categories->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $categories->isEmpty() ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Chemical
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if ($chemicals->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-xl">
                <div class="flex items-center justify-between">
                    <!-- Desktop Results Info -->
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $chemicals->firstItem() ?? 0 }}</span>-<span
                                class="font-medium">{{ $chemicals->lastItem() ?? 0 }}</span> of <span
                                class="font-medium">{{ $chemicals->total() }}</span> chemicals
                        </p>
                    </div>

                    <!-- Mobile Results Info -->
                    <div class="sm:hidden">
                        <p class="text-sm text-gray-700">
                            Page {{ $chemicals->currentPage() }} of {{ $chemicals->lastPage() }}
                        </p>
                    </div>

                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($chemicals->onFirstPage())
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
                                $currentPage = $chemicals->currentPage();
                                $lastPage = $chemicals->lastPage();
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
                        @if ($chemicals->hasMorePages())
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

    <!-- Add Modal -->
    @if ($isAddModalOpen)
        <div class="fixed inset-0 bg-gray-900/75 p-4 flex items-center justify-center z-50">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-2xl font-bold">Add New Chemical</h3>
                    <button wire:click="closeAddModal()" class="p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6">
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

                    <form wire:submit="store">
                        <div class="mb-5">
                            <label for="add-name" class="block text-sm font-bold mb-2">Chemical Name</label>
                            <input type="text" id="add-name" wire:model="name"
                                class="shadow-sm border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="add-code" class="block text-sm font-bold mb-2">Internal Code</label>
                            <input type="text" id="add-code" wire:model="code"
                                placeholder="e.g., CH-001, CH-ACID-01"
                                class="shadow-sm border @error('code') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4"
                                required>
                            @error('code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5 cursor-pointer relative">
                            <label for="add-category" class="block text-sm font-bold mb-2">Chemical Category</label>
                            <div class="relative">
                                <select id="add-category" wire:model="category_id"
                                    class="shadow-sm border @error('category_id') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 pr-10 cursor-pointer appearance-none
                                       {{ $categories->isEmpty() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    {{ $categories->isEmpty() ? 'disabled' : '' }} required>
                                    @if ($categories->isEmpty())
                                        <option value="">Tidak ada kategori tersedia</option>
                                    @else
                                        <option value="" hidden>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <!-- Custom dropdown icon -->
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end pt-5 border-t mt-6">
                            <button type="button" wire:click="closeAddModal()"
                                class="px-6 py-2 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 mr-3 cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="store"
                                class="px-6 py-2 rounded-lg text-white bg-blue-500 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer
                                   {{ $categories->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $categories->isEmpty() ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="store">Save Chemical</span>
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
        <div class="fixed inset-0 bg-gray-900/75 p-4 flex items-center justify-center z-50">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-2xl font-bold">Edit Chemical</h3>
                    <button wire:click="closeEditModal()" class="p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6">
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

                    <form wire:submit="update">
                        <div class="mb-5">
                            <label for="edit-name" class="block text-sm font-bold mb-2">Chemical Name</label>
                            <input type="text" id="edit-name" wire:model="name"
                                class="shadow-sm border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="edit-code" class="block text-sm font-bold mb-2">Internal Code</label>
                            <input type="text" id="edit-code" wire:model="code"
                                placeholder="e.g., CH-001, CH-ACID-01"
                                class="shadow-sm border @error('code') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-text"
                                required>
                            @error('code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5 cursor-pointer relative">
                            <label for="edit-category" class="block text-sm font-bold mb-2">Chemical Category</label>
                            <div class="relative">
                                <select id="edit-category" wire:model="category_id"
                                    class="shadow-sm border @error('category_id') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 pr-10 cursor-pointer appearance-none"
                                    required>
                                    <option value="" hidden>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <!-- Custom dropdown icon -->
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end pt-5 border-t mt-6">
                            <button type="button" wire:click="closeEditModal()"
                                class="px-6 py-2 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 mr-3 cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="update"
                                class="px-6 py-2 rounded-lg text-white bg-blue-500 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                <span wire:loading.remove wire:target="update">Update Chemical</span>
                                <span wire:loading wire:target="update">Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
