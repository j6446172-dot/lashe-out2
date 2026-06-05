@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        
        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">📊 إحصائيات الشهر</h1>
                    <p class="text-white/80 mt-1">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('staff.dashboard') }}" class="text-white/80 hover:text-white flex items-center gap-1">
                        <i class="fas fa-arrow-right"></i> العودة للداشبورد
                    </a>
                </div>
            </div>
        </div>

        {{-- Month Selector --}}
        <div class="rounded-xl p-4 mb-6" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(4px);">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <label class="font-bold text-gray-700">📅 اختر الشهر:</label>
                    <input type="month" id="monthPicker" value="{{ date('Y-m') }}" 
                           class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#B08D57]">
                </div>
                <div class="flex gap-2">
                    <button onclick="previousMonth()" class="px-4 py-2 rounded-lg transition" style="background: #B08D57; color: white;">
                        <i class="fas fa-chevron-right"></i> الشهر السابق
                    </button>
                    <button onclick="nextMonth()" class="px-4 py-2 rounded-lg transition" style="background: #B08D57; color: white;">
                        الشهر التالي <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="rounded-xl p-5 text-center transition-all hover:scale-105" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2" style="background: rgba(59, 130, 246, 0.2);">
                    <i class="fas fa-calendar-check text-2xl" style="color: #3b82f6;"></i>
                </div>
                <p class="text-gray-600 text-sm">📋 إجمالي الحجوزات</p>
                <p class="text-3xl font-bold mt-1" id="totalBookings" style="color: #3b82f6;">0</p>
                <p class="text-xs mt-1" id="totalChange">
                    <i class="fas fa-chart-line"></i> <span class="text-green-600">+0%</span> عن الشهر الماضي
                </p>
            </div>

            <div class="rounded-xl p-5 text-center transition-all hover:scale-105" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2" style="background: rgba(16, 185, 129, 0.2);">
                    <i class="fas fa-check-circle text-2xl" style="color: #10b981;"></i>
                </div>
                <p class="text-gray-600 text-sm">✅ المكتملة</p>
                <p class="text-3xl font-bold mt-1" id="completedBookings" style="color: #10b981;">0</p>
                <p class="text-xs mt-1" id="completedChange">
                    <i class="fas fa-chart-line"></i> <span class="text-green-600">+0%</span>
                </p>
            </div>

            <div class="rounded-xl p-5 text-center transition-all hover:scale-105" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2" style="background: rgba(239, 68, 68, 0.2);">
                    <i class="fas fa-ban text-2xl" style="color: #ef4444;"></i>
                </div>
                <p class="text-gray-600 text-sm">❌ الملغية</p>
                <p class="text-3xl font-bold mt-1" id="cancelledBookings" style="color: #ef4444;">0</p>
                <p class="text-xs mt-1" id="cancelledChange">
                    <i class="fas fa-chart-line"></i> <span class="text-red-600">+0%</span>
                </p>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Line Chart - Daily Bookings --}}
            <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <h3 class="text-lg font-bold mb-4" style="color: #2B1E1A;">📈 توزيع الحجوزات اليومية</h3>
                <canvas id="dailyBookingsChart" height="250"></canvas>
            </div>

            {{-- Pie Chart - Status Distribution --}}
            <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <h3 class="text-lg font-bold mb-4" style="color: #2B1E1A;">🥧 توزيع الحجوزات حسب الحالة</h3>
                <canvas id="statusPieChart" height="250"></canvas>
            </div>
        </div>

        {{-- Second Row Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Bar Chart - Service Distribution --}}
            <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <h3 class="text-lg font-bold mb-4" style="color: #2B1E1A;">📊 الحجوزات حسب الخدمة</h3>
                <canvas id="serviceBarChart" height="250"></canvas>
            </div>

            {{-- Trend Chart - Weekly Comparison --}}
            <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <h3 class="text-lg font-bold mb-4" style="color: #2B1E1A;">📉 مقارنة أسبوعية (هذا الأسبوع vs الأسبوع الماضي)</h3>
                <canvas id="weeklyComparisonChart" height="250"></canvas>
            </div>
        </div>

        {{-- تم إزالة قسم نسب الحضور وعدم الحضور بالكامل --}}

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let dailyChart, pieChart, serviceChart, weeklyChart;

    document.getElementById('monthPicker').addEventListener('change', function() {
        loadStats(this.value);
    });

    function previousMonth() {
        let picker = document.getElementById('monthPicker');
        let date = new Date(picker.value + '-01');
        date.setMonth(date.getMonth() - 1);
        picker.value = date.toISOString().slice(0, 7);
        loadStats(picker.value);
    }

    function nextMonth() {
        let picker = document.getElementById('monthPicker');
        let date = new Date(picker.value + '-01');
        date.setMonth(date.getMonth() + 1);
        picker.value = date.toISOString().slice(0, 7);
        loadStats(picker.value);
    }

    async function loadStats(month) {
        try {
            const response = await fetch(`/staff/monthly-stats/data?month=${month}`);
            const data = await response.json();
            
            // Update cards
            document.getElementById('totalBookings').innerText = data.total || 0;
            document.getElementById('completedBookings').innerText = data.completed || 0;
            document.getElementById('cancelledBookings').innerText = data.cancelled || 0;
            // تم إزالة noshowBookings
            
            // Update percentages
            updatePercentage('totalChange', data.totalChange);
            updatePercentage('completedChange', data.completedChange);
            updatePercentage('cancelledChange', data.cancelledChange);
            // تم إزالة noshowChange
            
            // تم إزالة قسم attendance rates بالكامل
            
            // Update charts
            updateDailyChart(data.dailyData);
            updatePieChart(data.completed, data.cancelled, data.pending);
            updateServiceChart(data.serviceData);
            updateWeeklyChart(data.weeklyData);
            
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    function updatePercentage(elementId, change) {
        const element = document.getElementById(elementId);
        if (change > 0) {
            element.innerHTML = `<i class="fas fa-chart-line"></i> <span class="text-green-600">+${change}%</span> عن الشهر الماضي`;
        } else if (change < 0) {
            element.innerHTML = `<i class="fas fa-chart-line"></i> <span class="text-red-600">${change}%</span> عن الشهر الماضي`;
        } else {
            element.innerHTML = `<i class="fas fa-chart-line"></i> <span class="text-gray-500">0%</span> عن الشهر الماضي`;
        }
    }

    function updateDailyChart(dailyData) {
        if (dailyChart) dailyChart.destroy();
        const ctx = document.getElementById('dailyBookingsChart').getContext('2d');
        dailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyData.dates,
                datasets: [{
                    label: 'عدد الحجوزات',
                    data: dailyData.counts,
                    borderColor: '#B08D57',
                    backgroundColor: 'rgba(176, 141, 87, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }

    function updatePieChart(completed, cancelled, pending) {
        if (pieChart) pieChart.destroy();
        const ctx = document.getElementById('statusPieChart').getContext('2d');
        pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['✅ مكتمل', '❌ ملغي', '⏳ قيد الانتظار'],
                datasets: [{
                    data: [completed, cancelled, pending],
                    backgroundColor: ['#10b981', '#ef4444', '#6b7280'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    function updateServiceChart(serviceData) {
        if (serviceChart) serviceChart.destroy();
        const ctx = document.getElementById('serviceBarChart').getContext('2d');
        serviceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: serviceData.labels,
                datasets: [{
                    label: 'عدد الحجوزات',
                    data: serviceData.counts,
                    backgroundColor: '#B08D57',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }

    function updateWeeklyChart(weeklyData) {
        if (weeklyChart) weeklyChart.destroy();
        const ctx = document.getElementById('weeklyComparisonChart').getContext('2d');
        weeklyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
                datasets: [
                    {
                        label: 'هذا الأسبوع',
                        data: weeklyData.thisWeek,
                        backgroundColor: '#B08D57',
                        borderRadius: 8
                    },
                    {
                        label: 'الأسبوع الماضي',
                        data: weeklyData.lastWeek,
                        backgroundColor: '#D4C4B0',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }

    // Initial load
    loadStats(document.getElementById('monthPicker').value);
</script>

<style>
    canvas {
        max-height: 300px;
    }
</style>
@endsection