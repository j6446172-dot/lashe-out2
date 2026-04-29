@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                
                {{-- Header --}}
                <div class="rounded-2xl p-6 text-center mb-6 shadow-md"
                     style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <h1 class="text-2xl font-bold" style="color: #F3EDE6;">📅 الحجوزات</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">جميع حجوزات الصالون</p>
                </div>

                {{-- بطاقات --}}
                <div class="grid grid-cols-6 gap-3 mb-6">
                    <div class="rounded-xl p-4 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-xs" style="color: #7C8574;">📋 الكل</p>
                        <p class="text-xl font-black mt-1" style="color: #B08D57;">{{ $totalBookings ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-xs" style="color: #7C8574;">✅ مؤكدة</p>
                        <p class="text-xl font-black mt-1" style="color: #10b981;">{{ $confirmedCount ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-xs" style="color: #7C8574;">⏳ معلقة</p>
                        <p class="text-xl font-black mt-1" style="color: #f59e0b;">{{ $pendingCount ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-xs" style="color: #7C8574;">❌ ملغية</p>
                        <p class="text-xl font-black mt-1" style="color: #ef4444;">{{ $cancelledCount ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-xs" style="color: #7C8574;">📅 الغد</p>
                        <p class="text-xl font-black mt-1" style="color: #3b82f6;">{{ $tomorrowCount ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-xs" style="color: #7C8574;">🕐 الذروة</p>
                        <p class="text-lg font-black mt-1" style="color: #B08D57;">{{ $peakTime ?? '—' }}</p>
                    </div>
                </div>

                {{-- فلترة + أزرار --}}
                <div class="flex gap-3 mb-4 items-center">
                    <input type="text" id="searchInput" placeholder="🔍 بحث..." class="px-4 py-2.5 rounded-xl flex-1" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);" onkeyup="filterBookings()">
                    <select id="statusFilter" class="px-3 py-2.5 rounded-xl" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);" onchange="filterBookings()">
                        <option value="">كل الحالات</option>
                        <option value="confirmed">✅ مؤكد</option>
                        <option value="pending">⏳ معلق</option>
                        <option value="cancelled">❌ ملغي</option>
                    </select>
                    <select id="staffFilter" class="px-3 py-2.5 rounded-xl" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);" onchange="filterBookings()">
                        <option value="">كل الموظفات</option>
                        @foreach($staffList ?? [] as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('owner.dashboard') }}" class="px-5 py-2.5 rounded-xl font-bold transition" style="background: rgba(176, 141, 87, 0.1); color: #B08D57; text-decoration: none;">← العودة</a>
                </div>

                {{-- جدول --}}
                <div class="rounded-xl p-6 overflow-x-auto" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <table class="w-full text-right" style="border-collapse: collapse; min-width: 800px; font-size: 13px;">
                        <thead>
                            <tr style="border-bottom: 2px solid rgba(176, 141, 87, 0.2); color: #B08D57;">
                                <th class="p-3">#</th><th class="p-3">العميلة</th><th class="p-3">الخدمة</th><th class="p-3">الموظفة</th><th class="p-3">التاريخ</th><th class="p-3">الوقت</th><th class="p-3">السعر</th><th class="p-3">الحالة</th><th class="p-3">عرض</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allBookings ?? [] as $b)
                            <tr style="border-bottom: 1px solid rgba(176, 141, 87, 0.1);">
                                <td class="p-3 font-bold" style="color: #2B1E1A;">{{ $b->id }}</td>
                                <td class="p-3" style="color: #2B1E1A;">{{ $b->user->name ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $b->service ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $b->staff->name ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $b->booking_date ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $b->booking_time ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $b->price ?? '—' }} د.أ</td>
                                <td class="p-3">
                                    @if(($b->status ?? '') == 'confirmed')
                                    <span class="px-2 py-1 rounded-full text-xs font-bold" style="background: rgba(16, 185, 129, 0.15); color: #065f46;">✅ مؤكد</span>
                                    @elseif(($b->status ?? '') == 'pending')
                                    <span class="px-2 py-1 rounded-full text-xs font-bold" style="background: rgba(245, 158, 11, 0.15); color: #92400e;">⏳ معلق</span>
                                    @else
                                    <span class="px-2 py-1 rounded-full text-xs font-bold" style="background: rgba(239, 68, 68, 0.15); color: #991b1b;">❌ ملغي</span>
                                    @endif
                                </td>
                                <td class="p-3"><button onclick="showDetail({{ $b->id }})" class="text-xl hover:scale-110 transition">👁️</button></td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="p-8 text-center" style="color: #7C8574;">لا توجد حجوزات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="text-center mt-4 text-xs" style="color: #7C8574;">ℹ️ التأكيد والرفض من اختصاص الموظفة المسؤولة فقط</p>

            </div>
        </div>
    </div>
</div>

{{-- نافذة التفاصيل --}}
<div id="detailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-lg mx-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);">
        <div class="p-4 text-right" style="background: linear-gradient(135deg, #B08D57, #9a7848);"><h3 class="font-bold text-lg" style="color: #F3EDE6;">📋 تفاصيل الحجز</h3></div>
        <div class="p-6" id="modalContent"></div>
        <div class="p-4 flex justify-center border-t" style="border-color: rgba(176, 141, 87, 0.1);"><button onclick="closeModal()" class="px-6 py-2 rounded-lg" style="background: rgba(124, 133, 116, 0.1); color: #7C8574;">✕ إغلاق</button></div>
    </div>
</div>

<script>
function showDetail(id){document.getElementById('detailModal').classList.remove('hidden');document.getElementById('detailModal').classList.add('flex');document.getElementById('modalContent').innerHTML='<p class="text-center" style="color:#7C8574;">جاري التحميل...</p>';fetch(`/owner/booking-detail/${id}`).then(r=>r.json()).then(data=>{document.getElementById('modalContent').innerHTML=`<div class="mb-3"><strong>👤 العميلة:</strong> ${data.user?.name||'—'}</div><div class="mb-1" style="color:#666;">📧 ${data.user?.email||'—'}</div><div class="mb-3" style="color:#666;">📱 ${data.user?.phone||'—'}</div><div class="mb-3"><strong>💅 الخدمة:</strong> ${data.service||'—'} - ${data.price||0} د.أ</div><div class="mb-3"><strong>👩‍💼 الموظفة:</strong> ${data.staff?.name||'—'} ⭐ ${data.staff_rating||0}</div><div class="mb-1"><strong>📅 التاريخ:</strong> ${data.booking_date||'—'}</div><div class="mb-3"><strong>🕐 الوقت:</strong> ${data.booking_time||'—'}</div><div class="mb-3"><strong>📊 الحالة:</strong> ${data.status=='confirmed'?'✅ مؤكد':data.status=='pending'?'⏳ معلق':'❌ ملغي'}</div><div class="mb-3"><strong>📝 ملاحظات:</strong> ${data.notes||'لا توجد'}</div>`;});}
function closeModal(){document.getElementById('detailModal').classList.add('hidden');document.getElementById('detailModal').classList.remove('flex');}
document.getElementById('detailModal').addEventListener('click',function(e){if(e.target===this)closeModal();});
function filterBookings(){const s=document.getElementById('searchInput')?.value||'',t=document.getElementById('statusFilter')?.value||'',f=document.getElementById('staffFilter')?.value||'';let u='{{ route("owner.bookings") }}?';if(s)u+='search='+encodeURIComponent(s)+'&';if(t)u+='status='+t+'&';if(f)u+='staff='+f;window.location.href=u;}
document.addEventListener('DOMContentLoaded',function(){const p=new URLSearchParams(window.location.search);if(p.get('status'))document.getElementById('statusFilter').value=p.get('status');if(p.get('staff'))document.getElementById('staffFilter').value=p.get('staff');if(p.get('search'))document.getElementById('searchInput').value=p.get('search');});
</script>
@endsection