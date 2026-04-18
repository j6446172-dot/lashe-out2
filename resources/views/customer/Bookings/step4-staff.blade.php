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
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #10b981;">
                        <i class="fas fa-check text-xl" style="color: white;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #10b981;">التاريخ</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #B08D57;">
                        <i class="fas fa-user-check text-xl" style="color: #F3EDE6;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #B08D57;">التأكيد</span>
                </div>
            </div>
            <div class="mt-4 h-2 rounded-full overflow-hidden" style="background: rgba(176, 141, 87, 0.2);">
                <div class="h-full rounded-full" style="width: 100%; background: #B08D57;"></div>
            </div>
        </div>

        {{-- ========== Main Card ========== --}}
        <div class="rounded-3xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
            
            <div class="px-8 py-8" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                <h2 class="text-3xl font-black mb-2" style="color: #F3EDE6;">💁‍♀️ اختاري الموظفة المناسبة لكِ</h2>
                <p class="text-lg" style="color: rgba(243, 237, 230, 0.8);">جميع موظفاتنا خبيرات ومعتمدات بأعلى المعايير</p>
            </div>
            
            <form method="POST" action="{{ route('customer.bookings.step4.post') }}" class="p-8" id="bookingForm">
                @csrf
                
                {{-- ========== اختيار الموظفة ========== --}}
                <div class="mb-8">
                    <label class="block font-bold mb-4 text-right text-lg" style="color: #2B1E1A;">
                        <i class="fas fa-female ml-2" style="color: #B08D57;"></i> من تفضلين تقديم الخدمة؟
                    </label>
                    
                    @if($availableStaff->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($availableStaff as $staffMember)
                            <div class="staff-card rounded-2xl border-2 p-4 transition-all duration-300 cursor-pointer" 
                                 style="background: rgba(255, 255, 255, 0.8); border-color: rgba(176, 141, 87, 0.2);" 
                                 data-value="{{ $staffMember->id }}">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: rgba(176, 141, 87, 0.15);">
                                        <i class="fas fa-user-circle text-3xl" style="color: #B08D57;"></i>
                                    </div>
                                    <div class="flex-1 text-right">
                                        <h4 class="font-bold" style="color: #2B1E1A;">{{ $staffMember->name }}</h4>
                                        <p class="text-xs mt-1" style="color: #7C8574;">{{ $staffMember->experience ?? 'خبيرة تجميل معتمدة' }}</p>
                                        <span class="text-xs inline-block px-2 py-1 rounded-full mt-2" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                            ✅ متاحة الآن
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="staff_id" id="staff_id" required>
                    @else
                        <div class="text-center py-8 rounded-2xl" style="background: rgba(220, 38, 38, 0.1);">
                            <i class="fas fa-clock text-4xl mb-2" style="color: #dc2626;"></i>
                            <p class="font-bold" style="color: #dc2626;">⚠️ جميع الموظفات مشغولات في هذا الوقت</p>
                            <a href="{{ route('customer.bookings.step3') }}" class="inline-block mt-3 font-bold transition hover:opacity-70" style="color: #B08D57;">
                                <i class="fas fa-arrow-left ml-1"></i> اختيار وقت آخر
                            </a>
                        </div>
                    @endif
                </div>
                
                {{-- ========== اختيار المكان ========== --}}
                <div class="mb-8">
                    <label class="block font-bold mb-4 text-right text-lg" style="color: #2B1E1A;">
                        <i class="fas fa-map-marker-alt ml-2" style="color: #B08D57;"></i> أين تفضلين تلقي الخدمة؟
                    </label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="location-option border-2 rounded-xl p-4 cursor-pointer transition-all duration-300 hover:shadow-md" 
                               style="border-color: rgba(176, 141, 87, 0.2);">
                            <input type="radio" name="location" value="salon" class="ml-3" required style="accent-color: #B08D57;">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-spa text-2xl" style="color: #B08D57;"></i>
                                <div>
                                    <span class="font-bold" style="color: #2B1E1A;">في الصالون</span>
                                    <p class="text-xs mt-1" style="color: #7C8574;">العبدلي - مجمع لاش أوت</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="location-option border-2 rounded-xl p-4 cursor-pointer transition-all duration-300 hover:shadow-md" 
                               style="border-color: rgba(176, 141, 87, 0.2);">
                            <input type="radio" name="location" value="home" class="ml-3" required style="accent-color: #B08D57;">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-home text-2xl" style="color: #B08D57;"></i>
                                <div>
                                    <span class="font-bold" style="color: #2B1E1A;">في المنزل</span>
                                    <p class="text-xs mt-1" style="color: #7C8574;">خدمة توصيل للمنزل <span class="font-bold" style="color: #B08D57;">+10 د.أ</span></p>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    {{-- ========== رسالة واتساب للموقع (تظهر عند اختيار خدمة منزلية) ========== --}}
                    <div id="whatsappMessage" class="mt-4 p-4 rounded-xl text-center hidden" style="background: rgba(37, 211, 102, 0.1); border: 1px solid rgba(37, 211, 102, 0.3);">
                        <div class="flex items-center justify-center gap-3">
                            <i class="fab fa-whatsapp text-2xl" style="color: #25D366;"></i>
                            <div class="text-right">
                                <p class="font-bold" style="color: #2B1E1A;">📍 لتحديد موقعك بدقة</p>
                                <p class="text-sm" style="color: #7C8574;">بعد تأكيد الحجز، سيتم توجيهك إلى واتساب لإرسال موقعك</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- ========== ملخص حجزك ========== --}}
                <div class="rounded-2xl p-4 mb-6" style="background: rgba(176, 141, 87, 0.08);">
                    <h4 class="font-bold text-right mb-3" style="color: #2B1E1A;">📋 ملخص حجزك:</h4>
                    <div class="grid grid-cols-2 gap-3 text-right text-sm">
                        <div><span style="color: #7C8574;">الخدمة:</span> 
                            <span class="font-bold" style="color: #2B1E1A;">
                                @php
                                    $serviceNames = [
                                        'classic' => 'Classic Set',
                                        'wet' => 'Wet Set',
                                        'wispy' => 'Wispy Set',
                                        'volume' => 'Volume Set',
                                        'anime' => 'Anime Set'
                                    ];
                                @endphp
                                {{ $serviceNames[session('booking.service_type')] ?? session('booking.service_type') }}
                            </span>
                        </div>
                        <div><span style="color: #7C8574;">التاريخ:</span> <span class="font-bold" style="color: #2B1E1A;">{{ session('booking.booking_date', 'غير محدد') }}</span></div>
                        <div><span style="color: #7C8574;">الوقت:</span> <span class="font-bold" style="color: #2B1E1A;">{{ session('booking.booking_time', 'غير محدد') }}</span></div>
                        <div><span style="color: #7C8574;">شكل العين:</span> <span class="font-bold" style="color: #2B1E1A;">
                            @php
                                $eyeShapes = [
                                    'almond' => 'لوزية', 'round' => 'دائرية', 'hooded' => 'مبطنة',
                                    'downturned' => 'ناعسة', 'close-set' => 'متقاربة', 'wide-set' => 'متباعدة'
                                ];
                            @endphp
                            {{ $eyeShapes[session('booking.eye_shape')] ?? session('booking.eye_shape') }}
                        </span></div>
                    </div>
                </div>
                
                {{-- ========== الأزرار ========== --}}
                @if($availableStaff->count() > 0)
                <div class="flex justify-between gap-4">
                    <a href="{{ route('customer.bookings.step3') }}" class="px-8 py-3 rounded-xl font-bold transition-all duration-300 text-center" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                        <i class="fas fa-arrow-right ml-2"></i> السابق
                    </a>
                    <button type="submit" class="flex-1 font-bold py-3 rounded-xl transition-all duration-300 transform hover:scale-[1.02]" style="background: #B08D57; color: #F3EDE6;" id="submitBtn">
                        عرض الحجز والتأكيد <i class="fas fa-arrow-left mr-2"></i>
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    // اختيار الموظفة
    document.querySelectorAll('.staff-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.staff-card').forEach(c => {
                c.classList.remove('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
                c.style.background = 'rgba(255,255,255,0.8)';
            });
            this.classList.add('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
            this.style.background = 'rgba(176,141,87,0.08)';
            document.getElementById('staff_id').value = this.dataset.value;
        });
    });
    
    // إظهار رسالة واتساب عند اختيار خدمة منزلية
    const locationRadios = document.querySelectorAll('input[name="location"]');
    const whatsappMessage = document.getElementById('whatsappMessage');
    
    locationRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'home') {
                whatsappMessage.classList.remove('hidden');
                whatsappMessage.classList.add('block');
            } else {
                whatsappMessage.classList.add('hidden');
                whatsappMessage.classList.remove('block');
            }
        });
    });
</script>
@endsection