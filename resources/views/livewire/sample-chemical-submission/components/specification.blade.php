<div>
    <div class="flex flex-col" x-data="{ showMessage: @entangle('showMessage') }" x-init="
        $watch('showMessage', value => {
            if (value) {
                setTimeout(() => { showMessage = false; }, 2000);
            }
        });
    ">
        <!-- Flash Message -->
        @if (session()->has('success') || session()->has('error'))
            <div x-show="showMessage" x-transition class="mb-4" x-init="showMessage = true">
                @if (session()->has('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Chemical Specification</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage and define chemical analysis specifications</p>
                </div>
                <div>
                    <button wire:click="openAddModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Specification
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden flex flex-col">
            <!-- Table Container -->
            <div class="overflow-x-auto rounded-t-xl table-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                NO</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Specification Name</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                                Used by Chemical References</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($specifications as $index => $specification)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $specifications->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $specification->name }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    @if ($specification->chemical_reference_manyto_many_count > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            {{ $specification->chemical_reference_manyto_many_count }} reference(s)
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8v4m0 0V9m0 0h4m-4 0H8" />
                                            </svg>
                                            Not used
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="openEditModal({{ $specification->id }})"
                                            class="inline-flex items-center px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $specification->id }})"
                                            wire:confirm="Are you sure you want to delete this specification?"
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md transition-colors duration-150
                                                   {{ $specification->chemical_reference_manyto_many_count > 0
                                                       ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                       : 'bg-red-100 hover:bg-red-200 text-red-700 cursor-pointer' }}"
                                            {{ $specification->chemical_reference_manyto_many_count > 0 ? 'title=Cannot delete: Specification is being used by chemical references' : '' }}
                                            {{ $specification->chemical_reference_manyto_many_count > 0 ? 'disabled' : '' }}>
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
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No specifications found</h3>
                                        <p class="text-sm text-gray-500 mb-4">Get started by adding your first chemical analysis
                                            specification</p>
                                        <button wire:click="openAddModal()"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Add Specification
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if ($specifications->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-xl">
                    <div class="flex items-center justify-between">
                        <!-- Desktop Results Info -->
                        <div class="hidden sm:block">
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $specifications->firstItem() ?? 0 }}</span>-<span
                                    class="font-medium">{{ $specifications->lastItem() ?? 0 }}</span> of <span
                                    class="font-medium">{{ $specifications->total() }}</span> specifications
                            </p>
                        </div>

                        <!-- Mobile Results Info -->
                        <div class="sm:hidden">
                            <p class="text-sm text-gray-700">
                                Page {{ $specifications->currentPage() }} of {{ $specifications->lastPage() }}
                            </p>
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center space-x-2">
                            {{-- Previous Page Link --}}
                            @if ($specifications->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <span class="hidden sm:inline">Previous</span>
                                </span>
                            @else
                                <button wire:click="previousPage"
                                    class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <span class="hidden sm:inline">Previous</span>
                                </button>
                            @endif

                            {{-- Page Numbers (Desktop Only) --}}
                            <div class="hidden sm:flex items-center space-x-1">
                                @php
                                    $currentPage = $specifications->currentPage();
                                    $lastPage = $specifications->lastPage();
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
                            @if ($specifications->hasMorePages())
                                <button wire:click="nextPage"
                                    class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    <span class="hidden sm:inline">Next</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @else
                                <span
                                    class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                                    <span class="hidden sm:inline">Next</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
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
                        <h3 class="text-2xl font-bold">Add New Specification</h3>
                        <button wire:click="closeAddModal()"
                            class="p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
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
                                <label for="add-name" class="block text-sm font-bold mb-2">Specification Name</label>
                                <input type="text" id="add-name" wire:model="name"
                                    class="shadow-sm border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4"
                                    placeholder="Enter specification name" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-5 border-t mt-6">
                                <button type="button" wire:click="closeAddModal()"
                                    class="px-6 py-2 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 mr-3 cursor-pointer">
                                    Cancel
                                </button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="store"
                                    class="px-6 py-2 rounded-lg text-white bg-blue-500 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                    <span wire:loading.remove wire:target="store">Save Specification</span>
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
                        <h3 class="text-2xl font-bold">Edit Specification</h3>
                        <button wire:click="closeEditModal()"
                            class="p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
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
                                <label for="edit-name" class="block text-sm font-bold mb-2">Specification Name</label>
                                <input type="text" id="edit-name" wire:model="name"
                                    class="shadow-sm border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4"
                                    placeholder="Enter specification name" required>
                                @error('name')
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
                                    <span wire:loading.remove wire:target="update">Update Specification</span>
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
