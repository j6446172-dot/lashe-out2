@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- ========== Progress Bar ========== --}}
        <div class="mb-12">
            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #10b981;">
                        <i class="fas fa-check text-xl" style="color: white;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #10b981;">شكل العين</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #10b981;">
                        <i class="fas fa-check text-xl" style="color: white;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #10b981;">الخدمة</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #B08D57;">
                        <i class="fas fa-calendar-alt text-xl" style="color: #F3EDE6;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #B08D57;">التاريخ</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2" style="background: rgba(176, 141, 87, 0.2);">
                        <i class="fas fa-user-check text-xl" style="color: #7C8574;"></i>
                    </div>
                    <span class="text-sm" style="color: #7C8574;">التأكيد</span>
                </div>
            </div>
            <div class="mt-4 h-2 rounded-full overflow-hidden" style="background: rgba(176, 141, 87, 0.2);">
                <div class="h-full rounded-full transition-all duration-500" style="width: 75%; background: #B08D57;"></div>
            </div>
        </div>

        {{-- ========== Main Card ========== --}}
        <div class="rounded-3xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
            
            <div class="px-8 py-8" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                <h2 class="text-3xl font-black mb-2" style="color: #F3EDE6;">📅 اختاري التاريخ والوقت المناسب</h2>
                <p class="text-lg" style="color: rgba(243, 237, 230, 0.8);">نحن في انتظارك في الموعد الذي يناسبك</p>
            </div>
            
            <div class="p-8">
                
                {{-- ========== رسائل الخطأ ========== --}}
                @if(session('error'))
                    <div class="rounded-2xl p-5 mb-6 animate-shake" style="background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.2);">
                        <div class="flex items-center gap-3" style="color: #dc2626;">
                            <i class="fas fa-exclamation-circle text-xl animate-pulse"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                {{-- ========== اقتراحات المواعيد الذكية ========== --}}
                @if(isset($suggestedDates) && count($suggestedDates) > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(176, 141, 87, 0.1);">
                            <i class="fas fa-star text-xl" style="color: #B08D57;"></i>
                        </div>
                        <label class="block font-bold text-right text-lg" style="color: #2B1E1A;">
                            ⭐ اقتراحات المواعيد المتاحة
                        </label>
                    </div>
                    <p class="text-sm text-right mb-4" style="color: #7C8574;">
                        هذه أقرب المواعيد المتاحة بناءً على حجوزات اليوم
                        <span class="text-xs">(بناءً على {{ \App\Models\User::where('role', 'staff')->count() }} موظفة)</span>
                    </p>
                    
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($suggestedDates as $suggestion)
                        <div class="suggested-date group rounded-xl p-4 cursor-pointer transition-all duration-300 transform hover:-translate-y-1" 
                             style="background: rgba(176, 141, 87, 0.08); border: 1px solid rgba(176, 141, 87, 0.2);" 
                             data-date="{{ $suggestion['date'] }}">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center group-hover:scale-110 transition" style="background: rgba(176, 141, 87, 0.15);">
                                        <i class="fas fa-calendar-day text-xl" style="color: #B08D57;"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-lg" style="color: #B08D57;">{{ $suggestion['day'] }}</div>
                                        <div class="text-sm" style="color: #7C8574;">{{ $suggestion['date'] }}</div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold" style="color: #B08D57;">{{ $suggestion['available_slots'] }}</div>
                                    <div class="text-xs" style="color: #7C8574;">مواعيد متاحة</div>
                                </div>
                                <div class="px-4 py-2 rounded-full text-sm font-bold shadow-md" style="background: #B08D57; color: #F3EDE6;">
                                    مُقترح ⭐
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center my-6">
                        <span class="text-sm" style="color: #7C8574;">أو اختاري تاريخ آخر</span>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('customer.bookings.step3.post') }}" id="bookingForm">
                    @csrf
                    
                    {{-- ========== اختيار التاريخ ========== --}}
                    <div class="mb-8">
                        <label class="block font-bold mb-4 text-right text-lg" style="color: #2B1E1A;">
                            <i class="fas fa-calendar-day ml-2" style="color: #B08D57;"></i> اختاري التاريخ
                        </label>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($availableDates as $date)
                                @php
                                    $carbonDate = \Carbon\Carbon::parse($date);
                                    $dayName = $carbonDate->format('D');
                                    $dayNameAr = match($dayName) {
                                        'Sat' => 'السبت', 'Sun' => 'الأحد', 'Mon' => 'الإثنين',
                                        'Tue' => 'الثلاثاء', 'Wed' => 'الأربعاء', 'Thu' => 'الخميس', 'Fri' => 'الجمعة'
                                    };
                                    $totalStaff = \App\Models\User::where('role', 'staff')->count();
                                    if ($totalStaff == 0) $totalStaff = 1;
                                    $maxPerDay = $totalStaff * 3;
                                    $bookingsCount = \App\Models\Booking::where('booking_date', $date)
                                        ->where('status', 'confirmed')
                                        ->count();
                                    $isBusy = $bookingsCount >= ($maxPerDay * 0.8);
                                @endphp
                                <div class="date-card group rounded-xl border-2 p-4 text-center transition-all duration-300 cursor-pointer transform hover:-translate-y-1" 
                                     style="background: rgba(255, 255, 255, 0.8); border-color: rgba(176, 141, 87, 0.2);" 
                                     data-value="{{ $date }}">
                                    <div class="text-2xl mb-2 transform transition group-hover:scale-110">
                                        <i class="fas fa-calendar-alt" style="color: #B08D57;"></i>
                                    </div>
                                    <div class="font-bold text-2xl" style="color: #2B1E1A;">{{ $carbonDate->format('d') }}</div>
                                    <div class="text-sm font-semibold" style="color: #7C8574;">{{ $dayNameAr }}</div>
                                    <div class="text-xs mt-1" style="color: #B9ADA3;">{{ $carbonDate->format('M Y') }}</div>
                                    @if($isBusy)
                                        <div class="mt-2 text-xs font-bold" style="color: #f59e0b;">🔥 مزدحم</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="booking_date" id="booking_date" required>
                    </div>
                    
                    {{-- ========== اختيار الوقت (ديناميكي عبر AJAX) ========== --}}
                    <div class="mb-8">
                        <label class="block font-bold mb-4 text-right text-lg" style="color: #2B1E1A;">
                            <i class="fas fa-clock ml-2" style="color: #B08D57;"></i> اختاري الوقت
                        </label>
                        
                        <div id="times-container" class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3">
                            <div class="text-center py-10 col-span-full" style="color: #7C8574;">
                                <i class="fas fa-calendar-day text-3xl mb-2 block"></i>
                                <p>الرجاء اختيار التاريخ أولاً</p>
                            </div>
                        </div>
                        <input type="hidden" name="booking_time" id="booking_time" required>
                    </div>
                    
                    {{-- ========== الأزرار ========== --}}
                    <div class="flex justify-between gap-4 mt-8">
                        <a href="{{ route('customer.bookings.step2') }}" class="px-8 py-3 rounded-xl font-bold transition-all duration-300 text-center" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                            <i class="fas fa-arrow-right ml-2"></i> السابق
                        </a>
                        <button type="submit" class="flex-1 font-bold py-3 rounded-xl transition-all duration-300 transform hover:scale-[1.02]" style="background: #B08D57; color: #F3EDE6;">
                            استمري <i class="fas fa-arrow-left mr-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ========== JavaScript مع AJAX ========== --}}
