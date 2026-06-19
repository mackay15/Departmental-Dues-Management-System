<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-azure-800 leading-tight tracking-tight">
            {{ __('Administrator Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Students -->
                <div class="bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-2xl p-6 border-l-4 border-azure-800 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-azure-600 text-xs font-semibold uppercase tracking-wider">Active Students</div>
                            <div class="mt-2 text-3xl font-extrabold text-azure-950">{{ number_format($totalStudents) }}</div>
                        </div>
                        <div class="p-3 bg-azure-100/60 rounded-xl text-azure-800">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-2xl p-6 border-l-4 border-emerald-500 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-azure-600 text-xs font-semibold uppercase tracking-wider">Total Revenue</div>
                            <div class="mt-2 text-3xl font-extrabold text-emerald-600">GHS {{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1m0-1H8m4 0h4m-4-8H8" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Dues -->
                <div class="bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-2xl p-6 border-l-4 border-rose-500 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-azure-600 text-xs font-semibold uppercase tracking-wider">Outstanding Dues</div>
                            <div class="mt-2 text-3xl font-extrabold text-rose-600">GHS {{ number_format($outstandingDues, 2) }}</div>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-xl text-rose-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Chart Section -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl p-6 lg:col-span-2 border border-azure-100">
                    <div class="flex items-center justify-between mb-4 border-b border-azure-50 pb-4">
                        <h3 class="text-lg font-bold text-azure-900">Revenue Analysis</h3>
                        <span class="text-xs font-semibold text-azure-600 bg-azure-100 px-2.5 py-1 rounded-full">Last 6 Months</span>
                    </div>
                    <div class="relative h-72 w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Recent Payments Section -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl p-6 border border-azure-100">
                    <div class="flex items-center justify-between mb-4 border-b border-azure-50 pb-4">
                        <h3 class="text-lg font-bold text-azure-900">Recent Payments</h3>
                        <svg class="w-5 h-5 text-azure-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="space-y-4 max-h-[280px] overflow-y-auto pr-1">
                        @forelse($recentPayments as $payment)
                            <div class="flex justify-between items-center border-b border-azure-50 pb-3 hover:bg-azure-50/20 px-2 rounded-lg transition-colors duration-150">
                                <div>
                                    <p class="text-sm font-semibold text-azure-950">{{ $payment->student->first_name ?? 'Unknown' }} {{ $payment->student->last_name ?? '' }}</p>
                                    <p class="text-xs text-azure-500 mt-0.5">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                </div>
                                <div class="text-sm font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                    +GHS {{ number_format($payment->amount, 2) }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-azure-500 italic">No recent payments found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Injection -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const data = @json($monthlyRevenue);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels.reverse(),
                    datasets: [{
                        label: 'Revenue (GHS)',
                        data: data.data.reverse(),
                        backgroundColor: 'rgba(0, 49, 82, 0.85)', // Dark Azure
                        borderColor: '#003152',
                        borderWidth: 1.5,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    family: "'Inter', 'sans-serif'",
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e8f4fd'
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', 'sans-serif'"
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', 'sans-serif'"
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
