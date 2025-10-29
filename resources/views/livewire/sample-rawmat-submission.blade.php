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
                                class="text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm p-2 transition-all duration-200">
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
                                        class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span>Cancel</span>
                                    </button>
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
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
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
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

    <!-- Sample Action Dropdown -->
    <div x-data="sampleDropdown()" x-init="initGlobalDropdown()" @click.away="handleClickAway()"
        @keydown.escape.window="closeDropdown()">

        <!-- Dropdown Content -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed z-[9999] w-80 rounded-xl bg-white shadow-2xl ring-1 ring-black/5 focus:outline-none border border-gray-200"
            :style="{
                top: (sampleData.position?.top || 200) + 'px',
                left: (sampleData.position?.left || 300) + 'px',
                maxHeight: '85vh',
                overflowY: 'auto'
            }"
            x-cloak>

            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900">Sample Actions</h3>
                        <p class="text-xs text-blue-600 mt-0.5">
                            <span x-text="sampleData.material || 'N/A'"></span> •
                            <span x-text="'Batch: ' + (sampleData.batch || 'N/A')"></span>
                        </p>
                    </div>
                    <button @click="closeDropdown()"
                        class="p-1.5 hover:bg-white/60 rounded-md transition-colors cursor-pointer">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Scrollable Actions Container -->
            <div class="max-h-96 overflow-y-auto custom-scrollbar">

                <!-- View & Info Actions -->
                <div class="p-3">
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">View & Information
                        </h4>
                    </div>
                    <div class="space-y-1">
                        <button @click="callLivewireMethod('viewDetails', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">View Details</span>
                                <span class="text-xs text-gray-500">View complete sample information</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Edit Actions -->
                <div class="p-3" x-show="sampleData.canEdit">
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Modify</h4>
                    </div>
                    <div class="space-y-1">
                        <button @click="callLivewireMethod('editSample', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-amber-100 group-hover:bg-amber-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Edit Sample</span>
                                <span class="text-xs text-gray-500">Modify sample details and information</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Process Actions -->
                <div class="p-3">
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Process Management
                        </h4>
                    </div>
                    <div class="space-y-1">
                        <button x-show="['pending', 'submitted'].includes(sampleData.status)"
                            @click="callLivewireMethod('openAnalysisForm', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Start Analysis</span>
                                <span class="text-xs text-gray-500">Begin laboratory analysis process</span>
                            </div>
                        </button>

                        <button x-show="['in_progress'].includes(sampleData.status)"
                            @click="callLivewireMethod('continueAnalysis', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Continue Analysis</span>
                                <span class="text-xs text-gray-500">Go to analysis laboratory page</span>
                            </div>
                        </button>

                        <!-- Submit to Hand Over Button -->
                        <button x-show="['in_progress'].includes(sampleData.status)"
                            @click="callLivewireMethod('openHandOverForm', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-yellow-100 group-hover:bg-yellow-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Submit to Hand Over</span>
                                <span class="text-xs text-gray-500">Transfer sample to another analyst</span>
                            </div>
                        </button>

                        <!-- Accept Hand Over Button -->
                        <button x-show="['submitted_to_handover'].includes(sampleData.status)"
                            @click="callLivewireMethod('openTakeOverForm', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-orange-100 group-hover:bg-orange-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Take Over</span>
                                <span class="text-xs text-gray-500">Take ownership and continue analysis</span>
                            </div>
                        </button>


                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Review & Approval Actions -->
                <div class="p-3">
                    <div class="px-2 py-1.5" x-show="['analysis_completed', 'review', 'reviewed', 'approved'].includes(sampleData.status)">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Review & Approval</h4>
                    </div>
                    <div class="space-y-1">
                        <button x-show="['analysis_completed', 'review', 'reviewed', 'approved'].includes(sampleData.status)"
                            @click="
                                callLivewireMethod('reviewResults', sampleData.sampleId);
                                setTimeout(() => {
                                    window.location.href = '/results-review/' + sampleData.sampleId;
                                }, 100);
                            "
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Review Results</span>
                                <span class="text-xs text-gray-500">Review analysis results and findings</span>
                            </div>
                        </button>

                        <button x-show="sampleData.canApprove"
                            @click="
                                if (confirm('Are you sure you want to approve this sample? This action cannot be undone.')) {
                                    callLivewireMethod('approveSample', sampleData.sampleId);
                                }
                            "
                            class="flex items-center w-full px-3 py-2.5 text-sm text-green-700 hover:bg-green-50 hover:text-green-800 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Approve Sample</span>
                                <span class="text-xs text-gray-500">Final approval and sign-off</span>
                            </div>
                        </button>

                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Delete Actions -->
                <div class="p-3" x-show="sampleData.canDelete">
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Danger Zone</h4>
                    </div>
                    <div class="space-y-1">
                        <button
                            @click="
                        if (confirm('Are you sure you want to delete this sample? This action cannot be undone.')) {
                            callLivewireMethod('deleteSample', sampleData.sampleId);
                        }
                    "
                            class="flex items-center w-full px-3 py-2.5 text-sm text-red-700 hover:bg-red-50 hover:text-red-800 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-red-100 group-hover:bg-red-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Delete Sample</span>
                                <span class="text-xs text-gray-500">Permanently remove this sample</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sample Details Modal -->
    @if ($showDetails && $selectedSample)
        @php
            // Get current status name for details modal
            $currentStatusName = $selectedSample->statusRelation ? $selectedSample->statusRelation->name : ($selectedSample->status ?? '');
        @endphp
        <div class="fixed inset-0 bg-gray-900/75 overflow-y-auto h-full w-full z-50" x-data="{ show: true }"
            x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click.self="$wire.hideDetails()">

            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-lg rounded-md bg-white"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Sample Details</h3>
                    </div>
                    <button type="button" wire:click="hideDetails"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="space-y-6">
                    <!-- Status Badge -->
                    <div class="flex items-center gap-4">
                        <span
                            class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $selectedSample->status_color }}">
                            {{ $selectedSample->status_label }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Created {{ $selectedSample->created_at->format('M d, Y \a\t H:i') }}
                        </span>
                    </div>

                    <!-- Sample Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $selectedSample->category->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Raw Material</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $selectedSample->rawMaterial->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Supplier</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $selectedSample->supplier }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Batch Lot</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $selectedSample->batch_lot }}</p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vehicle/Container Number</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $selectedSample->vehicle_container_number }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submission Time</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $selectedSample->submission_time->format('M d, Y \a\t H:i') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Entry Time</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $selectedSample->entry_time->format('M d, Y \a\t H:i') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submitted By</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $selectedSample->submittedBy->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate of Analysis -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Certificate of Analysis (CoA)</label>
                        @if ($selectedSample->has_coa && $selectedSample->coa_file_path)
                            <div class="mt-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-green-600">CoA file uploaded</span>
                                <a href="{{ asset('storage/' . $selectedSample->coa_file_path) }}" target="_blank"
                                    class="text-sm text-blue-600 hover:text-blue-800 underline">
                                    View File
                                </a>
                            </div>
                        @else
                            <div class="mt-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-500">No CoA file provided</span>
                            </div>
                        @endif
                    </div>

                    <!-- Notes -->
                    @if ($selectedSample->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md border border-gray-200">
                                <p class="text-sm text-gray-900">{{ $selectedSample->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Analysis Information Section -->
                    @if (($selectedSample->statusRelation ? $selectedSample->statusRelation->name : $selectedSample->status) !== 'pending' && $selectedSample->analysis_method)
                        @php
                            $currentStatusName = $selectedSample->statusRelation ? $selectedSample->statusRelation->name : $selectedSample->status;
                        @endphp

                            <!-- Analysis Information -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Analysis Information</label>
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Analysis Method -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Analysis Method</label>
                                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                                {{ ucfirst($selectedSample->analysis_method) }}
                                            </p>
                                        </div>

                                        <!-- Primary Analyst -->
                                        @if ($selectedSample->primary_analyst_id)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Primary Analyst</label>
                                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                                    {{ $selectedSample->primaryAnalyst->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Secondary Analyst (for joint analysis) -->
                                        @if ($selectedSample->analysis_method === 'joint' && $selectedSample->secondary_analyst_id)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Secondary Analyst</label>
                                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                                    {{ $selectedSample->secondaryAnalyst->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Analysis Started At -->
                                        @if ($selectedSample->analysis_started_at)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Analysis Started</label>
                                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                                    {{ $selectedSample->analysis_started_at->format('M d, Y \a\t H:i') }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Analysis Completed At -->
                                        @if ($selectedSample->analysis_completed_at)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Analysis Completed</label>
                                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                                    {{ $selectedSample->analysis_completed_at->format('M d, Y \a\t H:i') }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Duration Calculation -->
                                    @if ($selectedSample->analysis_started_at && $selectedSample->analysis_completed_at)
                                        <div class="mt-4 pt-4 border-t border-blue-200">
                                            <label class="block text-sm font-medium text-gray-700">Analysis Duration</label>
                                            @php
                                                $duration = $selectedSample->analysis_started_at->diff($selectedSample->analysis_completed_at);
                                                $durationText = '';
                                                if ($duration->days > 0) {
                                                    $durationText .= $duration->days . ' day' . ($duration->days > 1 ? 's' : '') . ' ';
                                                }
                                                if ($duration->h > 0) {
                                                    $durationText .= $duration->h . ' hour' . ($duration->h > 1 ? 's' : '') . ' ';
                                                }
                                                if ($duration->i > 0) {
                                                    $durationText .= $duration->i . ' minute' . ($duration->i > 1 ? 's' : '');
                                                }
                                                $durationText = trim($durationText) ?: 'Less than a minute';
                                            @endphp
                                            <p class="mt-1 text-sm font-semibold text-green-600">{{ $durationText }}</p>
                                        </div>
                                    @elseif ($selectedSample->analysis_started_at && !$selectedSample->analysis_completed_at)
                                        <div class="mt-4 pt-4 border-t border-blue-200">
                                            <label class="block text-sm font-medium text-gray-700">Analysis In Progress</label>
                                            @php
                                                $elapsed = $selectedSample->analysis_started_at->diff(now());
                                                $elapsedText = '';
                                                if ($elapsed->days > 0) {
                                                    $elapsedText .= $elapsed->days . ' day' . ($elapsed->days > 1 ? 's' : '') . ' ';
                                                }
                                                if ($elapsed->h > 0) {
                                                    $elapsedText .= $elapsed->h . ' hour' . ($elapsed->h > 1 ? 's' : '') . ' ';
                                                }
                                                if ($elapsed->i > 0) {
                                                    $elapsedText .= $elapsed->i . ' minute' . ($elapsed->i > 1 ? 's' : '');
                                                }
                                                $elapsedText = trim($elapsedText) ?: 'Just started';
                                            @endphp
                                            <p class="mt-1 text-sm font-semibold text-orange-600">Running for {{ $elapsedText }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                    @endif

                    <!-- Analysis Results Section -->
                    @if (in_array($currentStatusName, ['analysis_completed', 'reviewed', 'approved', 'rejected']) &&
                            $selectedSample->testResults->count() > 0)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Analysis Results</label>
                            <div
                                class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($selectedSample->testResults->groupBy('parameter_name') as $parameterName => $results)
                                        <div class="bg-white rounded-lg p-4 border border-green-200">
                                            <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ $parameterName }}
                                            </h4>
                                            @php
                                                $averageValue = $results->avg('test_value');
                                                $unit = $results->first()->unit ?? '';
                                                $status = $results->first()->status ?? 'unknown';
                                                $statusColor =
                                                    $status === 'pass'
                                                        ? 'text-green-600'
                                                        : ($status === 'fail'
                                                            ? 'text-red-600'
                                                            : 'text-gray-600');
                                                $statusBg =
                                                    $status === 'pass'
                                                        ? 'bg-green-100'
                                                        : ($status === 'fail'
                                                            ? 'bg-red-100'
                                                            : 'bg-gray-100');
                                            @endphp

                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-lg font-bold text-gray-900">
                                                    {{ rtrim(rtrim(number_format($averageValue, 4, '.', ''), '0'), '.') }}
                                                    {{ $unit }}
                                                </span>
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBg }} {{ $statusColor }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </div>

                                            <div class="text-xs text-gray-500">
                                                @if ($results->count() > 1)
                                                    <div class="mb-1">{{ $results->count() }} readings</div>
                                                @endif
                                                <div>Tested: {{ $results->first()->tested_at->format('M d, Y H:i') }}
                                                </div>
                                                <div>By: {{ $results->first()->testedBy->name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if ($selectedSample->notes)
                                    <div class="mt-4 pt-4 border-t border-green-200">
                                        <label class="block text-sm font-medium text-gray-700">Analysis Notes</label>
                                        <p class="mt-1 text-sm text-gray-600">{{ $selectedSample->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Status History Section -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Status History</label>
                        <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Time In</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Interval</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Analyst</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php
                                            // Get all statuses for consistent display
                                            $allStatuses = App\Models\Status::all()->keyBy('name');

                                            // Helper function to get status display data
                                            $getStatusData = function($statusName) use ($allStatuses) {
                                                $status = $allStatuses->get($statusName);
                                                if ($status) {
                                                    $colorMap = [
                                                        '#6B7280' => 'bg-gray-100 text-gray-800',
                                                        '#3B82F6' => 'bg-blue-100 text-blue-800',
                                                        '#F59E0B' => 'bg-amber-100 text-amber-800',
                                                        '#8B5CF6' => 'bg-purple-100 text-purple-800',
                                                        '#10B981' => 'bg-green-100 text-green-800',
                                                        '#EF4444' => 'bg-red-100 text-red-800',
                                                    ];
                                                    return [
                                                        'display_name' => $status->display_name,
                                                        'color_class' => $colorMap[$status->color] ?? 'bg-gray-100 text-gray-800'
                                                    ];
                                                }
                                                // Fallback for unknown statuses
                                                return [
                                                    'display_name' => ucfirst(str_replace('_', ' ', $statusName)),
                                                    'color_class' => 'bg-gray-100 text-gray-800'
                                                ];
                                            };

                                            // Build status history based on actual sample data
                                            $statusHistory = [];
                                            $counter = 1;

                                            // 1. Sample Submitted/Created
                                            $statusHistory[] = [
                                                'id' => $counter++,
                                                'time_in' => $selectedSample->created_at,
                                                'status' => $getStatusData('pending')['display_name'],
                                                'status_color' => $getStatusData('pending')['color_class'],
                                                'analyst' => $selectedSample->submittedBy->name ?? 'System',
                                                'previous_time' => null,
                                            ];

                                            // 2. Analysis Started (if applicable)
                                            if ($selectedSample->analysis_started_at) {
                                                // Determine analyst(s)
                                                $analysts = [];
                                                if ($selectedSample->primaryAnalyst) {
                                                    $analysts[] = $selectedSample->primaryAnalyst->name;
                                                }
                                                if (
                                                    $selectedSample->analysis_method === 'joint' &&
                                                    $selectedSample->secondaryAnalyst
                                                ) {
                                                    $analysts[] = $selectedSample->secondaryAnalyst->name;
                                                }
                                                $analystText = !empty($analysts)
                                                    ? implode(' & ', $analysts)
                                                    : 'Unknown Analyst';

                                                $statusHistory[] = [
                                                    'id' => $counter++,
                                                    'time_in' => $selectedSample->analysis_started_at,
                                                    'status' => $getStatusData('in_progress')['display_name'],
                                                    'status_color' => $getStatusData('in_progress')['color_class'],
                                                    'analyst' => $analystText,
                                                    'previous_time' => end($statusHistory)['time_in'],
                                                ];
                                            }

                                            // 3. Analysis Completed (if applicable)
                                            if ($selectedSample->analysis_completed_at) {
                                                // Determine analyst(s) for completion
                                                $analysts = [];
                                                if ($selectedSample->primaryAnalyst) {
                                                    $analysts[] = $selectedSample->primaryAnalyst->name;
                                                }
                                                if (
                                                    $selectedSample->analysis_method === 'joint' &&
                                                    $selectedSample->secondaryAnalyst
                                                ) {
                                                    $analysts[] = $selectedSample->secondaryAnalyst->name;
                                                }
                                                $analystText = !empty($analysts)
                                                    ? implode(' & ', $analysts)
                                                    : 'Unknown Analyst';

                                                $statusHistory[] = [
                                                    'id' => $counter++,
                                                    'time_in' => $selectedSample->analysis_completed_at,
                                                    'status' => $getStatusData('analysis_completed')['display_name'],
                                                    'status_color' => $getStatusData('analysis_completed')['color_class'],
                                                    'analyst' => $analystText,
                                                    'previous_time' => end($statusHistory)['time_in'],
                                                ];
                                            }

                                            // Collect all potential status events with timestamps
$events = [];

// Hand over submitted status - only show if exists
if ($selectedSample->handover_submitted_at) {
    $events[] = [
        'time' => $selectedSample->handover_submitted_at,
        'status' => $getStatusData('submitted_to_handover')['display_name'],
        'status_color' => $getStatusData('submitted_to_handover')['color_class'],
        'analyst' => $selectedSample->handoverSubmittedBy->name ?? 'Unknown',
        'type' => 'handover_submit',
    ];
}

// Hand over taken status - only show if exists
if ($selectedSample->handover_taken_at) {
    $events[] = [
        'time' => $selectedSample->handover_taken_at,
        'status' => $getStatusData('in_progress')['display_name'],
        'status_color' => $getStatusData('in_progress')['color_class'],
        'analyst' => $selectedSample->handoverTakenBy->name ?? 'Unknown',
        'type' => 'handover_take',
    ];
}

// Review status - only show if exists
if ($selectedSample->reviewed_at) {
    $events[] = [
        'time' => $selectedSample->reviewed_at,
        'status' => $getStatusData('reviewed')['display_name'],
        'status_color' => $getStatusData('reviewed')['color_class'],
        'analyst' => $selectedSample->reviewedBy->name ?? 'QC Manager',
        'type' => 'review',
    ];
}

// Approval status - only show if exists
if ($selectedSample->approved_at) {
    $events[] = [
        'time' => $selectedSample->approved_at,
        'status' => $getStatusData('approved')['display_name'],
        'status_color' => $getStatusData('approved')['color_class'],
        'analyst' => $selectedSample->approvedBy->name ?? 'QC Manager',
        'type' => 'approval',
    ];
}


// Sort events chronologically
usort($events, function ($a, $b) {
    return $a['time']->timestamp <=> $b['time']->timestamp;
});

// Add sorted events to status history
foreach ($events as $event) {
    $lastEntry = !empty($statusHistory) ? end($statusHistory) : null;
    $statusHistory[] = [
        'id' => $counter++,
        'time_in' => $event['time'],
        'status' => $event['status'],
        'status_color' => $event['status_color'],
        'analyst' => $event['analyst'],
        'previous_time' => $lastEntry ? $lastEntry['time_in'] : null,
    ];
}

// Handle rejected status (if applicable)
if ($currentStatusName === 'rejected') {
    $lastEntry = !empty($statusHistory) ? end($statusHistory) : null;
    if (!$lastEntry || $lastEntry['status'] !== 'Rejected') {
        $statusHistory[] = [
            'id' => $counter++,
            'time_in' => $selectedSample->updated_at,
            'status' => $getStatusData('rejected')['display_name'],
            'status_color' => $getStatusData('rejected')['color_class'],
            'analyst' => 'QC Manager',
            'previous_time' => $lastEntry ? $lastEntry['time_in'] : null,
                                                    ];
                                                }
                                            }
                                        @endphp

                                        @foreach ($statusHistory as $index => $history)
                                            <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $history['id'] }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    <div>{{ $history['time_in']->format('M d, Y') }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $history['time_in']->format('H:i:s') }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $history['status_color'] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ $history['status'] }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    @if ($history['previous_time'])
                                                        @php
                                                            $interval = $history['previous_time']->diff(
                                                                $history['time_in'],
                                                            );
                                                            $intervalText = '';
                                                            if ($interval->d > 0) {
                                                                $intervalText .= $interval->d . 'd ';
                                                            }
                                                            if ($interval->h > 0) {
                                                                $intervalText .= $interval->h . 'h ';
                                                            }
                                                            if ($interval->i > 0) {
                                                                $intervalText .= $interval->i . 'm';
                                                            }
                                                            if (empty($intervalText)) {
                                                                $intervalText = '< 1m';
                                                            }
                                                        @endphp
                                                        {{ trim($intervalText) }}
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $history['analyst'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button wire:click="hideDetails"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg transition-colors duration-200">
                            Close
                        </button>
                        <button wire:click="printSampleLabel"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Print Sample Label</span>
                        </button>
                        @if ($currentStatusName !== 'approved' && $currentStatusName !== 'rejected')
                            <button wire:click="editSample({{ $selectedSample->id }})"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                Edit Sample
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @endif

    <!-- Edit Sample Modal -->
    @if ($showEditForm && $selectedSample)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
            x-data="{ show: true }" x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click.self="$wire.hideEditForm()">

            <div class="relative top-4 mx-auto my-4 w-11/12 md:w-4/5 lg:w-3/5 xl:w-1/2 max-w-4xl shadow-2xl rounded-2xl bg-white overflow-hidden max-h-[calc(100vh-2rem)]"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-5 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white">Edit Sample</h3>
                                <p class="text-amber-100 text-sm">Modify sample information (ID:
                                    #{{ $selectedSample->id }})</p>
                            </div>
                        </div>
                        <button type="button" wire:click="hideEditForm"
                            class="text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm p-2 transition-all duration-200">
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
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <h4 class="text-sm font-medium text-red-800">Please correct the following errors:
                                    </h4>
                                </div>
                                <ul class="text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form wire:submit.prevent="updateSample">
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
                                        <p class="text-sm text-gray-500">Update sample details and information</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Category Selection -->
                                    <div class="space-y-2">
                                        <label for="edit_category_id"
                                            class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Material Category</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <select wire:model.live="edit_category_id" id="edit_category_id" required
                                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_category_id') border-red-500 ring-red-200 @enderror">
                                            <option value="">Choose material category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('edit_category_id')
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
                                        <label for="edit_raw_mat_id"
                                            class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Raw Material</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <select wire:model.live="edit_raw_mat_id" id="edit_raw_mat_id" required
                                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_raw_mat_id') border-red-500 ring-red-200 @enderror">
                                            <option value="">Choose raw material</option>
                                            @foreach ($editRawMaterials as $rawMat)
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
                                        <label for="edit_reference_id"
                                            class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Testing Reference</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <select wire:model="edit_reference_id" id="edit_reference_id" required
                                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_reference_id') border-red-500 ring-red-200 @enderror">
                                            <option value="">Choose testing reference</option>
                                            @foreach ($editReferences as $reference)
                                                <option value="{{ $reference->id }}">{{ $reference->name }}</option>
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
                                        <p class="text-sm text-gray-500">Update supplier and batch details</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Supplier -->
                                    <div class="space-y-2">
                                        <label for="edit_supplier"
                                            class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Supplier Name</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" wire:model="edit_supplier" id="edit_supplier"
                                                required
                                                class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('supplier') border-red-500 ring-red-200 @enderror"
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
                                        <label for="edit_batch_lot"
                                            class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Batch/Lot Number</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" wire:model="edit_batch_lot" id="edit_batch_lot"
                                                required
                                                class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('batch_lot') border-red-500 ring-red-200 @enderror"
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
                                        <label for="edit_vehicle_container_number"
                                            class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Vehicle/Container Number</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" wire:model="edit_vehicle_container_number"
                                                id="edit_vehicle_container_number" required
                                                class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('vehicle_container_number') border-red-500 ring-red-200 @enderror"
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
                                        <p class="text-sm text-gray-500">Update any additional observations or
                                            comments
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="edit_notes" class="block text-sm font-semibold text-gray-700">
                                        Notes (Optional)
                                    </label>
                                    <textarea wire:model="edit_notes" id="edit_notes" rows="4"
                                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('notes') border-red-500 ring-red-200 @enderror resize-none"
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
                                <button type="button" wire:click="hideEditForm"
                                    class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Cancel</span>
                                </button>
                                <button type="submit" wire:loading.attr="disabled"
                                    class="px-8 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" wire:loading.remove>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" wire:loading>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.49 8.49l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.49-8.49l2.83-2.83" />
                                    </svg>
                                    <span wire:loading.remove>Update Sample</span>
                                    <span wire:loading>Updating...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <!-- Hand Over Form Modal -->
    @if ($showHandOverForm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center" style="z-index: 9999;">
            <div class="bg-white rounded-lg max-w-lg w-full mx-4 max-h-screen overflow-y-auto">
                <!-- Header -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 px-6 py-4 border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-yellow-900">Submit to Hand Over</h3>
                                <p class="text-yellow-700 text-sm">Transfer sample to another analyst</p>
                            </div>
                        </div>
                        <button type="button" wire:click="hideHandOverForm" class="text-yellow-700 hover:text-yellow-900 p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Form Content -->
                <form wire:submit.prevent="submitToHandOver" class="p-6 space-y-6">
                    @if($selectedHandOverSample)
                        <!-- Sample Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Sample Information</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Material:</span>
                                    <span class="text-gray-900 ml-2">{{ $selectedHandOverSample->rawMaterial->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Batch:</span>
                                    <span class="text-gray-900 ml-2">{{ $selectedHandOverSample->batch_lot ?? 'N/A' }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-500">Current Analyst:</span>
                                    <span class="text-gray-900 ml-2">{{ $selectedHandOverSample->primaryAnalyst->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Hand Over Notes -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Hand Over Notes
                            </label>
                            <textarea wire:model="handOverNotes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Add any notes or instructions for the next analyst to take over this sample..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-4 border-t">
                            <button type="button" wire:click="hideHandOverForm" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                Submit Hand Over
                            </button>
                        </div>
                    @else
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-red-700">Error: Sample not loaded. Please try again.</p>
                            <button type="button" wire:click="hideHandOverForm" class="mt-2 px-4 py-2 bg-red-600 text-white rounded">Close</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    @endif

    <!-- Take Over Form Modal -->
    @if ($showTakeOverForm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center" style="z-index: 9999;">
            <div class="bg-white rounded-lg max-w-lg w-full mx-4 max-h-screen overflow-y-auto">
                <!-- Header -->
                <div class="bg-gradient-to-br from-orange-50 to-red-50 px-6 py-4 border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-orange-900">Take Over Sample</h3>
                                <p class="text-orange-700 text-sm">Take ownership and continue analysis</p>
                            </div>
                        </div>
                        <button type="button" wire:click="hideTakeOverForm" class="text-orange-700 hover:text-orange-900 p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Form Content -->
                <form wire:submit.prevent="submitTakeOver" class="p-6 space-y-6">
                    @if($selectedTakeOverSample)
                        <!-- Sample Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Sample Information</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Material:</span>
                                    <span class="text-gray-900 ml-2">{{ $selectedTakeOverSample->rawMaterial->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Batch:</span>
                                    <span class="text-gray-900 ml-2">{{ $selectedTakeOverSample->batch_lot ?? 'N/A' }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-500">Previous Analyst:</span>
                                    <span class="text-gray-900 ml-2">{{ $selectedTakeOverSample->primaryAnalyst->name ?? 'N/A' }}</span>
                                </div>
                                @if($selectedTakeOverSample->handover_notes)
                                    <div class="col-span-2">
                                        <span class="text-gray-500">Hand Over Notes:</span>
                                        <p class="text-gray-900 mt-1 text-sm bg-yellow-50 p-2 rounded">{{ $selectedTakeOverSample->handover_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Analysis Method Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Analysis Method <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" wire:model="takeOverAnalysisMethod" value="individual" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-700">Individual Analysis</div>
                                        <div class="text-xs text-gray-500">Continue analysis on your own</div>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" wire:model="takeOverAnalysisMethod" value="joint" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-700">Joint Analysis</div>
                                        <div class="text-xs text-gray-500">Work together with another analyst</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Secondary Analyst Selection (for joint analysis) -->
                        @if($takeOverAnalysisMethod === 'joint')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Secondary Analyst <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="takeOverSecondaryAnalystId" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="">Select secondary analyst...</option>
                                    @foreach($operators as $operator)
                                        @if($operator->id != auth()->id())
                                            <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-4 border-t">
                            <button type="button" wire:click="hideTakeOverForm" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                                Take Over Sample
                            </button>
                        </div>
                    @else
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-red-700">Error: Sample not loaded. Please try again.</p>
                            <button type="button" wire:click="hideTakeOverForm" class="mt-2 px-4 py-2 bg-red-600 text-white rounded">Close</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    @endif


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

    function sampleDropdown() {
        return {
            isOpen: false,
            sampleData: {},
            justOpened: false,

            config: {
                dropdownWidth: 320,
                dropdownHeight: 400,
                margin: 8,
                viewportMargin: 10,
                transitionDuration: 150
            },

            openDropdown(sampleId, data) {
                this.sampleData = this.createSampleData(sampleId, data);
                if (data.buttonRect) this.calculatePosition(data.buttonRect);
                this.showDropdown();
            },

            closeDropdown() {
                this.isOpen = false;
                setTimeout(() => this.resetData(), this.config.transitionDuration);
            },

            createSampleData(sampleId, data) {
                const statusPermissions = {
                    canEdit: ['submitted', 'pending'].includes(data.status),
                    canStartAnalysis: ['submitted', 'pending'].includes(data.status),
                    canCompleteAnalysis: ['in_progress', 'analysis_started'].includes(data.status),
                    canReview: ['analysis_completed', 'pending_review'].includes(data.status),
                    canApprove: ['reviewed'].includes(data.status),
                    canDelete: !['approved', 'completed'].includes(data.status)
                };

                return {
                    sampleId,
                    ...data,
                    ...statusPermissions,
                    position: {
                        left: 300,
                        top: 200
                    }
                };
            },

            calculatePosition(buttonRect) {
                const {
                    dropdownWidth,
                    dropdownHeight,
                    margin,
                    viewportMargin
                } = this.config;
                const viewport = {
                    width: window.innerWidth,
                    height: window.innerHeight
                };

                const space = {
                    left: buttonRect.left,
                    right: viewport.width - buttonRect.right,
                    above: buttonRect.top,
                    below: viewport.height - buttonRect.bottom
                };

                const left = space.left >= dropdownWidth ?
                    buttonRect.left - dropdownWidth - margin :
                    space.right >= dropdownWidth ?
                    buttonRect.right + margin :
                    (viewport.width - dropdownWidth) / 2;

                const top = space.below >= dropdownHeight ?
                    buttonRect.bottom + margin :
                    space.above >= dropdownHeight ?
                    buttonRect.top - dropdownHeight - margin :
                    (viewport.height - dropdownHeight) / 2;

                this.sampleData.position = {
                    left: Math.max(viewportMargin, Math.min(left, viewport.width - dropdownWidth - viewportMargin)),
                    top: Math.max(viewportMargin, Math.min(top, viewport.height - dropdownHeight - viewportMargin))
                };
            },

            showDropdown() {
                this.justOpened = true;
                this.isOpen = true;
                setTimeout(() => this.justOpened = false, 100);
            },

            resetData() {
                this.sampleData = {};
                this.justOpened = false;
            },

            handleClickAway() {
                if (!this.justOpened) this.closeDropdown();
            },

            callLivewireMethod(method, sampleId) {
                this.$wire.call(method, sampleId);
                this.closeDropdown();
            },

            initGlobalDropdown() {
                window.globalDropdown = {
                    open: (sampleId, data) => this.openDropdown(sampleId, data),
                    close: () => this.closeDropdown()
                };
            }
        };
    }
</script>
