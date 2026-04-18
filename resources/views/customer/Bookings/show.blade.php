@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                
                {{-- رأس الصفحة --}}
                <div class="rounded-2xl shadow-md p-6 text-center mb-6" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <i class="fas fa-receipt text-3xl mb-2" style="color: #F3EDE6;"></i>
                    <h1 class="text-xl font-bold" style="color: #F3EDE6;">📋 تفاصيل الحجز</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">معلومات حجزك الكاملة</p>
                </div>

                {{-- حالة الحجز --}}
                <div class="rounded-xl overflow-hidden mb-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <div class="px-5 py-3 flex justify-between items-center">
                        <span class="text-sm" style="color: #7C8574;">حالة الحجز</span>
                        @if($booking->status == 'confirmed')
                            @php
                                $isPast = $booking->booking_date < today();
                            @endphp
                            @if($isPast)
                                <span class="px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    <i class="fas fa-check-circle ml-1"></i> مكتمل ✅
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(16, 185, 129, 0.1); color: #065f46;">
                                    <i class="fas fa-check-circle ml-1"></i> مؤكد
                                </span>
                            @endif
                        @elseif($booking->status == 'cancelled')
                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                                <i class="fas fa-ban ml-1"></i> ملغي
                            </span>
                        @elseif($booking->status == 'completed')
                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="fas fa-check-circle ml-1"></i> مكتمل ✅
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="fas fa-clock ml-1"></i> قيد الانتظار
                            </span>
                        @endif
                    </div>
                </div>

                {{-- معلومات الحجز --}}
                <div class="rounded-xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <div class="px-5 py-3 border-b" style="background: rgba(176, 141, 87, 0.05); border-color: rgba(176, 141, 87, 0.1);">
                        <h2 class="font-bold text-sm" style="color: #2B1E1A;">
                            <i class="fas fa-info-circle ml-2" style="color: #B08D57;"></i> معلومات الحجز
                        </h2>
                    </div>
                    <div class="p-5 space-y-3">
                        
                        {{-- التاريخ --}}
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: #7C8574;">📅 التاريخ</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                        </div>

                        {{-- الوقت --}}
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: #7C8574;">⏰ الوقت</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span>
                        </div>

                        {{-- الخدمة --}}
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: #7C8574;">💅 الخدمة</span>
                            <span class="font-medium" style="color: #2B1E1A;">
                                @if($booking->service_type == 'classic') Classic Set
                                @elseif($booking->service_type == 'wet') Wet Set
                                @elseif($booking->service_type == 'wispy') Wispy Set
                                @elseif($booking->service_type == 'volume') Volume Set
                                @elseif($booking->service_type == 'anime') Anime Set
                                @elseif($booking->service_type == 'removal') إزالة رموش
                                @else {{ $booking->service_type }}
                                @endif
                            </span>
                        </div>

                        {{-- الموظفة (لغير الإزالة فقط) --}}
                        @if($booking->service_type != 'removal')
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: #7C8574;">💇‍♀️ الموظفة</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ $booking->staff->name ?? 'غير محدد' }}</span>
                        </div>
                        @endif

                        {{-- الموقع --}}
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: #7C8574;">📍 الموقع</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ $booking->location == 'salon' ? 'في الصالون' : 'خدمة منزلية' }}</span>
                        </div>

                        {{-- مدة الرموش --}}
                        @if($booking->lash_duration)
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: #7C8574;">⏰ مدة الرموش</span>
                            <span class="font-medium" style="color: #2B1E1A;">
                                @if($booking->lash_duration == 'weekly') أسبوعية
                                @elseif($booking->lash_duration == 'monthly') شهرية
                                @else مرة واحدة
                                @endif
                            </span>
                        </div>
                        @endif

                        {{-- شكل العين (لغير الإزالة فقط) --}}
                        @if($booking->service_type != 'removal' && $booking->eye_shape)
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: #7C8574;">👁️ شكل العين</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ $booking->eye_shape }}</span>
                        </div>
                        @endif

                        {{-- التفضيل (لغير الإزالة فقط) --}}
                        @if($booking->service_type != 'removal' && $booking->style_preference)
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: #7C8574;">🎨 التفضيل</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ $booking->style_preference }}</span>
                        </div>
                        @endif

                        {{-- السعر --}}
                        @if($booking->status != 'cancelled')
                        <div class="flex justify-between items-center pt-3 mt-2 border-t" style="border-color: rgba(176, 141, 87, 0.1);">
                            <span class="font-bold" style="color: #2B1E1A;">💰 السعر</span>
                            <span class="text-2xl font-bold" style="color: #B08D57;">{{ $booking->price }} د.أ</span>
                        </div>
                        @endif

                        {{-- زر التقييم (للحجوزات المكتملة فقط) --}}
                        @if(($booking->status == 'completed' || ($booking->status == 'confirmed' && $booking->booking_date < today())) && !$booking->review)
                        <div class="mt-4 pt-3 border-t" style="border-color: rgba(176, 141, 87, 0.1);">
                            <a href="{{ route('customer.reviews.create', $booking->id) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold transition hover:shadow-md w-full justify-center"
                               style="background: #B08D57; color: #F3EDE6;">
                                <i class="fas fa-star ml-1"></i> ⭐ قيمي تجربتك
                            </a>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- ========== 🔴 زر إرسال الموقع للخدمة المنزلية (واتساب) 🔴 ========== --}}
                @if($booking->location == 'home' && $booking->status == 'confirmed')
                    @php
                        // رقم واتساب الصالون (غيريه حسب رقمك)
                        $salonPhone = '962791234567';
                        
                        // رسالة جاهزة تحتوي على تفاصيل الحجز
                        $message = "📍 *خدمة منزلية - Lashe Out* 📍\n\n";
                        $message .= "👤 *الاسم:* {$booking->user->name}\n";
                        $message .= "📅 *التاريخ:* " . \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') . "\n";
                        $message .= "⏰ *الوقت:* " . \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') . "\n";
                        $message .= "💅 *الخدمة:* ";
                        if($booking->service_type == 'classic') $message .= "Classic Set";
                        elseif($booking->service_type == 'wet') $message .= "Wet Set";
                        elseif($booking->service_type == 'wispy') $message .= "Wispy Set";
                        elseif($booking->service_type == 'volume') $message .= "Volume Set";
                        elseif($booking->service_type == 'anime') $message .= "Anime Set";
                        else $message .= $booking->service_type;
                        $message .= "\n\n";
                        $message .= "📍 *الموقع:* (الرجاء إرسال موقعك)";
                        
                        $whatsappLink = "https://wa.me/{$salonPhone}?text=" . urlencode($message);
                    @endphp
                    
                    <div class="mt-4 p-4 rounded-xl text-center" style="background: rgba(37, 211, 102, 0.1); border: 1px solid #25D366; border-radius: 16px;">
                        <div class="flex items-center justify-center gap-2 mb-2">
                            <i class="fab fa-whatsapp text-2xl" style="color: #25D366;"></i>
                            <span class="font-bold" style="color: #2B1E1A;">📍 لتأكيد خدمتك المنزلية</span>
                        </div>
                        <p class="text-sm mb-3" style="color: #7C8574;">أرسلي موقعك عبر واتساب لتأكيد الحجز</p>
                        <a href="{{ $whatsappLink }}" 
                           target="_blank"
                           class="inline-flex items-center justify-center gap-2 w-full py-3 rounded-xl font-bold transition hover:opacity-80"
                           style="background: #25D366; color: white;">
                            <i class="fab fa-whatsapp text-xl"></i>
                            📍 إرسال موقعي عبر واتساب
                        </a>
                        <p class="text-xs mt-2" style="color: #7C8574;">سيتم فتح واتساب، اضغطي إرسال لمشاركة موقعك</p>
                    </div>
                @endif

                {{-- رسالة للحجز الملغي --}}
                @if($booking->status == 'cancelled')
                <div class="rounded-xl p-4 mt-6 text-center" style="background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.2);">
                    <i class="fas fa-info-circle text-lg ml-2" style="color: #dc2626;"></i>
                    <p class="text-sm inline" style="color: #dc2626;">❌ تم إلغاء هذا الحجز</p>
                </div>
                @endif

                {{-- أزرار الإجراءات --}}
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('customer.bookings.index') }}" class="flex-1 text-center py-3 rounded-xl font-bold text-sm transition hover:opacity-80" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                        <i class="fas fa-arrow-right ml-2"></i> رجوع
                    </a>
                    @if($booking->status == 'confirmed' && $booking->booking_date >= today())
                    <button type="button" onclick="openCancelModal()" class="flex-1 py-3 rounded-xl font-bold text-sm transition hover:opacity-80" style="background: #dc2626; color: white;">
                        <i class="fas fa-trash-alt ml-2"></i> إلغاء الحجز
                    </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- مودال الإلغاء --}}
