// Dashboard Charts Management
// Make variables and function global
window.monthlyChartInstance = null;
window.approvalChartInstance = null;

window.initCharts = function() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        return;
    }

    // Monthly Submissions Chart
    const monthlyCtx = document.getElementById('monthlyChart');

    if (monthlyCtx) {
        // Destroy existing chart if it exists
        if (window.monthlyChartInstance) {
            window.monthlyChartInstance.destroy();
        }

        const monthlyData = window.dashboardData?.monthlySubmissions || [];

        try {
            window.monthlyChartInstance = new Chart(monthlyCtx, {
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
        } catch (error) {
            // Silent error
        }
    }

    // Approval Data Chart (Bar Chart)
    const approvalCtx = document.getElementById('approvalChart');

    if (approvalCtx) {
        // Destroy existing chart if it exists
        if (window.approvalChartInstance) {
            window.approvalChartInstance.destroy();
        }

        const approvalData = window.dashboardData?.approvalData || { labels: [], datasets: [] };

        try {
            window.approvalChartInstance = new Chart(approvalCtx, {
                type: 'bar',
                data: {
                    labels: approvalData.labels || [],
                    datasets: approvalData.datasets || []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                },
                                usePointStyle: true,
                                boxWidth: 6,
                                boxHeight: 6
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } catch (error) {
            // Silent error
        }
    }
}

// Initialize charts on page load
document.addEventListener('DOMContentLoaded', function() {
    window.initCharts();
});

// Re-initialize charts after Livewire updates (Livewire 3)
document.addEventListener('livewire:navigated', function() {
    window.initCharts();
});

// Listen for chart data update event from Livewire
document.addEventListener('livewire:init', () => {
    Livewire.on('chartDataUpdated', (event) => {
        const data = Array.isArray(event) ? event[0] : event;

        // Validasi agar tidak error jika data kosong
        if (!data || !data.monthlySubmissions) {
            return;
        }

        // Manual Update Chart Instance
        if (window.monthlyChartInstance) {
            // Update Label
            window.monthlyChartInstance.data.labels = data.monthlySubmissions.map(d => d.month);
            // Update Data
            window.monthlyChartInstance.data.datasets[0].data = data.monthlySubmissions.map(d => d.count);
            // Render Ulang
            window.monthlyChartInstance.update();
        }

        if (window.approvalChartInstance && data.approvalData) {
            window.approvalChartInstance.data.labels = data.approvalData.labels || [];
            window.approvalChartInstance.data.datasets = data.approvalData.datasets || [];
            window.approvalChartInstance.update();
        }
    });
});

// Watch for Livewire updates and re-initialize charts
document.addEventListener('livewire:update', () => {
    setTimeout(() => {
        window.initCharts();
    }, 100);
});
