@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                
                {{-- Header --}}
                <div class="rounded-2xl p-6 text-center mb-6 shadow-md"
                     style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <h1 class="text-2xl font-bold" style="color: #F3EDE6;">👥 العملاء</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">قاعدة عملاء الصالون</p>
                </div>

                {{-- بطاقات --}}
                <div class="grid grid-cols-5 gap-4 mb-6">
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">👥 إجمالي العملاء</p>
                        <p class="text-2xl font-black mt-2" style="color: #B08D57;">{{ $totalCustomers ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">🆕 جدد هذا الشهر</p>
                        <p class="text-2xl font-black mt-2" style="color: #10b981;">{{ $newCustomers ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">🔄 عائدين</p>
                        <p class="text-2xl font-black mt-2" style="color: #3b82f6;">{{ $returningCustomers ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">🎁 نقاط الولاء</p>
                        <p class="text-2xl font-black mt-2" style="color: #f59e0b;">{{ number_format($totalLoyaltyPoints ?? 0) }}</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">⭐ متوسط التقييم</p>
                        <p class="text-2xl font-black mt-2" style="color: #f59e0b;">{{ number_format($avgCustomerRating ?? 0, 1) }} ⭐</p>
                    </div>
                </div>

                {{-- فلترة + زر العودة --}}
                <div class="flex gap-3 mb-4 items-center">
                    <input type="text" placeholder="🔍 بحث..." class="px-4 py-2.5 rounded-xl flex-1" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <select class="px-4 py-2.5 rounded-xl" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <option>الكل</option>
                        <option>🏷️ VIP فقط</option>
                    </select>
                    <a href="{{ route('owner.dashboard') }}" class="px-5 py-2.5 rounded-xl font-bold transition" style="background: rgba(176, 141, 87, 0.1); color: #B08D57; text-decoration: none;">← العودة</a>
                </div>

                {{-- جدول --}}
                <div class="rounded-xl p-6 overflow-x-auto" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <table class="w-full text-right" style="border-collapse: collapse; min-width: 900px;">
                        <thead>
                            <tr style="border-bottom: 2px solid rgba(176, 141, 87, 0.2); color: #B08D57;">
                                <th class="p-3">العميلة</th>
                                <th class="p-3">الهاتف</th>
                                <th class="p-3">أول حجز</th>
                                <th class="p-3">عدد الحجوزات</th>
                                <th class="p-3">إجمالي الصرف</th>
                                <th class="p-3">🎁 نقاط</th>
                                <th class="p-3">تقييم</th>
                                <th class="p-3">VIP</th>
                                <th class="p-3">عرض</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customersList ?? [] as $customer)
                            <tr style="border-bottom: 1px solid rgba(176, 141, 87, 0.1);">
                                <td class="p-3 font-bold" style="color: #2B1E1A;">{{ $customer['name'] }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $customer['phone'] ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $customer['first_booking'] ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $customer['total_bookings'] }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ number_format($customer['total_spent']) }} د.أ</td>
                                <td class="p-3 font-bold" style="color: #f59e0b;">🎁 {{ $customer['loyalty_points'] }}</td>
                                <td class="p-3" style="color: #f59e0b;">⭐ {{ number_format($customer['rating'], 1) }}</td>
                                <td class="p-3">{{ $customer['is_vip'] ? '🏷️' : '—' }}</td>
                                <td class="p-3"><button onclick="showCustomerDetail({{ $customer['id'] }})" class="text-xl hover:scale-110 transition">👁️</button></td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="p-8 text-center" style="color: #7C8574;">لا يوجد عملاء</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- نافذة تفاصيل --}}
<div id="customerModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-md mx-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);">
        <div class="p-4 text-right" style="background: linear-gradient(135deg, #B08D57, #9a7848);"><h3 class="font-bold text-lg" style="color: #F3EDE6;">👤 تفاصيل العميلة</h3></div>
        <div class="p-6" id="customerContent"></div>
        <div class="p-4 flex gap-3 justify-center border-t" style="border-color: rgba(176, 141, 87, 0.1);">
            <button onclick="closeCustomerModal()" class="px-6 py-2 rounded-lg" style="background: rgba(124, 133, 116, 0.1); color: #7C8574;">✕ إغلاق</button>
        </div>
    </div>
</div>

<script>
function showCustomerDetail(id) {
    document.getElementById('customerModal').classList.remove('hidden'); document.getElementById('customerModal').classList.add('flex');
    document.getElementById('customerContent').innerHTML = '<p class="text-center" style="color:#7C8574;">جاري التحميل...</p>';
    fetch(`/owner/customer-detail/${id}`).then(r=>r.json()).then(data=>{
        document.getElementById('customerContent').innerHTML = `
            <div class="mb-4"><strong style="color:#2B1E1A;">👤 معلومات</strong></div>
            <div class="mb-4 p-4 rounded-xl" style="background:#f9f9f9;"><p>الاسم: ${data.name}</p><p>الإيميل: ${data.email||'—'}</p><p>الهاتف: ${data.phone||'—'}</p></div>
            <div class="mb-4"><strong style="color:#2B1E1A;">📊 إحصائيات</strong></div>
            <div class="mb-4 p-4 rounded-xl" style="background:#f9f9f9;"><p>📅 عدد الحجوزات: ${data.total_bookings}</p><p>💰 إجمالي الصرف: ${data.total_spent} د.أ</p><p>🎁 نقاط الولاء: ${data.loyalty_points}</p><p>⭐ تقييمها: ${data.rating}</p></div>
            <div class="mb-4"><strong style="color:#2B1E1A;">📅 آخر الحجوزات</strong></div>
            <div class="p-4 rounded-xl" style="background:#f9f9f9;">${data.recent_bookings&&data.recent_bookings.length>0?data.recent_bookings.map(b=>`<p>${b.date} - ${b.service} - ${b.staff}</p>`).join(''):'<p style="color:#999;">لا توجد حجوزات</p>'}</div>`;
    });
}
function closeCustomerModal(){ document.getElementById('customerModal').classList.add('hidden'); document.getElementById('customerModal').classList.remove('flex'); }
document.getElementById('customerModal').addEventListener('click',function(e){if(e.target===this)closeCustomerModal();});
</script>
@endsection