@php
use Illuminate\Support\Facades\Storage;
@endphp

<div>
    @if ($show && $sample)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
            x-data="{ show: true }" x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click.self="$wire.close()">

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
                            <p class="text-amber-100 text-sm">Modify sample information </p>
                        </div>
                    </div>
                    <button type="button" wire:click="close"
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
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <h4 class="text-sm font-medium text-red-800">Please correct the following errors:</h4>
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
                                    <select wire:model.live="edit_category_id" id="edit_category_id"
                                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_category_id') border-red-500 ring-red-200 @enderror">
                                        <option value="" hidden>Choose material category</option>
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

                                <!-- Solder Selection -->
                                <div class="space-y-2">
                                    <label for="edit_material_id"
                                        class="block text-sm font-semibold text-gray-700">
                                        <span class="flex items-center space-x-1">
                                            <span>Solder</span>
                                            <span class="text-red-500">*</span>
                                        </span>
                                    </label>
                                    <select wire:model.live="edit_material_id" id="edit_material_id"
                                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200
                                        @error('edit_material_id') border-red-500 ring-red-200 @enderror">
                                        <option value="" hidden>Choose solder</option>
                                        @foreach ($editMaterials as $material)
                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('edit_material_id')
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
                                    <select wire:model="edit_reference_id" id="edit_reference_id"
                                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_reference_id') border-red-500 ring-red-200 @enderror">
                                        <option value="" hidden>Choose testing reference</option>
                                        @foreach ($editReferences as $reference)
                                            <option value="{{ $reference->id }}">{{ $reference->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('edit_reference_id')
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

                                            class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_supplier') border-red-500 ring-red-200 @enderror"
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
                                    @error('edit_supplier')
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

                                            class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_batch_lot') border-red-500 ring-red-200 @enderror"
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
                                    @error('edit_batch_lot')
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
                                            id="edit_vehicle_container_number"
                                            class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_vehicle_container_number') border-red-500 ring-red-200 @enderror"
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
                                    @error('edit_vehicle_container_number')
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

                                <!-- CoA Checkbox -->
                                <div class="space-y-2 md:col-span-2">
                                    <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" wire:model.live="edit_has_coa" id="edit_has_coa"
                                                class="w-5 h-5 text-blue-600 bg-white border-2 border-blue-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                                        </div>
                                        <div class="ml-3">
                                            <label for="edit_has_coa"
                                                class="text-sm font-semibold text-blue-900 cursor-pointer">
                                                Certificate of Analysis (CoA) Available
                                            </label>
                                            <p class="text-xs text-blue-700 mt-1">Check this if you have a CoA document to upload</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CoA File Upload Section (shows when edit_has_coa is checked) -->
                        @if ($edit_has_coa)
                            <div class="mb-8">
                                <div class="flex items-center space-x-2 mb-6">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">Certificate of Analysis</h4>
                                        <p class="text-sm text-gray-500">Update your CoA document</p>
                                    </div>
                                </div>

                                <!-- Display current CoA file if exists -->
                                @if ($sample && $sample->coa_file_path)
                                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-900">Current CoA File</p>
                                                    <p class="text-xs text-blue-700">{{ basename($sample->coa_file_path) }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($sample->coa_file_path) }}" target="_blank"
                                                class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
                                                View File
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div class="space-y-2">
                                    <label for="edit_coa_file" class="block text-sm font-semibold text-gray-700">
                                        <span class="flex items-center space-x-1">
                                            <span>Upload New CoA File {{ $sample && $sample->coa_file_path ? '(Optional - Leave empty to keep current file)' : '' }}</span>
                                            @if (!$sample || !$sample->coa_file_path)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500 font-normal block mt-1">(PDF, DOC, DOCX, JPG, JPEG, PNG - Max 10MB)</span>
                                    </label>
                                    <div class="relative">
                                        <input type="file" wire:model="edit_coa_file" id="edit_coa_file"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-3 bg-white border-2 border-dashed border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('edit_coa_file') border-red-500 @enderror
                                               file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all duration-200">
                                    </div>
                                    @error('edit_coa_file')
                                        <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                    @if ($edit_coa_file)
                                        <div class="mt-3 p-4 bg-green-50 border border-green-200 rounded-xl">
                                            <div class="flex items-center text-sm text-green-700">
                                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-4 h-4 text-green-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium">New file selected successfully</p>
                                                    <p class="text-xs text-green-600">
                                                        {{ $edit_coa_file->getClientOriginalName() }}</p>
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
                                    <p class="text-sm text-gray-500">Update any additional observations or comments</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="edit_notes" class="block text-sm font-semibold text-gray-700">
                                    Notes (Optional)
                                </label>
                                <textarea wire:model="edit_notes" id="edit_notes" rows="4"
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-sm transition-all duration-200 @error('edit_notes') border-red-500 ring-red-200 @enderror resize-none"
                                    placeholder="Enter any additional notes, observations, or special handling instructions..."></textarea>
                                @error('edit_notes')
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
                            <button type="button" wire:click="close"
                                class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Cancel</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-8 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
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
</div>
