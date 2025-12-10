<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Format Dokumen CoA</h2>
            <p class="text-gray-600 mt-1">Kelola format nomor dokumen Certificate of Analysis</p>
        </div>
        <button wire:click="openCreateModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Tambah Format Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-4">
        <input type="text" wire:model.live="search" placeholder="Cari format..."
            class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                        Format</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contoh
                        Format</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($formats as $format)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">{{ $format->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="bg-gray-100 px-2 py-1 rounded text-sm">
                                {{ $format->prefix }}-{{ $format->year_month }}/{{ $format->middle_part }}/{{ now()->year }}-{{ $format->suffix }}
                            </code>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $format->description ?: '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="toggleActive({{ $format->id }})"
                                class="px-3 py-1 rounded text-xs font-medium {{ $format->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $format->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <button wire:click="openEditModal({{ $format->id }})"
                                        class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $format->id }})"
                                        onclick="return confirm('Yakin ingin menghapus format ini?')"
                                        class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Belum ada format dokumen
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $formats->links() }}
    </div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-500/75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $modalMode === 'create' ? 'Tambah Format Baru' : 'Edit Format' }}
                    </h3>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Format *</label>
                        <input type="text" wire:model="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-4 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prefix *</label>
                            <input type="text" wire:model.live="prefix" placeholder="TI/COA"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('prefix')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor *</label>
                            <input type="number" wire:model.live="year_month" placeholder="2512"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('year_month')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle *</label>
                            <input type="text" wire:model.live="middle_part" placeholder="MT"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('middle_part')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Suffix *</label>
                            <input type="text" wire:model.live="suffix" placeholder="S0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('suffix')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <p class="text-xs text-gray-500 -mt-2">
                        *Info Nomor: Hanya angka (contoh: 2512, 1234)
                    </p>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview Format:</p>
                        <code class="bg-white px-3 py-2 rounded border border-gray-300 block text-center">
                            {{ $prefix }}-{{ $year_month ?: 'XXX' }}/{{ $middle_part }}/{{ now()->year }}-{{ $suffix }}
                        </code>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    {{-- Custom Fields Section --}}
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-medium text-gray-700">Custom Fields (Optional)</label>
                            <button type="button" wire:click="addCustomField"
                                class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Field
                            </button>
                        </div>

                        @if(count($customFields) > 0)
                            <div class="space-y-3">
                                @foreach($customFields as $index => $field)
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                        <div class="grid grid-cols-12 gap-2 items-start">
                                            <div class="col-span-4">
                                                <label class="block text-xs text-gray-600 mb-1">Label *</label>
                                                <input type="text" wire:model="customFields.{{ $index }}.label"
                                                    placeholder="e.g., IEC NO"
                                                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                                            </div>
                                            <div class="col-span-3">
                                                <label class="block text-xs text-gray-600 mb-1">Key *</label>
                                                <input type="text" wire:model="customFields.{{ $index }}.key"
                                                    placeholder="e.g., iec_no"
                                                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                                            </div>
                                            <div class="col-span-2">
                                                <label class="block text-xs text-gray-600 mb-1">Type</label>
                                                <select wire:model="customFields.{{ $index }}.type"
                                                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                                                    <option value="text">Text</option>
                                                    <option value="number">Number</option>
                                                    <option value="date">Date</option>
                                                    <option value="textarea">Textarea</option>
                                                </select>
                                            </div>
                                            <div class="col-span-2 flex items-end">
                                                <label class="flex items-center gap-1 pb-1.5">
                                                    <input type="checkbox" wire:model="customFields.{{ $index }}.required"
                                                        class="rounded text-blue-600">
                                                    <span class="text-xs text-gray-600">Required</span>
                                                </label>
                                            </div>
                                            <div class="col-span-1 flex items-end justify-end">
                                                <button type="button" wire:click="removeCustomField({{ $index }})"
                                                    class="text-red-600 hover:text-red-700 pb-1.5">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-3">Belum ada custom field. Klik "Tambah Field" untuk menambahkan.</p>
                        @endif
                    </div>

                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="is_active" class="rounded">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                    <button wire:click="closeModal"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        {{ $modalMode === 'create' ? 'Simpan' : 'Update' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
