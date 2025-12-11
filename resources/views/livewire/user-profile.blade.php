<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">User Profile</h1>
            <p class="text-gray-600 mt-2">Manage your profile and digital signature</p>
        </div>

        <!-- Alert Messages -->
        @if (session('message'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('message') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Information</h2>

            <form wire:submit.prevent="updateProfile" class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Update Button -->
                <div class="flex gap-4">
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove>Update Profile</span>
                        <span wire:loading>Updating...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Digital Signature QR Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Digital Signature (QR Code)</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Upload Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload QR Signature</h3>

                    <form wire:submit.prevent="uploadSignatureQR" class="space-y-4">
                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Choose Image (PNG/JPG)</label>
                            <div class="relative border-2 border-dashed border-blue-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition-colors"
                                @dragover.prevent="dragover = true"
                                @dragleave.prevent="dragover = false"
                                @drop.prevent="$wire.uploadFile($event)"
                                x-data="{ dragover: false }">

                                <input type="file" wire:model="new_signature_qr" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <p class="text-gray-700 font-medium">Click or drag QR image</p>
                                    <p class="text-gray-500 text-sm">Max 2MB (PNG, JPG)</p>
                                </div>
                            </div>
                            @error('new_signature_qr') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- File Preview -->
                        @if ($new_signature_qr)
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                <img src="{{ $new_signature_qr->temporaryUrl() }}"
                                    alt="Preview" class="h-32 w-32 object-cover rounded">
                            </div>
                        @endif

                        <!-- Upload Button -->
                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 cursor-pointer">
                            <span wire:loading.remove>Upload QR Signature</span>
                            <span wire:loading>Uploading...</span>
                        </button>
                    </form>

                    <p class="text-xs text-gray-500 mt-4">
                        ℹ️ The image will be automatically resized to 150x150px and will appear on all CoA documents you approve.
                    </p>
                </div>

                <!-- Current Signature Display -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Current QR Signature</h3>

                    @if ($signature_qr_image)
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <p class="text-sm text-gray-600 mb-4">Your current signature:</p>
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('storage/' . $signature_qr_image) }}"
                                    alt="QR Signature"
                                    class="h-40 w-40 border-2 border-gray-300 rounded"
                                    onerror="console.error('Failed to load image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div style="display:none;" class="text-red-500 text-sm">
                                    <p>Image failed to load</p>
                                    <p class="text-xs">Path: {{ $signature_qr_image }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">150x150px</p>

                            <button type="button" wire:click="deleteSignatureQR"
                                wire:confirm="Are you sure you want to delete your QR signature?"
                                class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors cursor-pointer">
                                Delete QR Signature
                            </button>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-8 text-center border-2 border-dashed border-gray-300">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 font-medium">No QR Signature uploaded</p>
                            <p class="text-gray-400 text-sm">Upload one to display on your approved CoA documents</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
