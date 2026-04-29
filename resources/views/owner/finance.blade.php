@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                
                {{-- Header --}}
                <div class="rounded-2xl p-6 text-center mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <h1 class="text-2xl font-bold" style="color: #F3EDE6;">💰 المالية</h1>
                </div>

                @if(session('success'))
                <div class="mb-4 p-3 rounded-xl text-center text-sm font-bold" style="background: rgba(16, 185, 129, 0.15); color: #065f46;">
                    ✅ {{ session('success') }}
                </div>
                @endif

                {{-- بطاقات --}}
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">💰 الإيرادات</p>
                        <p class="text-2xl font-black mt-2" style="color: #B08D57;">{{ number_format($monthlyRevenue ?? 0) }} د.أ</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">📉 المصاريف</p>
                        <p class="text-2xl font-black mt-2" style="color: #ef4444;">{{ number_format(($salaries ?? 0) + ($materials ?? 0) + ($rent ?? 0)) }} د.أ</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">📈 صافي الربح</p>
                        <p class="text-2xl font-black mt-2" style="color: {{ ($netProfit ?? 0) >= 0 ? '#10b981' : '#ef4444' }};">{{ number_format($netProfit ?? 0) }} د.أ</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">📊 نقطة التعادل</p>
                        <p class="text-md font-black mt-2" style="color: {{ ($monthlyRevenue ?? 0) >= ($salaries + $materials + $rent) ? '#10b981' : '#ef4444' }};">
                            {{ ($monthlyRevenue ?? 0) >= ($salaries + $materials + $rent) ? '✅ تم التغطية' : '⚠️ لم تتم التغطية' }}
                        </p>
                    </div>
                </div>

                {{-- أزرار --}}
                <div class="flex gap-3 mb-6">
                    <a href="{{ route('owner.dashboard') }}" class="px-5 py-2.5 rounded-xl font-bold transition" style="background: rgba(176, 141, 87, 0.1); color: #B08D57; text-decoration: none;">← العودة</a>
                    <button onclick="document.getElementById('deductionModal').classList.remove('hidden');document.getElementById('deductionModal').classList.add('flex')" class="px-5 py-2.5 rounded-xl font-bold text-white" style="background: #ef4444;">➖ خصم</button>
                    <button onclick="document.getElementById('bonusModal').classList.remove('hidden');document.getElementById('bonusModal').classList.add('flex')" class="px-5 py-2.5 rounded-xl font-bold text-white" style="background: #10b981;">➕ مكافأة</button>
                </div>

                {{-- مخططات --}}
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="rounded-xl p-5" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <h4 class="font-bold mb-3" style="color: #2B1E1A;">📊 الإيرادات الشهرية</h4>
                        <div style="position: relative; height: 250px;"><canvas id="revenueChart"></canvas></div>
                    </div>
                    <div class="rounded-xl p-5" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <h4 class="font-bold mb-3" style="color: #2B1E1A;">🥧 توزيع المصاريف</h4>
                        <div style="position: relative; height: 250px;"><canvas id="expensesChart"></canvas></div>
                    </div>
                </div>

                {{-- مقارنة بالشهر الماضي --}}
                <div class="rounded-xl p-6 mb-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <h4 class="text-lg font-bold mb-4" style="color: #2B1E1A;">📊 مقارنة بالشهر الماضي</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right" style="border-collapse: collapse; font-size: 14px;">
                            <thead><tr style="border-bottom: 2px solid rgba(176, 141, 87, 0.2); color: #B08D57;"><th class="p-3">المؤشر</th><th class="p-3">الحالي</th><th class="p-3">السابق</th><th class="p-3">التغيير</th></tr></thead>
                            <tbody>
                                @php $revChange = ($lastMonthRevenue ?? 0) > 0 ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100) : 0; @endphp
                                <tr style="border-bottom: 1px solid rgba(176, 141, 87, 0.1);"><td class="p-3 font-bold">💰 الإيرادات</td><td class="p-3">{{ number_format($monthlyRevenue) }} د.أ</td><td class="p-3">{{ number_format($lastMonthRevenue) }} د.أ</td><td class="p-3 font-bold" style="color:{{ $revChange >= 0 ? '#10b981' : '#ef4444' }};">{{ number_format($revChange, 1) }}%</td></tr>
                                @php $bookChange = ($lastMonthBookings ?? 0) > 0 ? (($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings * 100) : 0; @endphp
                                <tr style="border-bottom: 1px solid rgba(176, 141, 87, 0.1);"><td class="p-3 font-bold">📅 الحجوزات</td><td class="p-3">{{ $thisMonthBookings }}</td><td class="p-3">{{ $lastMonthBookings }}</td><td class="p-3 font-bold" style="color:{{ $bookChange >= 0 ? '#10b981' : '#ef4444' }};">{{ number_format($bookChange, 1) }}%</td></tr>
                                @php $profitChange = ($lastMonthNetProfit ?? 0) > 0 ? (($netProfit - $lastMonthNetProfit) / $lastMonthNetProfit * 100) : 0; @endphp
                                <tr><td class="p-3 font-bold">📈 صافي الربح</td><td class="p-3">{{ number_format($netProfit) }} د.أ</td><td class="p-3">{{ number_format($lastMonthNetProfit) }} د.أ</td><td class="p-3 font-bold" style="color:{{ $profitChange >= 0 ? '#10b981' : '#ef4444' }};">{{ number_format($profitChange, 1) }}%</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- نافذة الخصم --}}
