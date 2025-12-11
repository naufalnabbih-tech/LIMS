<div>
    <!-- Month Filter -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Pilih Bulan:</label>
            <select wire:model.live="selectedMonth"
                class="flex-1 max-w-xs px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                @foreach($monthOptions as $option)
                    <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Solder Operators -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Data Operator Laboratorium - Solder</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Total Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Jumlah Sampel</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $soldOperators = $this->getSolderOperators();
                        @endphp
                        @forelse($soldOperators as $index => $operator)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-gray-900 font-medium">{{ $operator['name'] }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $operator['total_time'] }}</td>
                                <td class="px-4 py-3 text-gray-700 text-center">{{ $operator['total_samples'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada data operator untuk bulan ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chemical Operators -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Data Operator Laboratorium - Chemical</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Total Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Jumlah Sampel</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $chemOperators = $this->getChemicalOperators();
                        @endphp
                        @forelse($chemOperators as $index => $operator)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-gray-900 font-medium">{{ $operator['name'] }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $operator['total_time'] }}</td>
                                <td class="px-4 py-3 text-gray-700 text-center">{{ $operator['total_samples'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada data operator untuk bulan ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
