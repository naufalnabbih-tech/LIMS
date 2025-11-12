<div>
    <div class="flex flex-col">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2"
                class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Raw Material Sample Submission</h2>
                    <p class="text-sm text-gray-600 mt-1">Submit and manage raw material samples for testing</p>
                </div>
                <div>
                    @if (!$showForm)
                        <button wire:click="showCreateForm"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                            {{-- <x-icon name="plus" class="w-4 h-4 mr-2"></x-icon> --}}
                            Submit Sample
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submission Form Modal -->
        @if ($showForm)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
                x-data="{ show: true }" x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click.self="$wire.hideForm()">

                <div class="relative top-4 mx-auto my-4 w-11/12 md:w-4/5 lg:w-3/5 xl:w-1/2 max-w-4xl shadow-2xl rounded-2xl bg-white overflow-hidden max-h-[calc(100vh-2rem)]"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">

                    <!-- Modal Header with Gradient -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 flex-shrink-0">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white">Sample Submission Form</h3>
                                    <p class="text-blue-100 text-sm">Submit raw material sample for laboratory analysis
                                    </p>
                                </div>
                            </div>
                            <button type="button" wire:click="hideForm"
                                class="text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm p-2 transition-all duration-200 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Form -->
                    <div class="overflow-y-auto flex-1 max-h-[calc(100vh-10rem)]">
                        <div class="px-6 py-6 bg-gray-50/30">
                            <!-- Error Display -->
                            @if ($errors->any())
                                <div class="mb-6 mx-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <h4 class="text-sm font-medium text-red-800">Please correct the following
                                            errors:</h4>
                                    </div>
                                    <ul class="text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="mb-6 mx-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form wire:submit.prevent="submit" id="submission-form">

                                <!-- Sample Information Section -->
                                <div class="mb-8">
                                    <div class="flex items-center space-x-2 mb-6">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Sample Information</h4>
                                            <p class="text-sm text-gray-500">Basic details about the material sample
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Category Selection -->
                                        <div class="space-y-2">
                                            <label for="category_id"
                                                class="block text-sm font-semibold text-gray-700">
                                                <span class="flex items-center space-x-1">
                                                    <span>Material Category</span>
                                                    <span class="text-red-500">*</span>
                                                </span>
                                            </label>
                                            <select wire:model.live="category_id" id="category_id" required
                                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('category_id') border-red-500 ring-red-200 @enderror">
                                                <option value="">Choose material category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $message }}</span>
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Raw Material Selection -->
                                        <div class="space-y-2">
                                            <label for="raw_mat_id" class="block text-sm font-semibold text-gray-700">
                                                <span class="flex items-center space-x-1">
                                                    <span>Raw Material</span>
                                                    <span class="text-red-500">*</span>
                                                </span>
                                            </label>
                                            <select wire:model.live="raw_mat_id" id="raw_mat_id" required
                                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('raw_mat_id') border-red-500 ring-red-200 @enderror @if (empty($category_id)) bg-gray-100 cursor-not-allowed @endif"
                                                @if (empty($category_id)) disabled @endif>
                                                <option value="">Choose raw material</option>
                                                @foreach ($rawMaterials as $rawMat)
                                                    <option value="{{ $rawMat->id }}">{{ $rawMat->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('raw_mat_id')
                                                <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $message }}</span>
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Reference Selection -->
                                        <div class="space-y-2 md:col-span-2">
                                            <label for="reference_id"
                                                class="block text-sm font-semibold text-gray-700">
                                                <span class="flex items-center space-x-1">
                                                    <span>Testing Reference</span>
                                                    <span class="text-red-500">*</span>
                                                </span>
                                            </label>
                                            <select wire:model="reference_id" id="reference_id" required
                                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('reference_id') border-red-500 ring-red-200 @enderror @if (empty($raw_mat_id)) bg-gray-100 cursor-not-allowed @endif"
                                                @if (empty($raw_mat_id)) disabled @endif>
                                                <option value="">Choose testing reference</option>
                                                @foreach ($references as $reference)
                                                    <option value="{{ $reference->id }}">{{ $reference->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('reference_id')
                                                <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $message }}</span>
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Supplier Information Section -->
                                <div class="mb-8">
                                    <div class="flex items-center space-x-2 mb-6">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 21V3a2 2 0 012-2h6a2 2 0 012 2v18M7 21h10" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Supplier Information</h4>
                                            <p class="text-sm text-gray-500">Details about the material source and
                                                batch</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Supplier -->
                                        <div class="space-y-2">
                                            <label for="supplier" class="block text-sm font-semibold text-gray-700">
                                                <span class="flex items-center space-x-1">
                                                    <span>Supplier Name</span>
                                                    <span class="text-red-500">*</span>
                                                </span>
                                            </label>
                                            <div class="relative">
                                                <input type="text" wire:model="supplier" id="supplier" required
                                                    class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('supplier') border-red-500 ring-red-200 @enderror"
                                                    placeholder="Enter supplier company name">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('supplier')
                                                <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $message }}</span>
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Batch Lot -->
                                        <div class="space-y-2">
                                            <label for="batch_lot" class="block text-sm font-semibold text-gray-700">
                                                <span class="flex items-center space-x-1">
                                                    <span>Batch/Lot Number</span>
                                                    <span class="text-red-500">*</span>
                                                </span>
                                            </label>
                                            <div class="relative">
                                                <input type="text" wire:model="batch_lot" id="batch_lot" required
                                                    class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('batch_lot') border-red-500 ring-red-200 @enderror"
                                                    placeholder="Enter batch or lot number">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('batch_lot')
                                                <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $message }}</span>
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Vehicle/Container Number -->
                                        <div class="space-y-2 md:col-span-2">
                                            <label for="vehicle_container_number"
                                                class="block text-sm font-semibold text-gray-700">
                                                <span class="flex items-center space-x-1">
                                                    <span>Vehicle/Container Number</span>
                                                    <span class="text-red-500">*</span>
                                                </span>
                                            </label>
                                            <div class="relative">
                                                <input type="text" wire:model="vehicle_container_number"
                                                    id="vehicle_container_number" required
                                                    class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('vehicle_container_number') border-red-500 ring-red-200 @enderror"
                                                    placeholder="Enter vehicle or container identification number">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('vehicle_container_number')
                                                <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $message }}</span>
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Submission Details Section -->
                                <div class="mb-8">
                                    <div class="flex items-center space-x-2 mb-6">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h6m-6 0v11a2 2 0 002 2h4a2 2 0 002-2V7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Submission Details</h4>
                                            <p class="text-sm text-gray-500">Date, time and documentation information
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- CoA Checkbox -->
                                        <div class="space-y-2 md:col-span-2">
                                            <div
                                                class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" wire:model.live="has_coa" id="has_coa"
                                                        class="w-5 h-5 text-blue-600 bg-white border-2 border-blue-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                                                </div>
                                                <div class="ml-3">
                                                    <label for="has_coa"
                                                        class="text-sm font-semibold text-blue-900 cursor-pointer">
                                                        Certificate of Analysis (CoA) Available
                                                    </label>
                                                    <p class="text-xs text-blue-700 mt-1">Check this if you have a CoA
                                                        document to upload</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submission Date -->
                                        <div class="space-y-2">
                                            <label for="submission_date"
                                                class="block text-sm font-semibold text-gray-700">
                                                <span class="flex items-center space-x-1">
                                                    <span>Submission Date</span>
                                                    <span class="text-red-500">*</span>
                                                </span>
                                            </label>
                                            <div class="relative">
                                                <input type="date" wire:model="submission_date"
                                                    id="submission_date" required
                                                    class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('submission_date') border-red-500 ring-red-200 @enderror">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h6m-6 0v11a2 2 0 002 2h4a2 2 0 002-2V7" />
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('submission_date')
                                                <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $message }}</span>
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Submission Time -->
                                        <div class="space-y-2">
                                            <label for="submission_time"
                                                class="block text-sm font-semibold text-gray-700">
                                                Submission Time (Auto-Generated)
                                            </label>
                                            <div class="relative">
                                                <input type="time" wire:model="submission_time"
                                                    id="submission_time" readonly
                                                    class="w-full px-4 py-3 pl-10 bg-gray-100 border border-gray-300 rounded-xl text-gray-600 cursor-not-allowed">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>Time will be set to current time when submitted</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- CoA File Upload Section (shows when has_coa is checked) -->
                                @if ($has_coa)
                                    <div class="mb-8">
                                        <div class="flex items-center space-x-2 mb-6">
                                            <div
                                                class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-orange-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">Certificate of Analysis
                                                </h4>
                                                <p class="text-sm text-gray-500">Upload your CoA document</p>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="coa_file" class="block text-sm font-semibold text-gray-700">
                                                <span class="flex items-center space-x-1">
                                                    <span>Upload CoA File</span>
                                                    <span class="text-red-500">*</span>
                                                </span>
                                                <span class="text-xs text-gray-500 font-normal block mt-1">(PDF, DOC,
                                                    DOCX, JPG, JPEG, PNG - Max 10MB)</span>
                                            </label>
                                            <div class="relative">
                                                <input type="file" wire:model="coa_file" id="coa_file"
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                    class="w-full px-4 py-3 bg-white border-2 border-dashed border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('coa_file') border-red-500 @enderror
                                                       file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all duration-200">
                                            </div>
                                            @error('coa_file')
                                                <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $message }}</span>
                                                </p>
                                            @enderror
                                            @if ($coa_file)
                                                <div class="mt-3 p-4 bg-green-50 border border-green-200 rounded-xl">
                                                    <div class="flex items-center text-sm text-green-700">
                                                        <div
                                                            class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                            <svg class="w-4 h-4 text-green-600" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium">File selected successfully</p>
                                                            <p class="text-xs text-green-600">
                                                                {{ $coa_file->getClientOriginalName() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Notes Section -->
                                <div class="mb-8">
                                    <div class="flex items-center space-x-2 mb-6">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Additional Notes</h4>
                                            <p class="text-sm text-gray-500">Any additional observations or comments
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="notes" class="block text-sm font-semibold text-gray-700">
                                            Notes (Optional)
                                        </label>
                                        <textarea wire:model="notes" id="notes" rows="4"
                                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('notes') border-red-500 ring-red-200 @enderror resize-none"
                                            placeholder="Enter any additional notes, observations, or special handling instructions..."></textarea>
                                        @error('notes')
                                            <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                                    <button type="button" wire:click="hideForm"
                                        class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span>Cancel</span>
                                    </button>
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" wire:loading.remove>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" wire:loading>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.49 8.49l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.49-8.49l2.83-2.83" />
                                        </svg>
                                        <span wire:loading.remove>Submit Sample</span>
                                        <span wire:loading>Submitting...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Analysis Form Modal -->
        @if ($showAnalysisForm)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                @click.self="$wire.hideAnalysisForm()">

                <div class="relative top-4 mx-auto my-4 w-11/12 md:w-4/5 lg:w-3/5 xl:w-1/2 max-w-4xl shadow-2xl rounded-2xl bg-white overflow-hidden max-h-[calc(100vh-2rem)]"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">

                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-5 flex-shrink-0">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white">Start Analysis</h3>
                                    <p class="text-green-100 text-sm">Begin laboratory analysis process</p>
                                </div>
                            </div>
                            <button type="button" wire:click="hideAnalysisForm"
                                class="text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm p-2 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="overflow-y-auto flex-1 max-h-[calc(100vh-10rem)]">
                        <div class="px-6 py-6">
                            @if ($selectedAnalysisSample)
                                <!-- Sample Information Display -->
                                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Sample Information</h4>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Raw
                                                Material</label>
                                            <div class="text-base font-medium text-gray-900">
                                                {{ $selectedAnalysisSample->rawMaterial->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                            <div class="text-base font-medium text-gray-900">
                                                {{ $selectedAnalysisSample->category->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">Batch/Lot</label>
                                            <div class="text-base font-medium text-gray-900">
                                                {{ $selectedAnalysisSample->batch_lot }}
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Submission
                                                Time</label>
                                            <div class="text-base font-medium text-gray-900">
                                                {{ $selectedAnalysisSample->submission_time->format('M d, Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @error('analysisMethod')
                                    <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 p-3 rounded-r-lg">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium">{{ $message }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @enderror

                                <!-- Analysis Method Selection -->
                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-900 mb-4">Analysis
                                        Method</label>
                                    <div class="space-y-3">
                                        <div class="flex items-start space-x-3">
                                            <input wire:model.live="analysisMethod" type="radio" id="individual"
                                                value="individual"
                                                class="mt-1 w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 focus:ring-2">
                                            <div class="flex-1">
                                                <label for="individual"
                                                    class="text-sm font-medium text-gray-900 cursor-pointer">
                                                    Individual Analysis
                                                </label>
                                                <p class="text-xs text-gray-500 mt-1">Analysis conducted by a single
                                                    analyst</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <input wire:model.live="analysisMethod" type="radio" id="joint"
                                                value="joint"
                                                class="mt-1 w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 focus:ring-2">
                                            <div class="flex-1">
                                                <label for="joint"
                                                    class="text-sm font-medium text-gray-900 cursor-pointer">
                                                    Joint Analysis
                                                </label>
                                                <p class="text-xs text-gray-500 mt-1">Analysis conducted by multiple
                                                    analysts working together</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Analyst Selection -->
                                @if ($analysisMethod === 'individual')
                                    <div class="mb-6">
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">Assigned
                                            Analyst</label>
                                        <div
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-base font-medium text-gray-900">
                                            {{ auth()->user()->name }} (Current User)
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Individual analysis will be conducted by
                                            you</p>
                                    </div>
                                @elseif ($analysisMethod === 'joint')
                                    <div class="mb-6 space-y-4">
                                        <label class="block text-sm font-semibold text-gray-900">Assigned
                                            Analysts</label>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Primary
                                                Analyst</label>
                                            <div
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-base font-medium text-gray-900">
                                                {{ auth()->user()->name }} (Current User)
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">You will be the primary analyst</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Secondary
                                                Analyst</label>
                                            <select wire:model="secondaryAnalystId"
                                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm transition-all duration-200">
                                                <option value="">Select an analyst to assist</option>
                                                @foreach ($operators as $operator)
                                                    <option value="{{ $operator->id }}">{{ $operator->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                            <button type="button" wire:click="hideAnalysisForm"
                                class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="button" wire:click="startAnalysisProcess" wire:loading.attr="disabled"
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove>Start Analysis</span>
                                <span wire:loading>Starting...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Samples List -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200" style="overflow: visible;">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Submitted Samples</h3>
                <p class="text-sm text-gray-600 mt-1">Track the status of submitted raw material samples</p>
            </div>

            <div class="overflow-x-auto" style="overflow-y: visible; overflow-x: auto;">
                <table class="min-w-full divide-y divide-gray-200" style="position: relative;">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sample Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Supplier Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Submission</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                CoA</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($samples as $sample)
                            <tr wire:key="sample-{{ $sample->id }}" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $sample->rawMaterial->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $sample->category->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">Batch: {{ $sample->batch_lot }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $sample->supplier }}</div>
                                    <div class="text-xs text-gray-500">{{ $sample->vehicle_container_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $sample->submission_time->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $sample->submission_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-400">by {{ $sample->submittedBy->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $sample->status_color }}">
                                        {{ $sample->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($sample->has_coa)
                                        <div class="flex flex-col space-y-1">
                                            <span class="inline-flex items-center text-green-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Yes
                                            </span>
                                            @if ($sample->coa_file_path)
                                                <a href="{{ Storage::url($sample->coa_file_path) }}" target="_blank"
                                                    class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 underline">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Download
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center text-red-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @php
                                        // Define status-based actions visibility
                                        $statusName = $sample->statusRelation ? $sample->statusRelation->name : ($sample->status ?? 'submitted');
                                        $canEdit = in_array($statusName, ['submitted', 'pending']);
                                        $canStartAnalysis = in_array($statusName, ['submitted', 'pending']);
                                        $canCompleteAnalysis = in_array($statusName, ['in_progress', 'analysis_started']);
                                        $canReview = in_array($statusName, ['analysis_completed', 'pending_review']);
                                        $canApprove = in_array($statusName, ['reviewed']);
                                        $canDelete = !in_array($statusName, ['approved', 'completed']);
                                    @endphp

                                    <!-- Sample Action Button -->
                                    <button
                                        @click="
                                        window.globalDropdown?.open({{ $sample->id }}, {
                                            batch: @js($sample->batch_lot ?? 'N/A'),
                                            status: @js($statusName),
                                            supplier: @js($sample->supplier ?? 'N/A'),
                                            material: @js($sample->rawMaterial->name ?? 'N/A'),
                                            buttonRect: $el.getBoundingClientRect()
                                        })
                                    "
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 hover:text-gray-800 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition-all duration-150 cursor-pointer shadow-sm hover:shadow-md"
                                        aria-label="Open sample actions">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                        <span class="font-medium">Actions</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No samples submitted</h3>
                                        <p class="text-sm text-gray-500 mb-4">Start by submitting your first raw
                                            material sample</p>
                                        @if (!$showForm)
                                            <button wire:click="showCreateForm"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 cursor-pointer">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Submit Sample
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($samples->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-xl">
                    {{ $samples->links() }}
                </div>
            @endif
        </div>
    </div>


    <!-- Sample Details Modal -->

    <!-- Edit Sample Modal -->





    <!-- Child Components -->
    <livewire:components.sample-details />
    <livewire:components.edit-sample-form />
    <livewire:components.sample-actions-dropdown />
    <livewire:components.hand-over-form />
    <livewire:components.take-over-form />

</div>

<script>
    // Global Print Label Function - Available for all components
    window.printSampleLabel = function(data) {
        console.log('Printing sample label:', data);

        if (!data || !data.sample_id) {
            console.error('Invalid label data:', data);
            alert('Error: Invalid label data received');
            return;
        }

        const printWindow = window.open('', '_blank', 'width=400,height=600');

        if (!printWindow) {
            alert('Unable to open print window. Please check if pop-ups are blocked.');
            return;
        }

        const labelHtml = `
<!DOCTYPE html>
<html>
<head>
    <title>Sample Label #${data.sample_id}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .label-container {
            border: 2px solid #000;
            padding: 15px;
            width: 300px;
            margin: 0 auto;
        }
        .label-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .label-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 5px;
        }
        .label-field {
            font-weight: bold;
        }
        .label-value {
            text-align: right;
            max-width: 150px;
            word-break: break-word;
        }
        .sample-id {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            background: #f0f0f0;
            padding: 10px;
            margin: 10px 0;
        }
        .print-button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 10px 0;
            display: block;
            width: 100%;
        }
        .print-button:hover {
            background: #2563eb;
        }
        @media print {
            body { margin: 0; }
            .label-container { border: 2px solid #000; }
            .print-button { display: none; }
        }
    </style>
</head>
<body>
    <div class="label-container">
        <button class="print-button" onclick="window.print()">🖨️ Print This Label</button>
        <div class="label-title">LABORATORY SAMPLE LABEL</div>
        <div class="sample-id">ID: #${data.sample_id}</div>
        <div class="label-row">
            <span class="label-field">Material:</span>
            <span class="label-value">${data.material_name}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Category:</span>
            <span class="label-value">${data.category_name}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Supplier:</span>
            <span class="label-value">${data.supplier}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Batch/Lot:</span>
            <span class="label-value">${data.batch_lot}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Vehicle/Container:</span>
            <span class="label-value">${data.vehicle_container}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Reference:</span>
            <span class="label-value">${data.reference}</span>
        </div>

        <div class="label-row">
            <span class="label-field">Submitted by:</span>
            <span class="label-value">${data.submitted_by}</span>
        </div>
    </div>
</body>
</html>`;

        printWindow.document.open();
        printWindow.document.write(labelHtml);
        printWindow.document.close();


        printWindow.focus();
    };

</script>
