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

                        {{-- حساب السعر الأساسي --}}
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
                            
                            // السعر الأساسي
                            $basePrice = $priceAfterDuration;
                            
                            // إضافة 10 د.أ للخدمة المنزلية
                            if(session('booking.location') == 'home') {
                                $basePrice += 10;
                            }
                            
                            // حساب الخصومات المتاحة
                            $userPoints = $user->points ?? 0;
                            $availableDiscounts = floor($userPoints / 50);
                            $hasDiscount = $availableDiscounts > 0;
                            
                            // إنشاء مصفوفة الخيارات
                            $discountOptions = [];
                            $discountOptions[] = [
                                'percent' => 0,
                                'points' => 0,
                                'price' => $basePrice,
                                'label' => 'بدون خصم',
                                'description' => 'الحفاظ على النقاط للمستقبل'
                            ];
                            
                            for ($i = 1; $i <= $availableDiscounts; $i++) {
                                $percent = $i * 15;
                                $points = $i * 50;
                                $price = $basePrice * (1 - ($percent / 100));
                                if ($price < 0) $price = 0;
                                
                                $discountOptions[] = [
                                    'percent' => $percent,
                                    'points' => $points,
                                    'price' => $price,
                                    'label' => "خصم {$percent}%",
                                    'description' => "استخدام {$points} نقطة" . ($points > 0 ? " (سيتبقى " . ($userPoints - $points) . " نقطة)" : "")
                                ];
                            }
                            
                            // ترتيب الخيارات تنازلياً (الأعلى خصم أولاً)
                            $discountOptions = array_reverse($discountOptions);
                        @endphp

                        {{-- قسم الخصومات --}}
                        @if($hasDiscount)
                        <div class="rounded-2xl p-4 mt-2" style="background: rgba(176, 141, 87, 0.1); border: 2px solid #B08D57;">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-gift text-xl" style="color: #B08D57;"></i>
                                <span class="font-bold" style="color: #2B1E1A;">🎁 اختر خصمك المناسب</span>
                            </div>
                            <p class="text-sm text-right mb-3" style="color: #7C8574;">
                                لديك {{ $userPoints }} نقطة (تؤهلك {{ $availableDiscounts }} خصم)
                            </p>
                            
                            <div class="space-y-2">
                                @foreach($discountOptions as $index => $option)
                                <label class="flex items-center justify-between cursor-pointer p-3 rounded-xl transition-all duration-300 hover:shadow-md discount-option" 
                                       style="background: rgba(255, 255, 255, 0.8); {{ $index == 0 ? 'border: 2px solid #10b981;' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="discount_option" value="{{ $option['percent'] }}" 
                                               data-points="{{ $option['points'] }}"
                                               data-price="{{ $option['price'] }}"
                                               class="w-5 h-5 rounded discount-radio" 
                                               style="accent-color: #B08D57;"
                                               {{ $index == 0 ? 'checked' : '' }}>
                                        <div>
                                            <span class="font-bold" style="color: {{ $option['percent'] > 0 ? '#10b981' : '#7C8574' }};">
                                                {{ $option['label'] }}
                                            </span>
                                            <p class="text-sm" style="color: #7C8574;">{{ $option['description'] }}</p>
                                            @if($option['percent'] > 0)
                                                <p class="text-xs" style="color: #B08D57;">
                                                    💰 توفرين {{ number_format($basePrice - $option['price'], 2) }} دينار
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        @if($option['percent'] > 0)
                                            <span class="text-sm line-through" style="color: #9ca3af;">{{ number_format($basePrice, 2) }} د.أ</span>
                                            <span class="font-bold text-lg block" style="color: #10b981;">{{ number_format($option['price'], 2) }} د.أ</span>
                                        @else
                                            <span class="font-bold text-lg" style="color: #B08D57;">{{ number_format($option['price'], 2) }} د.أ</span>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            
                            <div class="mt-3 p-2 rounded-lg text-center" style="background: rgba(176, 141, 87, 0.08);">
                                <p class="text-xs" style="color: #7C8574;">
                                    💡 يمكنك اختيار الخصم المناسب لك. كل 50 نقطة = خصم 15%
                                </p>
                            </div>
                        </div>

                        {{-- السعر الإجمالي --}}
                        <div class="flex justify-between items-center pt-3 border-t" style="border-color: rgba(176, 141, 87, 0.2);">
                            <span class="font-bold text-lg" style="color: #2B1E1A;">💰 المبلغ الإجمالي</span>
                            <span class="text-2xl font-bold" id="totalPrice" style="color: #B08D57;">{{ number_format($basePrice, 2) }} د.أ</span>
                        </div>

                        @else
                        {{-- لا يوجد خصم --}}
                        <div class="rounded-2xl p-4 mt-2" style="background: rgba(176, 141, 87, 0.05);">
                            <div class="text-center">
                                <i class="fas fa-chart-line text-2xl mb-2" style="color: #B08D57;"></i>
                                <p class="text-sm" style="color: #7C8574;">
                                    ✨ اجمعي <strong>{{ 50 - $userPoints }}</strong> نقطة إضافية لتحصلي على خصم 15%!
                                </p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                    <div class="h-2 rounded-full transition-all duration-500" style="width: {{ min(100, ($userPoints / 50) * 100) }}%; background: #B08D57;"></div>
                                </div>
                                <p class="text-xs mt-2" style="color: #7C8574;">{{ $userPoints }} / 50 نقطة</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t" style="border-color: rgba(176, 141, 87, 0.2);">
                            <span class="font-bold text-lg" style="color: #2B1E1A;">💰 المبلغ الإجمالي</span>
                            <span class="text-2xl font-bold" style="color: #B08D57;">{{ number_format($basePrice, 2) }} د.أ</span>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- الأزرار --}}
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('customer.bookings.step4') }}" class="flex-1 text-center py-3 rounded-xl font-bold transition hover:opacity-80" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                        <i class="fas fa-arrow-right ml-2"></i> رجوع
                    </a>
                    <form action="{{ route('customer.bookings.store') }}" method="POST" class="flex-1" id="bookingForm">
                        @csrf
                        <input type="hidden" name="discount_percent" id="discount_percent_input" value="0">
                        <input type="hidden" name="points_to_use" id="points_to_use_input" value="0">
                        <button type="submit" class="w-full py-3 rounded-xl font-bold transition shadow-md hover:shadow-lg" style="background: #B08D57; color: #F3EDE6;">
                            <i class="fas fa-check-circle ml-2"></i> تأكيد الحجز
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // معالجة اختيار الخصم
    const radioButtons = document.querySelectorAll('input[name="discount_option"]');
    const totalPriceSpan = document.getElementById('totalPrice');
    const discountPercentInput = document.getElementById('discount_percent_input');
    const pointsToUseInput = document.getElementById('points_to_use_input');
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedPercent = parseInt(this.value);
            const selectedPoints = parseInt(this.dataset.points);
            const selectedPrice = parseFloat(this.dataset.price);
            
            // تحديث السعر المعروض
            totalPriceSpan.innerHTML = selectedPrice.toFixed(2) + ' د.أ';
            totalPriceSpan.style.color = selectedPercent > 0 ? '#10b981' : '#B08D57';
            
            // تحديث الحقول المخفية
            discountPercentInput.value = selectedPercent;
            pointsToUseInput.value = selectedPoints;
            
            // تأثير بسيط عند التحديد
            document.querySelectorAll('.discount-option').forEach(option => {
                option.style.border = 'none';
                option.style.transform = 'scale(1)';
            });
            this.closest('.discount-option').style.border = '2px solid #B08D57';
            this.closest('.discount-option').style.transform = 'scale(1.02');
            
            setTimeout(() => {
                if (this.closest('.discount-option')) {
                    this.closest('.discount-option').style.transform = 'scale(1)';
                }
            }, 200);
        });
    });
    
    // تحديد الخيار الأول افتراضياً
    const checkedRadio = document.querySelector('input[name="discount_option"]:checked');
    if (checkedRadio) {
        const event = new Event('change');
        checkedRadio.dispatchEvent(event);
    }
</script>

@endsection