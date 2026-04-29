@extends('layouts.app')

@section('content')
<div style="display: flex; min-height: 100vh; background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    
    {{-- سايد بار --}}
    <div style="width: 220px; background: white; border-right: 1px solid rgba(200,162,122,0.1); padding: 25px 15px; display: flex; flex-direction: column; text-align: center; position: fixed; left: 0; top: 64px; bottom: 0; z-index: 30;">
        <div style="width: 55px; height: 55px; margin: 0 auto 12px; border-radius: 50%; background: linear-gradient(135deg, #C8A27A, #8B6B4A); display: flex; align-items: center; justify-content: center; font-size: 24px; color: white;">👑</div>
        <h4 style="color: #8B6B4A; font-weight: bold; font-size: 15px;">{{ auth()->user()->name }}</h4>
        <p style="color: #C8A27A; font-size: 12px;">المالك</p>
        <div style="border-top: 1px solid rgba(200,162,122,0.1); margin: 15px 0;"></div>
        <a href="{{ route('owner.dashboard') }}" style="display: block; padding: 10px; border-radius: 10px; text-decoration: none; color: #8B6B4A; font-weight: bold; font-size: 14px; margin-bottom: 10px;">📊 نظرة عامة</a>
        <div style="flex: 1;"></div>
        <div style="position: relative;" x-data="{ settings: false }">
            <button @click="settings = !settings" @click.away="settings = false" style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; border-radius: 12px; border: none; background: rgba(200,162,122,0.08); cursor: pointer; width: 100%; color: #8B6B4A; font-weight: bold; font-size: 14px;">⚙️ الإعدادات <span style="font-size: 10px;">▼</span></button>
            <div x-show="settings" x-cloak style="position: absolute; left: 10px; bottom: 50px; width: 170px; background: white; border-radius: 14px; box-shadow: 0 8px 25px rgba(0,0,0,0.12); border: 1px solid rgba(200,162,122,0.15); overflow: hidden; z-index: 60;">
                <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 10px; padding: 11px 15px; text-decoration: none; color: #666; font-size: 13px;">👤 الملف الشخصي</a>
                <hr style="border-color: rgba(200,162,122,0.1); margin: 0;">
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" style="display: flex; align-items: center; gap: 10px; padding: 11px 15px; border: none; background: none; cursor: pointer; width: 100%; color: #ef4444; font-size: 13px;">🚪 تسجيل خروج</button></form>
            </div>
        </div>
    </div>

    {{-- المحتوى --}}
    <div style="flex: 1; padding: 25px; margin-left: 240px;">
        
        <div class="rounded-2xl p-6 text-center mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <h1 class="text-2xl font-bold" style="color: #F3EDE6;">👑 مرحباً {{ auth()->user()->name }}! يومك سعيد ✨</h1>
            <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">📅 {{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l j F Y') }}</p>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <a href="#" onclick="event.preventDefault(); document.getElementById('financePasswordModal').classList.remove('hidden'); document.getElementById('financePasswordModal').classList.add('flex')" class="rounded-xl p-5 text-center transition hover:-translate-y-1 hover:shadow-lg" style="text-decoration: none; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                <div class="text-4xl mb-2">💰</div><p class="font-bold text-lg" style="color: #B08D57;">المالية</p><p class="text-xs mt-1" style="color: #7C8574;">🔒 محمي بكلمة مرور</p>
            </a>
            <a href="{{ route('owner.bookings') }}" class="rounded-xl p-5 text-center transition hover:-translate-y-1 hover:shadow-lg" style="text-decoration: none; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                <div class="text-4xl mb-2">📅</div><p class="font-bold text-lg" style="color: #B08D57;">الحجوزات</p><p class="text-xs mt-1" style="color: #7C8574;">{{ $todayBookings ?? 0 }} اليوم</p>
            </a>
            <a href="{{ route('owner.staff') }}" class="rounded-xl p-5 text-center transition hover:-translate-y-1 hover:shadow-lg" style="text-decoration: none; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                <div class="text-4xl mb-2">👩‍💼</div><p class="font-bold text-lg" style="color: #B08D57;">الموظفات</p><p class="text-xs mt-1" style="color: #7C8574;">إدارة الفريق</p>
            </a>
            <a href="{{ route('owner.customers') }}" class="rounded-xl p-5 text-center transition hover:-translate-y-1 hover:shadow-lg" style="text-decoration: none; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                <div class="text-4xl mb-2">👥</div><p class="font-bold text-lg" style="color: #B08D57;">العملاء</p><p class="text-xs mt-1" style="color: #7C8574;">{{ $totalCustomers ?? 0 }} عميلة</p>
            </a>
            <a href="{{ route('owner.reviews') }}" class="rounded-xl p-5 text-center transition hover:-translate-y-1 hover:shadow-lg" style="text-decoration: none; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                <div class="text-4xl mb-2">⭐</div><p class="font-bold text-lg" style="color: #B08D57;">التقييمات</p><p class="text-xs mt-1" style="color: #7C8574;">{{ number_format($averageRating ?? 0, 1) }} ⭐</p>
            </a>
            <a href="{{ route('owner.schedule') }}" class="rounded-xl p-5 text-center transition hover:-translate-y-1 hover:shadow-lg" style="text-decoration: none; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                <div class="text-4xl mb-2">🕐</div><p class="font-bold text-lg" style="color: #B08D57;">أوقات الدوام</p><p class="text-xs mt-1" style="color: #7C8574;">ساعات العمل</p>
            </a>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="rounded-xl p-5" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                <h4 class="font-bold mb-3" style="color: #2B1E1A;">📈 نمو العملاء</h4>
                <div style="position: relative; height: 250px;"><canvas id="customerGrowthChart"></canvas></div>
            </div>
            <div class="rounded-xl p-5" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                <h4 class="font-bold mb-3" style="color: #2B1E1A;">👩‍💼 أداء الموظفات</h4>
                <div style="position: relative; height: 250px;"><canvas id="staffPerformanceChart"></canvas></div>
            </div>
        </div>

    </div>
