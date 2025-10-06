<div>
    <div class="flex flex-col">
        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Role Management</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage system roles and permissions</p>
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
                               placeholder="Search roles..." 
                               class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Add Button -->
                    <button wire:click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Role
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Display Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($roles as $index => $role)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $roles->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $role->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $role->display_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="max-w-xs truncate" title="{{ $role->description }}">
                                    {{ $role->description ?? 'No description' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $role->users_count ?? $role->users()->count() }} users
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <button wire:click="toggleStatus({{ $role->id }})" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full transition-colors duration-150 cursor-pointer
                                        @if($role->is_active) bg-green-100 text-green-800 hover:bg-green-200
                                        @else bg-red-100 text-red-800 hover:bg-red-200
                                        @endif">
                                    @if($role->is_active)
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Active
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 10.586l2.707-2.707a1 1 0 00-1.414-1.414L10 7.879 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Inactive
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end space-x-2">
                                    <button wire:click="openEditModal({{ $role->id }})" 
                                            class="inline-flex items-center px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded-md transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                    @if(!in_array($role->name, ['admin', 'manager', 'user']) && $role->users()->count() == 0)
                                        <button wire:click="delete({{ $role->id }})" 
                                                wire:confirm="Are you sure you want to delete this role?"
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
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No roles found</h3>
                                    <p class="text-sm text-gray-500 mb-4">Get started by adding your first role</p>
                                    <button wire:click="openCreateModal"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Role
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if ($roles->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-xl">
                <div class="flex items-center justify-between">
                    <!-- Desktop Results Info -->
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $roles->firstItem() ?? 0 }}</span>-<span
                                class="font-medium">{{ $roles->lastItem() ?? 0 }}</span> of <span
                                class="font-medium">{{ $roles->total() }}</span> roles
                        </p>
                    </div>

                    <!-- Mobile Results Info -->
                    <div class="sm:hidden">
                        <p class="text-sm text-gray-700">
                            Page {{ $roles->currentPage() }} of {{ $roles->lastPage() }}
                        </p>
                    </div>

                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($roles->onFirstPage())
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
                                $currentPage = $roles->currentPage();
                                $lastPage = $roles->lastPage();
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
                        @if ($roles->hasMorePages())
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
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl p-8">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-2xl font-bold">{{ $isEditing ? 'Edit Role' : 'Add New Role' }}</h3>
                    <button wire:click="closeModal" class="p-2 rounded-full hover:bg-gray-100 cursor-pointer">
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

                    <form wire:submit="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-bold mb-2">Role Name</label>
                                <input type="text" id="name" wire:model="name"
                                    class="shadow-sm border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                    required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Display Name -->
                            <div>
                                <label for="display_name" class="block text-sm font-bold mb-2">Display Name</label>
                                <input type="text" id="display_name" wire:model="display_name"
                                    class="shadow-sm border @error('display_name') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                    required>
                                @error('display_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-5">
                            <label for="description" class="block text-sm font-bold mb-2">Description</label>
                            <textarea id="description" wire:model="description"
                                class="shadow-sm border @error('description') border-red-500 @else border-gray-300 @enderror rounded-lg w-full py-3 px-4 cursor-pointer"
                                rows="3"></textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div class="mb-5">
                            <label class="block text-sm font-bold mb-2">Permissions</label>
                            <div class="border border-gray-300 rounded-lg">
                                <!-- Select/Deselect All -->
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">Select Permissions</span>
                                        <div class="flex space-x-2">
                                            <button type="button" 
                                                    wire:click="selectAllPermissions"
                                                    class="text-xs px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded cursor-pointer">
                                                Select All
                                            </button>
                                            <button type="button" 
                                                    wire:click="deselectAllPermissions"
                                                    class="text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded cursor-pointer">
                                                Deselect All
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Scrollable Permissions Container -->
                                <div class="max-h-80 overflow-y-auto p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @php
                                            $groupedPermissions = [
                                                'System Management' => [
                                                    'manage_users' => 'Manage Users',
                                                    'manage_roles' => 'Manage Roles',
                                                    'view_users' => 'View Users',
                                                    'manage_settings' => 'Manage Settings',
                                                ],
                                                'Raw Materials' => [
                                                    'manage_raw_materials' => 'Manage Raw Materials',
                                                    'view_raw_materials' => 'View Raw Materials',
                                                    'manage_categories' => 'Manage Raw Material Categories',
                                                    'view_categories' => 'View Raw Material Categories',
                                                ],
                                                'References & Specifications' => [
                                                    'manage_references' => 'Manage References',
                                                    'view_references' => 'View References',
                                                    'manage_specifications' => 'Manage Specifications',
                                                    'view_specifications' => 'View Specifications',
                                                ],
                                                'Sample Management' => [
                                                    'manage_samples' => 'Manage Sample Submissions',
                                                    'view_samples' => 'View Sample Submissions',
                                                    'manage_sample_analysis' => 'Manage Sample Analysis',
                                                    'view_sample_analysis' => 'View Sample Analysis',
                                                ],
                                                'Instruments' => [
                                                    'manage_instruments' => 'Manage Instruments',
                                                    'view_instruments' => 'View Instruments',
                                                    'manage_instrument_conditions' => 'Manage Instrument Conditions',
                                                    'view_instrument_conditions' => 'View Instrument Conditions',
                                                ],
                                                'Thermohygrometers' => [
                                                    'manage_thermohygrometers' => 'Manage Thermohygrometers',
                                                    'view_thermohygrometers' => 'View Thermohygrometers',
                                                    'manage_thermohygrometer_conditions' => 'Manage Thermohygrometer Conditions',
                                                    'view_thermohygrometer_conditions' => 'View Thermohygrometer Conditions',
                                                ],
                                                'Reports & Analytics' => [
                                                    'view_reports' => 'View Reports',
                                                    'view_analysis_reports' => 'View Analysis Reports',
                                                    'view_audit_reports' => 'View Audit Reports',
                                                    'export_reports' => 'Export Reports',
                                                ],
                                                'Dashboard & Profile' => [
                                                    'view_dashboard' => 'View Dashboard',
                                                    'manage_profile' => 'Manage Own Profile',
                                                ],
                                            ];
                                        @endphp
                                        
                                        @foreach($groupedPermissions as $groupName => $groupPermissions)
                                            <div class="mb-4">
                                                <h4 class="text-sm font-semibold text-gray-800 mb-2 pb-1 border-b border-gray-200">{{ $groupName }}</h4>
                                                <div class="space-y-2">
                                                    @foreach($groupPermissions as $permission => $label)
                                                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded">
                                                            <input type="checkbox" 
                                                                   wire:model="permissions" 
                                                                   value="{{ $permission }}"
                                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0">
                                                            <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @error('permissions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       wire:model="is_active"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0">
                                <span class="ml-2 text-sm font-bold">Active Role</span>
                            </label>
                        </div>

                        <div class="flex justify-end pt-5 border-t mt-6">
                            <button type="button" wire:click="closeModal"
                                class="px-6 py-2 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 mr-3 cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="px-6 py-2 rounded-lg text-white bg-blue-500 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Update Role' : 'Save Role' }}</span>
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
