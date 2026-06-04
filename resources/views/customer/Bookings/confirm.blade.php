@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                
                {{-- هيدر الصفحة --}}
                <div class="rounded-2xl shadow-md p-6 text-center mb-6" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <i class="fas fa-clipboard-list text-3xl mb-2" style="color: #F3EDE6;"></i>
                    <h1 class="text-xl font-bold" style="color: #F3EDE6;">📋 تأكيد الحجز</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">راجعي معلومات حجزك قبل التأكيد</p>
                </div>

                {{-- بطاقة المعلومات --}}
                <div class="rounded-2xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
                    <div class="p-6 space-y-4">
                        
                        {{-- شكل العين --}}
                        <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                            <span class="text-sm" style="color: #7C8574;">👁️ شكل العين</span>
                            <span class="font-medium" style="color: #2B1E1A;">
                                @php
                                    $eyeShapes = [
                                        'almond' => 'لوزية',
                                        'round' => 'دائرية',
                                        'hooded' => 'مبطنة',
                                        'downturned' => 'ناعسة',
                                        'close-set' => 'متقاربة',
                                        'wide-set' => 'متباعدة',
                                    ];
                                    $eyeShape = session('booking.eye_shape', 'غير محدد');
                                @endphp
                                {{ $eyeShapes[$eyeShape] ?? $eyeShape }}
                            </span>
                        </div>

                        {{-- مدة الرموش --}}
                        <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                            <span class="text-sm" style="color: #7C8574;">📅 مدة الرموش</span>
                            <span class="font-medium" style="color: #2B1E1A;">
                                @php
                                    $durations = [
                                        'weekly' => 'أسبوعية',
                                        'monthly' => 'شهرية',
                                        'one-time' => 'مرة واحدة',
                                    ];
                                    $lashDuration = session('booking.lash_duration', 'monthly');
                                @endphp
                                {{ $durations[$lashDuration] ?? $lashDuration }}
                            </span>
                        </div>

                        {{-- الخدمة --}}
                        <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                            <span class="text-sm" style="color: #7C8574;">💅 الخدمة</span>
                            <span class="font-medium" style="color: #2B1E1A;">
                                @php
                                    $services = [
                                        'classic' => 'Classic Set',
                                        'wet' => 'Wet Set',
                                        'wispy' => 'Wispy Set',
                                        'volume' => 'Volume Set',
                                        'anime' => 'Anime Set',
                                        'removal' => 'إزالة الرموش',
                                    ];
                                    $serviceType = session('booking.service_type', 'غير محدد');
                                @endphp
                                {{ $services[$serviceType] ?? $serviceType }}
                            </span>
                        </div>

                        {{-- التاريخ --}}
                        <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                            <span class="text-sm" style="color: #7C8574;">📅 التاريخ</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ \Carbon\Carbon::parse(session('booking.booking_date'))->format('d/m/Y') }}</span>
                        </div>

                        {{-- الوقت --}}
                        <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                            <span class="text-sm" style="color: #7C8574;">⏰ الوقت</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ \Carbon\Carbon::parse(session('booking.booking_time'))->format('g:i A') }}</span>
                        </div>

                        {{-- الموظفة --}}
                        <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                            <span class="text-sm" style="color: #7C8574;">💇‍♀️ الموظفة</span>
                            <span class="font-medium" style="color: #2B1E1A;">
                                @php
                                    $staff = \App\Models\User::find(session('booking.staff_id'));
                                @endphp
                                {{ $staff->name ?? 'غير محدد' }}
                            </span>
                        </div>

                        {{-- الموقع --}}
                        <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                            <span class="text-sm" style="color: #7C8574;">📍 الموقع</span>
                            <span class="font-medium" style="color: #2B1E1A;">{{ session('booking.location') == 'salon' ? 'في الصالون' : 'خدمة منزلية' }}</span>
                        </div>

                        {{-- السعر قبل الخصم --}}
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-sm" style="color: #7C8574;">💰 السعر قبل الخصم</span>
                            <span class="font-medium" style="color: #2B1E1A;">
                                @php
                                    $servicesPrice = [
                                        'classic' => 30,
                                        'wet' => 40,
                                        'wispy' => 50,
                                        'volume' => 45,
                                        'anime' => 55,
                                        'removal' => 5
                                    ];
                                    $originalPrice = $servicesPrice[session('booking.service_type')] ?? 30;
                                    
                                    $lashDuration = session('booking.lash_duration', 'monthly');
                                    
                                    if ($lashDuration == 'one-time') {
                                        $priceAfterDuration = $originalPrice * 0.5;
                                    } elseif ($lashDuration == 'weekly') {
                                        $priceAfterDuration = $originalPrice * 0.65;
                                    } else {
                                        $priceAfterDuration = $originalPrice;
                                    }
                                    
                                    $user = auth()->user();
                                    
                                    // السعر بعد مدة الرموش
                                    $basePrice = $priceAfterDuration;
                                    
                                    // إضافة 10 د.أ للخدمة المنزلية
                                    if(session('booking.location') == 'home') {
                                        $basePrice += 10;
                                    }
                                @endphp
                                {{ number_format($basePrice, 2) }} د.أ
                            </span>
                        </div>

                        {{-- ✅ خيار استخدام الخصم (إذا عندها 50 نقطة او اكثر) --}}
                        @php
                            $userPoints = $user->loyalty_points ?? 0;
                            $hasDiscount = $userPoints >= 50;
                            $discountValue = floor($userPoints / 50) * 5; // كل 50 نقطة = 5 دنانير
                            $finalPriceAfterDiscount = $basePrice - $discountValue;
                            if ($finalPriceAfterDiscount < 0) $finalPriceAfterDiscount = 0;
                        @endphp

                        @if($hasDiscount)
                        <div class="rounded-2xl p-4 mt-2" style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981;">
                            <label class="flex items-center justify-between cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-gift text-xl" style="color: #10b981;"></i>
                                    <div>
                                        <span class="font-bold" style="color: #2B1E1A;">🎁 لديك خصم متاح!</span>
                                        <p class="text-sm" style="color: #7C8574;">
                                            خصم {{ $discountValue }} دينار (باستخدام {{ floor($userPoints / 50) * 50 }} نقطة)
                                        </p>
                                        <p class="text-xs" style="color: #10b981;">
                                            رصيدك الحالي: {{ $userPoints }} نقطة
                                        </p>
                                    </div>
                                </div>
                                <input type="checkbox" name="use_discount" id="use_discount" value="1" class="w-5 h-5 rounded" style="accent-color: #10b981;">
                            </label>
                        </div>

                        {{-- السعر بعد الخصم (يتغير بالجافا سكريبت) --}}
                        <div class="flex justify-between items-center pt-3 border-t" style="border-color: rgba(176, 141, 87, 0.2);">
                            <span class="font-bold text-lg" style="color: #2B1E1A;">💰 المبلغ الإجمالي</span>
                            <span class="text-2xl font-bold" id="totalPrice" style="color: #B08D57;">{{ number_format($basePrice, 2) }} د.أ</span>
                        </div>
                        <div id="discountInfo" class="text-center text-sm hidden" style="color: #10b981;">
                            🎉 تم تطبيق خصم {{ $discountValue }} دينار!
                        </div>
                        @else
                        {{-- لا يوجد خصم --}}
                        <div class="flex justify-between items-center pt-3 border-t" style="border-color: rgba(176, 141, 87, 0.2);">
                            <span class="font-bold text-lg" style="color: #2B1E1A;">💰 المبلغ الإجمالي</span>
                            <span class="text-2xl font-bold" style="color: #B08D57;">{{ number_format($basePrice, 2) }} د.أ</span>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- الأزرار الأساسية --}}
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('customer.bookings.step4') }}" class="flex-1 text-center py-3 rounded-xl font-bold transition hover:opacity-80" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                        <i class="fas fa-arrow-right ml-2"></i> رجوع
                    </a>
                    <form action="{{ route('customer.bookings.store') }}" method="POST" class="flex-1" id="bookingForm">
                        @csrf
                        <input type="hidden" name="use_discount" id="use_discount_input" value="0">
                        <button type="submit" class="w-full py-3 rounded-xl font-bold transition shadow-md hover:shadow-lg" style="background: #B08D57; color: #F3EDE6;">
                            <i class="fas fa-check-circle ml-2"></i> تأكيد الحجز
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- جافا سكريبت لتحديث السعر عند اختيار الخصم --}}
@if($hasDiscount)
<script>
    const checkbox = document.getElementById('use_discount');
    const totalPriceSpan = document.getElementById('totalPrice');
    const discountInfoDiv = document.getElementById('discountInfo');
    const discountInput = document.getElementById('use_discount_input');
    
    const basePrice = {{ $basePrice }};
    const discountValue = {{ $discountValue }};
    const finalPrice = {{ $finalPriceAfterDiscount }};
    
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            totalPriceSpan.innerHTML = finalPrice.toFixed(2) + ' د.أ';
            discountInfoDiv.classList.remove('hidden');
            discountInput.value = '1';
        } else {
            totalPriceSpan.innerHTML = basePrice.toFixed(2) + ' د.أ';
            discountInfoDiv.classList.add('hidden');
            discountInput.value = '0';
        }
    });
</script>
@endif

@endsection