<div id="deductionModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-md mx-4 p-6" style="background: rgba(255, 255, 255, 0.95);">
        <h3 class="font-bold text-lg mb-4" style="color: #ef4444;">➖ إضافة خصم</h3>
        <form method="POST" action="{{ url('/owner/save-finance') }}">
            @csrf
            <input type="hidden" name="type" value="deduction">
            <select name="staff_id" class="w-full mb-3 px-4 py-3 rounded-xl" style="border: 1px solid #ddd;">
                <option value="">اختيار الموظفة</option>
                @foreach($staffList ?? [] as $s)<option value="{{ $s['id'] }}">{{ $s['name'] }}</option>@endforeach
            </select>
            <input type="number" name="amount" placeholder="المبلغ" class="w-full mb-3 px-4 py-3 rounded-xl" style="border: 1px solid #ddd;">
            <input type="text" name="reason" placeholder="السبب" class="w-full mb-4 px-4 py-3 rounded-xl" style="border: 1px solid #ddd;">
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 rounded-xl font-bold text-white" style="background: #ef4444;">💾 حفظ</button>
                <button type="button" onclick="document.getElementById('deductionModal').classList.add('hidden')" class="flex-1 py-3 rounded-xl font-bold" style="background: rgba(124,133,116,0.1); color: #7C8574;">✕ إلغاء</button>
            </div>
        </form>
    </div>
</div>

{{-- نافذة المكافأة --}}
<div id="bonusModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-md mx-4 p-6" style="background: rgba(255, 255, 255, 0.95);">
        <h3 class="font-bold text-lg mb-4" style="color: #10b981;">➕ إضافة مكافأة</h3>
        <form method="POST" action="{{ url('/owner/save-finance') }}">
            @csrf
            <input type="hidden" name="type" value="bonus">
            <select name="staff_id" class="w-full mb-3 px-4 py-3 rounded-xl" style="border: 1px solid #ddd;">
                <option value="">اختيار الموظفة</option>
                @foreach($staffList ?? [] as $s)<option value="{{ $s['id'] }}">{{ $s['name'] }}</option>@endforeach
            </select>
            <input type="number" name="amount" placeholder="المبلغ" class="w-full mb-3 px-4 py-3 rounded-xl" style="border: 1px solid #ddd;">
            <input type="text" name="reason" placeholder="السبب" class="w-full mb-4 px-4 py-3 rounded-xl" style="border: 1px solid #ddd;">
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 rounded-xl font-bold text-white" style="background: #10b981;">💾 حفظ</button>
                <button type="button" onclick="document.getElementById('bonusModal').classList.add('hidden')" class="flex-1 py-3 rounded-xl font-bold" style="background: rgba(124,133,116,0.1); color: #7C8574;">✕ إلغاء</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('revenueChart'),{type:'bar',data:{labels:{!! json_encode($chartMonths ?? []) !!},datasets:[{data:{!! json_encode($chartRevenue ?? []) !!},backgroundColor:'#B08D57',borderRadius:8}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}});
new Chart(document.getElementById('expensesChart'),{type:'doughnut',data:{labels:['رواتب','مواد','إيجار'],datasets:[{data:[{{ $salaries ?? 0 }},{{ $materials ?? 0 }},{{ $rent ?? 0 }}],backgroundColor:['#B08D57','#9a7848','#7C8574']}]},options:{responsive:true,maintainAspectRatio:false}});
</script>
@endsection