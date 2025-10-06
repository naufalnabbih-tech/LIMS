<div>
    <div class="flex flex-col">
        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">User Management</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage system users, their roles, and permissions</p>
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
                               placeholder="Search users..." 
                               class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Add Button -->
                    <button wire:click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add User
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
                            NO
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($user->role && $user->role->name === 'admin') bg-red-100 text-red-800
                                    @elseif($user->role && $user->role->name === 'manager') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ $user->role ? $user->role->display_name : 'No Role' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end space-x-2">
                                    <button wire:click="openEditModal({{ $user->id }})" 
                                            class="inline-flex items-center px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="delete({{ $user->id }})" 
                                                wire:confirm="Are you sure you want to delete this user?"
                                                class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    @endif
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
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                                    <p class="text-sm text-gray-500 mb-4">Get started by adding your first user</p>
                                    <button wire:click="openCreateModal"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add User
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if ($users->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-xl">
                <div class="flex items-center justify-between">
                    <!-- Desktop Results Info -->
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>-<span
                                class="font-medium">{{ $users->lastItem() ?? 0 }}</span> of <span
                                class="font-medium">{{ $users->total() }}</span> users
                        </p>
                    </div>

                    <!-- Mobile Results Info -->
                    <div class="sm:hidden">
                        <p class="text-sm text-gray-700">
                            Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                        </p>
                    </div>

                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($users->onFirstPage())
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
                                $currentPage = $users->currentPage();
                                $lastPage = $users->lastPage();
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
                        @if ($users->hasMorePages())
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

    <!-- Add/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/75 p-4 flex items-center justify-center z-50">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-2xl font-bold">{{ $isEditing ? 'Edit User' : 'Add New User' }}</h3>
                    <button wire:click="closeModal" class="p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6">
                    <!-- Session Error Display -->
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

                    <!-- Validation Error Display -->
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit="save">
                            
                        <div class="mb-5">
                            <label for="name" class="block text-sm font-bold mb-2">Name</label>
                            <input type="text" id="name" wire:model="name"
                                class="shadow-sm border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="email" class="block text-sm font-bold mb-2">Email</label>
                            <input type="email" id="email" wire:model="email"
                                class="shadow-sm border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5 cursor-pointer relative">
                            <label for="role_id" class="block text-sm font-bold mb-2">Role</label>
                            <div class="relative">
                                <select id="role_id" wire:model="role_id"
                                    class="shadow-sm border @error('role_id') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 pr-10 cursor-pointer appearance-none"
                                    required>
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                    @endforeach
                                </select>
                                <!-- Custom dropdown icon -->
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('role_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="password" class="block text-sm font-bold mb-2">
                                Password {{ $isEditing ? '(leave blank to keep current)' : '' }}
                            </label>
                            <input type="password" id="password" wire:model="password"
                                class="shadow-sm border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                {{ !$isEditing ? 'required' : '' }}>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if(!$isEditing || !empty($password))
                        <div class="mb-5">
                            <label for="password_confirmation" class="block text-sm font-bold mb-2">Confirm Password</label>
                            <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                class="shadow-sm border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                {{ !$isEditing ? 'required' : '' }}>
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                        </div>

                        <div class="flex justify-end pt-5 border-t mt-6">
                            <button type="button" wire:click="closeModal"
                                class="px-6 py-2 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 mr-3 cursor-pointer">
                                Cancel
                            </button>
                            <button type="button" wire:click="testSave"
                                class="px-6 py-2 rounded-lg text-yellow-700 bg-yellow-100 hover:bg-yellow-200 mr-3 cursor-pointer">
                                Test
                            </button>
                            <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save"
                                class="px-6 py-2 rounded-lg text-white bg-blue-500 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Update User' : 'Save User' }}</span>
                                <span wire:loading wire:target="save">{{ $isEditing ? 'Updating...' : 'Saving...' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