<script>
    // دالة لجلب الأوقات المتاحة باستخدام AJAX
    async function loadAvailableTimes(date) {
        const container = document.getElementById('times-container');
        
        // عرض مؤشر التحميل
        container.innerHTML = '<div class="text-center py-10 col-span-full" style="color: #7C8574;"><i class="fas fa-spinner fa-spin text-3xl mb-2 block"></i><p>جاري تحميل الأوقات...</p></div>';
        
        try {
            // ✅ إرسال طلب AJAX إلى الـ Route
            const response = await fetch(`/customer/get-available-times?date=${date}`);
            const times = await response.json();
            
            if (times.length === 0) {
                container.innerHTML = '<div class="text-center py-10 col-span-full" style="color: #dc2626;"><i class="fas fa-calendar-times text-3xl mb-2 block"></i><p>لا توجد مواعيد متاحة في هذا اليوم</p></div>';
                return;
            }
            
            // تنظيف الحاوية
            container.innerHTML = '';
            
            // عرض الأوقات
            times.forEach(time => {
                const timeCard = document.createElement('div');
                timeCard.className = 'time-card group rounded-xl border-2 p-3 text-center transition-all duration-300 cursor-pointer transform hover:-translate-y-1 relative';
                timeCard.style.background = time.available ? 'rgba(255, 255, 255, 0.8)' : '#fef2f2';
                timeCard.style.borderColor = time.available ? 'rgba(176, 141, 87, 0.2)' : '#fecaca';
                timeCard.style.opacity = time.available ? '1' : '0.5';
                timeCard.style.cursor = time.available ? 'pointer' : 'not-allowed';
                timeCard.dataset.value = time.time;
                timeCard.dataset.available = time.available;
                
                // إضافة علامة "ذروة" إذا كان الوقت مزدحماً ومتاحاً
                if (time.peak && time.available) {
                    const peakBadge = document.createElement('div');
                    peakBadge.className = 'absolute -top-2 left-1/2 transform -translate-x-1/2 text-white text-xs px-2 py-0.5 rounded-full';
                    peakBadge.style.background = '#f59e0b';
                    peakBadge.innerText = 'ذروة';
                    timeCard.appendChild(peakBadge);
                }
                
                // إضافة علامة "مكتمل" إذا كان الوقت غير متاح
                if (!time.available) {
                    const soldBadge = document.createElement('div');
                    soldBadge.className = 'absolute -top-2 left-1/2 transform -translate-x-1/2 text-white text-xs px-2 py-0.5 rounded-full';
                    soldBadge.style.background = '#dc2626';
                    soldBadge.innerText = 'مكتمل';
                    timeCard.appendChild(soldBadge);
                }
                
                // عرض الوقت
                const timeText = document.createElement('div');
                timeText.className = 'text-lg font-bold mt-1';
                timeText.style.color = time.available ? '#2B1E1A' : '#9ca3af';
                timeText.innerText = time.time;
                
                // عرض الفترة (صباحاً/ظهراً/مساءً)
                const periodText = document.createElement('div');
                periodText.className = 'text-xs mt-1';
                periodText.style.color = '#7C8574';
                periodText.innerText = time.period;
                
                timeCard.appendChild(timeText);
                timeCard.appendChild(periodText);
                
                // إضافة حدث النقر للوقت المتاح فقط
                if (time.available) {
                    timeCard.addEventListener('click', function() {
                        document.querySelectorAll('.time-card').forEach(c => {
                            c.classList.remove('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
                        });
                        this.classList.add('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
                        document.getElementById('booking_time').value = this.dataset.value;
                    });
                }
                
                container.appendChild(timeCard);
            });
        } catch (error) {
            console.error('Error loading times:', error);
            container.innerHTML = '<div class="text-center py-10 col-span-full" style="color: #dc2626;"><i class="fas fa-exclamation-circle text-3xl mb-2 block"></i><p>حدث خطأ في تحميل المواعيد</p></div>';
        }
    }
    
    // اختيار التاريخ (من البطاقات العادية)
    document.querySelectorAll('.date-card').forEach(card => {
        card.addEventListener('click', function() {
            // إزالة التحديد من جميع التواريخ
            document.querySelectorAll('.date-card').forEach(c => {
                c.classList.remove('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
                c.style.background = 'rgba(255,255,255,0.8)';
            });
            
            // تحديد التاريخ المختار
            this.classList.add('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
            this.style.background = 'rgba(176,141,87,0.08)';
            
            const selectedDate = this.dataset.value;
            document.getElementById('booking_date').value = selectedDate;
            
            // ✅ إعادة تعيين الوقت المختار
            document.getElementById('booking_time').value = '';
            
            // ✅ جلب الأوقات المتاحة لهذا التاريخ عبر AJAX
            loadAvailableTimes(selectedDate);
        });
    });
    
    // اختيار التاريخ المقترح
    document.querySelectorAll('.suggested-date').forEach(el => {
        el.addEventListener('click', function() {
            const date = this.dataset.date;
            document.getElementById('booking_date').value = date;
            
            // تمييز التاريخ في قائمة التواريخ
            document.querySelectorAll('.date-card').forEach(card => {
                if (card.dataset.value === date) {
                    card.classList.add('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
                    card.style.background = 'rgba(176,141,87,0.08)';
                } else {
                    card.classList.remove('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
                    card.style.background = 'rgba(255,255,255,0.8)';
                }
            });
            
            // ✅ إعادة تعيين الوقت المختار
            document.getElementById('booking_time').value = '';
            
            // ✅ جلب الأوقات المتاحة لهذا التاريخ عبر AJAX
            loadAvailableTimes(date);
            
            // تأثير بصري
            this.classList.add('ring-2', 'ring-[#B08D57]', 'scale-[1.02]');
            setTimeout(() => {
                this.classList.remove('ring-2', 'ring-[#B08D57]', 'scale-[1.02]');
            }, 300);
        });
    });
</script>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.3s ease-in-out;
    }
</style>
@endsection