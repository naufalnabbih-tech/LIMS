<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-2 text-gray-600">Welcome back! Here's what's happening in your lab today.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    All Systems Operational
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div
            class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-800 tracking-tight">{{ $pendingTests }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending Analysis</p>
                <div
                    class="mt-2 inline-flex items-center px-2.5 py-1 rounded-md bg-amber-50 border border-amber-100 text-amber-700">
                    <svg class="w-3.5 h-3.5 mr-1.5 animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs font-medium">Awaiting testing</span>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-800 tracking-tight">{{ $submissionsInProgress }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">In Progress</p>
                <div
                    class="mt-2 inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 border border-blue-100 text-blue-700">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    <span class="text-xs font-medium">Active analysis</span>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-800 tracking-tight">{{ $analysisComplete }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Analysis Complete</p>
                <div
                    class="mt-2 inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-700">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-xs font-medium">Ready for review</span>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-800 tracking-tight">{{ $reviewedTests }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Under Review</p>
                <div
                    class="mt-2 inline-flex items-center px-2.5 py-1 rounded-md bg-purple-50 border border-purple-100 text-purple-700">
                    <svg class="w-3.5 h-3.5 mr-1.5 animate-spin" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    <span class="text-xs font-medium">Being reviewed</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">

                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Submissions Chart</h3>
                </div>

                <div class="bg-gray-100 p-1 rounded-xl flex items-center">
                    <button wire:click="updateChartPeriod('week')"
                        class="px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 focus:outline-none {{ $chartPeriod === 'week' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Week
                    </button>
                    <button wire:click="updateChartPeriod('month')"
                        class="px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 focus:outline-none {{ $chartPeriod === 'month' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Month
                    </button>
                    <button wire:click="updateChartPeriod('year')"
                        class="px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 focus:outline-none {{ $chartPeriod === 'year' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Year
                    </button>
                </div>
            </div>

            <div class="relative w-full h-64" wire:ignore>
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Total Approved</h3>
                        <p class="text-sm text-gray-500 font-medium">
                            {{ now()->subMonths(11)->format('M Y') }} - {{ now()->format('M Y') }}
                        </p>
                    </div>
                </div>


            </div>

            <div class="relative w-full h-64" wire:ignore>
                <canvas id="approvalChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Data Operator Laboratorium Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Data Tin Solder</h3>
            </div>

            <div class="relative">
                <select wire:model.live="selectedMonth"
                    class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-green-500 focus:border-green-500 block w-full pl-3 pr-8 py-2 cursor-pointer hover:bg-gray-100 transition-colors">
                    <option value="">-- Semua Bulan --</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $bulan)
                        <option value="{{ $idx + 1 }}">{{ $bulan }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider rounded-l-lg">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Operator</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Total Waktu</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Samples</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider rounded-r-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($solderOperators as $index => $data)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">

                                    <div class="text-sm font-semibold text-gray-800">{{ $data['operator']->name }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="mr-1.5 h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $data['total_waktu'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                    {{ $data['jumlah_sample'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openDetailModal({{ $data['operator']->id }}, 'solder')"
                                    class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors text-xs font-semibold">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <span class="text-sm">Tidak ada data untuk bulan ini</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Data Tin Chemical</h3>
            </div>

            <div class="relative">
                <select wire:model.live="selectedMonth"
                    class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 block w-full pl-3 pr-8 py-2 cursor-pointer hover:bg-gray-100 transition-colors">
                    <option value="">-- Semua Bulan --</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $bulan)
                        <option value="{{ $idx + 1 }}">{{ $bulan }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider rounded-l-lg">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Operator</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Total Waktu</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Samples</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider rounded-r-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($chemicalOperators as $index => $data)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">

                                    <div class="text-sm font-semibold text-gray-800">{{ $data['operator']->name }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="mr-1.5 h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $data['total_waktu'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-700 border border-orange-100">
                                    {{ $data['jumlah_sample'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openDetailModal({{ $data['operator']->id }}, 'chemical')"
                                    class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg transition-colors text-xs font-semibold">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <span class="text-sm">Tidak ada data untuk bulan ini</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Modal Detail Sample Per Operator -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-5xl shadow-lg rounded-lg bg-white"
                wire:click.stop>
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Detail Sample - {{ $selectedOperator?->name }}
                        ({{ ucfirst($modalSampleType) }})
                        @if ($selectedMonth)
                            - {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y') }}
                        @endif
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="mt-4 max-h-96 overflow-y-auto">
                    @if ($operatorSamples->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Batch/Lot</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Material</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu Analysis</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($operatorSamples as $index => $sample)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $index + 1 }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $sample->batch_lot ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $sample->material->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            @php
                                                // Use operator's actual work time calculated from handovers
                                                $seconds = $sample->operator_work_time ?? 0;
                                                $hours = floor($seconds / 3600);
                                                $minutes = floor(($seconds % 3600) / 60);
                                            @endphp
                                            @if ($seconds > 0)
                                                {{ $hours > 0 ? $hours . ' jam ' : '' }}{{ $minutes }} menit
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $sample->analysis_started_at ? $sample->analysis_started_at->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-4 text-sm text-gray-500">Tidak ada sample untuk ditampilkan</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end pt-4 border-t border-gray-200 mt-4">
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Load dashboard charts script FIRST -->
    @vite('resources/js/dashboard-charts.js')

    <!-- Pass PHP data to JavaScript and initialize -->
    <script>
        window.dashboardData = {
            monthlySubmissions: @json($monthlySubmissions),
            approvalData: @json($approvalData)
        };

        // Force initialization after a short delay to ensure everything is loaded
        setTimeout(function() {
            if (typeof window.initCharts === 'function') {
                window.initCharts();
            }
        }, 200);
    </script>
</div>