@if($booking->status == 'confirmed' && $booking->booking_date >= today())
<div id="cancelModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-sm mx-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);">
        <div class="p-4 text-center" style="background: #dc2626;">
            <i class="fas fa-exclamation-triangle text-2xl mb-1" style="color: white;"></i>
            <h3 class="font-bold text-lg" style="color: white;">تأكيد الإلغاء</h3>
        </div>
        <div class="p-6 text-center">
            <i class="fas fa-calendar-times text-5xl mb-3" style="color: #dc2626;"></i>
            <p class="font-medium" style="color: #2B1E1A;">هل أنت متأكدة من إلغاء هذا الحجز؟</p>
            <p class="text-sm mt-2" style="color: #7C8574;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</p>
        </div>
        <div class="p-4 flex gap-3 border-t" style="border-color: rgba(176, 141, 87, 0.1);">
            <button onclick="closeCancelModal()" class="flex-1 py-2 rounded-xl transition hover:opacity-80" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                تراجع
            </button>
            <form action="{{ route('customer.bookings.cancel', $booking->id) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2 rounded-xl transition hover:opacity-80" style="background: #dc2626; color: white;">
                    نعم، إلغاء
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
        document.getElementById('cancelModal').classList.add('flex');
    }
    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.getElementById('cancelModal').classList.remove('flex');
    }
    document.getElementById('cancelModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeCancelModal();
    });
</script>
@endif
@endsection