<div x-data="{
    scrollToError() {
        this.$nextTick(() => {
            const errorElement = this.$el.querySelector('.border-red-500, .ring-red-500, [aria-invalid=true]');
            if (errorElement) {
                errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                errorElement.focus();
            }
        });
    }
}"
@scroll-to-error.window="scrollToError()">
    <!-- Submission Form Modal -->
    @if ($showForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" x-data="{ show: true }"
            x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="$wire.hide()">

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
                                <p class="text-blue-100 text-sm">Submit chemical sample for laboratory analysis
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="hide"
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
                                        <label class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Material Category</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>

                                        {{-- Custom Searchable Dropdown for Category --}}
                                        <div class="relative" x-data="{ showDropdown: false }" @click.away="showDropdown = false">
                                            <input type="text"
                                                wire:model.live.debounce.300ms="categorySearch"
                                                @click="showDropdown = !{{ $categories->isEmpty() ? 'true' : 'false' }} && (showDropdown = true)"
                                                value="{{ !empty($this->selectedCategoryName) ? $this->selectedCategoryName : $categorySearch }}"
                                                placeholder="🔍 Search material category..."
                                                class="@error('category_id') border-red-500 @else border-gray-300 @enderror w-full rounded-xl border-2 px-4 py-3 pr-10 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium @if ($categories->isEmpty()) bg-gray-100 cursor-not-allowed @endif"
                                                autocomplete="off"
                                                {{ $categories->isEmpty() ? 'disabled' : '' }}>

                                            {{-- Arrow Icon with Animation --}}
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                <svg class="h-5 w-5 text-gray-400 transition-transform duration-200"
                                                     :class="{ 'rotate-180': showDropdown }"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>

                                            {{-- Dropdown List --}}
                                            @if (!$categories->isEmpty())
                                                <div x-show="showDropdown"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 transform scale-95"
                                                     x-transition:enter-end="opacity-100 transform scale-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100 transform scale-100"
                                                     x-transition:leave-end="opacity-0 transform scale-95"
                                                     class="absolute z-50 mt-2 w-full bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                                    @if (count($categories) > 0)
                                                        @foreach ($categories as $category)
                                                            <div wire:click="$set('category_id', {{ $category->id }})"
                                                                 @click="showDropdown = false"
                                                                 class="px-4 py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer border-b border-gray-100 last:border-0 transition-all duration-150 flex items-center justify-between group">
                                                                <div class="flex items-center space-x-3 flex-1">
                                                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                                    </svg>
                                                                    <div class="flex-1">
                                                                        <div class="font-semibold text-sm">{{ $category->name }}</div>
                                                                        <div class="text-xs text-gray-500 mt-0.5">Material category</div>
                                                                    </div>
                                                                </div>

                                                                {{-- Checkmark for selected item --}}
                                                                @if ($category_id == $category->id)
                                                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-5 h-5 text-gray-300 opacity-0 group-hover:opacity-100 flex-shrink-0 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">No categories found</div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Alert --}}
                                        @if ($categories->isEmpty())
                                            <div x-data="{ show: true }" x-show="show"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                                class="mt-2 flex items-center p-4 text-sm text-yellow-800 rounded-lg bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-300 shadow-sm"
                                                role="alert">
                                                <svg class="flex-shrink-0 inline w-5 h-5 me-3 text-yellow-600"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                                </svg>
                                                <div class="flex-1">
                                                    <span class="font-semibold">Tidak ada kategori chemical yang
                                                        tersedia!</span>
                                                    <p class="mt-1 text-xs">Silakan tambahkan kategori chemical
                                                        terlebih dahulu sebelum membuat sampel.</p>
                                                </div>
                                            </div>
                                        @endif

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

                                    <!-- Chemical Selection -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Chemical</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>

                                        {{-- Custom Searchable Dropdown for Chemical --}}
                                        <div class="relative" x-data="{ showDropdown: false }" @click.away="showDropdown = false">
                                            <input type="text"
                                                wire:model.live.debounce.300ms="materialSearch"
                                                @click="showDropdown = !{{ empty($category_id) || $materials->isEmpty() ? 'true' : 'false' }} && (showDropdown = true)"
                                                value="{{ !empty($this->selectedMaterialName) ? $this->selectedMaterialName : $materialSearch }}"
                                                placeholder="🔍 Search chemical..."
                                                class="@error('material_id') border-red-500 @else border-gray-300 @enderror w-full rounded-xl border-2 px-4 py-3 pr-10 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium @if (empty($category_id) || $materials->isEmpty()) bg-gray-100 cursor-not-allowed @endif"
                                                autocomplete="off"
                                                {{ empty($category_id) || $materials->isEmpty() ? 'disabled' : '' }}>

                                            {{-- Arrow Icon with Animation --}}
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                <svg class="h-5 w-5 text-gray-400 transition-transform duration-200"
                                                     :class="{ 'rotate-180': showDropdown }"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>

                                            {{-- Dropdown List --}}
                                            @if (!empty($category_id) && !$materials->isEmpty())
                                                <div x-show="showDropdown"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 transform scale-95"
                                                     x-transition:enter-end="opacity-100 transform scale-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100 transform scale-100"
                                                     x-transition:leave-end="opacity-0 transform scale-95"
                                                     class="absolute z-50 mt-2 w-full bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                                    @if (count($materials) > 0)
                                                        @foreach ($materials as $material)
                                                            <div wire:click="$set('material_id', {{ $material->id }})"
                                                                 @click="showDropdown = false"
                                                                 class="px-4 py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer border-b border-gray-100 last:border-0 transition-all duration-150 flex items-center justify-between group">
                                                                <div class="flex items-center space-x-3 flex-1">
                                                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                                                    </svg>
                                                                    <div class="flex-1">
                                                                        <div class="font-semibold text-sm">{{ $material->name }}</div>
                                                                        <div class="text-xs text-gray-500 mt-0.5">Chemical material</div>
                                                                    </div>
                                                                </div>

                                                                {{-- Checkmark for selected item --}}
                                                                @if ($material_id == $material->id)
                                                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-5 h-5 text-gray-300 opacity-0 group-hover:opacity-100 flex-shrink-0 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">No chemicals found</div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Alert --}}
                                        @if (!empty($category_id) && $materials->isEmpty())
                                            <div x-data="{ show: true }" x-show="show"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                                class="mt-2 flex items-center p-4 text-sm text-yellow-800 rounded-lg bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-300 shadow-sm"
                                                role="alert">
                                                <svg class="flex-shrink-0 inline w-5 h-5 me-3 text-yellow-600"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                                </svg>
                                                <span class="sr-only">Info</span>
                                                <div>
                                                    <span class="font-semibold">Maaf!</span> Tidak ada material yang
                                                    tersedia untuk kategori ini.
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Error Notification --}}
                                        @error('material_id')
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

                                <!-- Testing Reference and Batch/Lot in separate row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <!-- Reference Selection -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">
                                            <span class="flex items-center space-x-1">
                                                <span>Testing Reference</span>
                                                <span class="text-red-500">*</span>
                                            </span>
                                        </label>

                                        {{-- Custom Searchable Dropdown --}}
                                        <div class="relative" x-data="{ showDropdown: false }" @click.away="showDropdown = false">
                                            <input type="text"
                                                wire:model.live.debounce.300ms="referenceSearch"
                                                @click="showDropdown = !{{ empty($material_id) || $references->isEmpty() ? 'true' : 'false' }} && (showDropdown = true)"
                                                value="{{ !empty($this->selectedReferenceName) ? $this->selectedReferenceName : $referenceSearch }}"
                                                placeholder="🔍 Search testing reference..."
                                                class="@error('reference_id') border-red-500 @else border-gray-300 @enderror w-full rounded-xl border-2 px-4 py-3 pr-10 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium @if (empty($material_id) || $references->isEmpty()) bg-gray-100 cursor-not-allowed @endif"
                                                autocomplete="off"
                                                {{ empty($material_id) || $references->isEmpty() ? 'disabled' : '' }}>

                                            {{-- Arrow Icon with Animation --}}
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                <svg class="h-5 w-5 text-gray-400 transition-transform duration-200"
                                                     :class="{ 'rotate-180': showDropdown }"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>

                                            {{-- Dropdown List --}}
                                            @if (!empty($material_id) && !$references->isEmpty())
                                                <div x-show="showDropdown"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 transform scale-95"
                                                     x-transition:enter-end="opacity-100 transform scale-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100 transform scale-100"
                                                     x-transition:leave-end="opacity-0 transform scale-95"
                                                     class="absolute z-50 mt-2 w-full bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                                    @if (count($references) > 0)
                                                        @foreach ($references as $reference)
                                                            <div wire:click="$set('reference_id', {{ $reference->id }}); showDropdown = false"
                                                                 @click="showDropdown = false"
                                                                 class="px-4 py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer border-b border-gray-100 last:border-0 transition-all duration-150 flex items-center justify-between group">
                                                                <div class="flex items-center space-x-3 flex-1">
                                                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                    </svg>
                                                                    <div class="flex-1">
                                                                        <div class="font-semibold text-sm">{{ $reference->name }}</div>
                                                                        <div class="text-xs text-gray-500 mt-0.5">Standard testing reference</div>
                                                                    </div>
                                                                </div>

                                                                {{-- Checkmark for selected item --}}
                                                                @if ($reference_id == $reference->id)
                                                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-5 h-5 text-gray-300 opacity-0 group-hover:opacity-100 flex-shrink-0 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">No references found</div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Alert --}}
                                        @if (!empty($material_id) && $references->isEmpty())
                                            <div x-data="{ show: true }" x-show="show"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                                class="mt-2 flex items-center p-4 text-sm text-yellow-800 rounded-lg bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-300 shadow-sm"
                                                role="alert">
                                                <svg class="flex-shrink-0 inline w-5 h-5 me-3 text-yellow-600"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                                </svg>
                                                <span class="sr-only">Info</span>
                                                <div>
                                                    <span class="font-semibold">Maaf!</span> Tidak ada referensi
                                                    pengujian yang tersedia untuk material ini.
                                                </div>
                                            </div>
                                        @endif

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

                                    <!-- Batch/Lot Number -->
                                    <div class="space-y-2">
                                        <label for="batch_lot" class="block text-sm font-semibold text-gray-700">
                                            Batch/Lot Number
                                        </label>
                                        <div class="relative">
                                            <input type="text" wire:model="batch_lot" id="batch_lot"
                                                class="w-full px-4 py-3 pl-10 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 @error('batch_lot') border-red-500 ring-red-200 @enderror"
                                                placeholder="Enter batch or lot number">
                                            <div
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
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
                                            <input type="date" wire:model="submission_date" id="submission_date"
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
                                            <input type="time" wire:model="submission_time" id="submission_time"
                                                readonly
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
                                        Notes
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
                                <button type="button" wire:click="hide"
                                    class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Cancel</span>
                                </button>
                                <button type="button" wire:click="validateBeforeConfirm" wire:loading.attr="disabled"
                                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        wire:loading.remove wire:target="validateBeforeConfirm">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" wire:loading wire:target="validateBeforeConfirm">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.49 8.49l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.49-8.49l2.83-2.83" />
                                    </svg>
                                    <span wire:loading.remove wire:target="validateBeforeConfirm">Submit Sample</span>
                                    <span wire:loading wire:target="validateBeforeConfirm">Validating...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        @if($showConfirmation)
        <div x-show="true" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] overflow-y-auto" x-cloak>

            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showConfirmation', false)" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal container - centered -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <!-- Modal panel -->
                <div
                    class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all max-w-lg w-full mx-auto">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Konfirmasi Submit Sample
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Apakah Anda yakin ingin submit sample ini untuk analisis? Sample akan masuk ke
                                        sistem dengan status "Pending".
                                    </p>

                                    <!-- Sample Details -->
                                    <div class="bg-gray-50 rounded-lg p-3 text-sm">
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 font-medium">Category:</span>
                                                <span class="text-gray-900">
                                                    @foreach ($categories as $category)
                                                        @if ($category->id == $category_id)
                                                            {{ $category->name }}
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-600 font-medium">Material:</span>
                                                <span class="text-gray-900">
                                                    @foreach ($materials as $material)
                                                        @if ($material->id == $material_id)
                                                            {{ $material->name }}
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-600 font-medium">Reference:</span>
                                                <span class="text-gray-900">
                                                    @foreach ($references as $reference)
                                                        @if ($reference->id == $reference_id)
                                                            {{ $reference->name }}
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="submit" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Submit Sample</span>
                            <span wire:loading>Submitting...</span>
                        </button>
                        <button wire:click="$set('showConfirmation', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