</div>

{{-- نافذة كلمة المرور --}}
<div id="financePasswordModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-sm mx-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);">
        <div class="p-4 text-center" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <h3 class="font-bold text-lg" style="color: #F3EDE6;">🔒 القسم المالي</h3>
        </div>
        <div class="p-6 text-center">
            <div class="text-4xl mb-4">🔒</div>
            <p class="text-sm mb-4" style="color: #7C8574;">يرجى إدخال كلمة المرور للوصول</p>
            <form method="POST" action="{{ route('owner.verify-finance-login') }}">
                @csrf
                <input type="password" name="password" placeholder="كلمة المرور" required class="w-full px-4 py-3 rounded-xl text-center mb-3" style="border: 1px solid rgba(176, 141, 87, 0.3);">
                @if(session('finance_error'))
                    <p class="text-sm mb-3" style="color: #ef4444;">❌ {{ session('finance_error') }}</p>
                @endif
                <button type="submit" class="w-full py-3 rounded-xl font-bold text-white transition" style="background: #B08D57;">✅ دخول</button>
            </form>
            <button onclick="document.getElementById('financePasswordModal').classList.add('hidden')" class="mt-3 text-sm" style="color: #7C8574;">✕ إلغاء</button>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('customerGrowthChart'), {
    type: 'line', data: { labels: {!! json_encode($customerGrowthMonths ?? []) !!}, datasets: [{ label: 'عملاء جدد', data: {!! json_encode($customerGrowthData ?? []) !!}, borderColor: '#B08D57', backgroundColor: 'rgba(176,141,87,0.1)', fill: true, tension: 0.4, pointBackgroundColor: '#B08D57', pointRadius: 5 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('staffPerformanceChart'), {
    type: 'bar', data: { labels: {!! json_encode(array_column($staffPerformance ?? [], 'name')) !!}, datasets: [{ label: 'الحجوزات', data: {!! json_encode(array_column($staffPerformance ?? [], 'total_bookings')) !!}, backgroundColor: '#B08D57', borderRadius: 8 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, indexAxis: 'y' }
});
</script>
@endsection