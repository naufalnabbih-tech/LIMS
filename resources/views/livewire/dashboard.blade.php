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
        <!-- Submissions In Progress Card -->
        <div
            class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Submissions In Progress</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $submissionsInProgress }}</p>
                    <p class="text-xs text-blue-600 flex items-center mt-1">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        Active analysis
                    </p>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div
            class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Categories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalCategories }}</p>
                    <p class="text-xs text-gray-500 mt-1">Active categories</p>
                </div>
            </div>
        </div>

        <!-- Pending Tests Card -->
        <div
            class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Pending Tests</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingTests }}</p>
                    <p class="text-xs text-amber-600 flex items-center mt-1">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Awaiting analysis
                    </p>
                </div>
            </div>
        </div>

        <!-- Completed Tests Card -->
        <div
            class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-violet-500 to-violet-600 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Tests Complete</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $completedTests }}</p>
                    <p class="text-xs text-green-600 flex items-center mt-1">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Approved samples
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Monthly Submissions Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Submissions</h3>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Distribution</h3>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity - Takes 2/3 width -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                        <a href="{{ route('sample-rawmat-submissions') }}" wire:navigate
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
                    </div>
                </div>
                <div class="pt-5 pb-10  px-6">
                    @if (count($recentActivities) > 0)
                        <div class="flow-root">
                            <ul class="-mb-8 space-y-6">
                                @foreach ($recentActivities as $activity)
                                    <li class="relative">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                                                    @if ($activity['icon'] === 'check-circle')
                                                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @elseif($activity['icon'] === 'clipboard-list')
                                                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                            <path fill-rule="evenodd"
                                                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}
                                                </p>
                                                <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ $activity['time_human'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-4 text-sm text-gray-500">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('rawmats') }}" wire:navigate
                            class="group block p-4 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 border border-blue-200 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-700 transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900">Add Raw Material</p>
                                    <p class="text-xs text-gray-600">Register new material</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('sample-rawmat-submissions') }}" wire:navigate
                            class="group block p-4 rounded-lg bg-gradient-to-r from-emerald-50 to-emerald-100 hover:from-emerald-100 hover:to-emerald-200 border border-emerald-200 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center group-hover:bg-emerald-700 transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900">Submit Sample</p>
                                    <p class="text-xs text-gray-600">New sample submission</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('rawmat-categories') }}" wire:navigate
                            class="group block p-4 rounded-lg bg-gradient-to-r from-amber-50 to-amber-100 hover:from-amber-100 hover:to-amber-200 border border-amber-200 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center group-hover:bg-amber-700 transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900">Manage Categories</p>
                                    <p class="text-xs text-gray-600">Organize materials</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Submissions Chart
            const monthlyCtx = document.getElementById('monthlyChart');
            if (monthlyCtx) {
                const monthlyData = @json($monthlySubmissions);
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyData.map(d => d.month),
                        datasets: [{
                            label: 'Submissions',
                            data: monthlyData.map(d => d.count),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Status Distribution Chart
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                const statusData = @json($statusDistribution);
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusData.map(d => d.label),
                        datasets: [{
                            data: statusData.map(d => d.count),
                            backgroundColor: statusData.map(d => d.color),
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
