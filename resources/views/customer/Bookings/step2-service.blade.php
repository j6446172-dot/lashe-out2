@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12">
        
        {{-- Progress Bar --}}
        <div class="mb-12">
            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #10b981;">
                        <i class="fas fa-check text-xl" style="color: white;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #10b981;">شكل العين</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #B08D57;">
                        <i class="fas fa-spa text-xl" style="color: #F3EDE6;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #B08D57;">الخدمة</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2" style="background: rgba(176, 141, 87, 0.2);">
                        <i class="fas fa-calendar-alt text-xl" style="color: #7C8574;"></i>
                    </div>
                    <span class="text-sm" style="color: #7C8574;">التاريخ</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2" style="background: rgba(176, 141, 87, 0.2);">
                        <i class="fas fa-user-check text-xl" style="color: #7C8574;"></i>
                    </div>
                    <span class="text-sm" style="color: #7C8574;">التأكيد</span>
                </div>
            </div>
            <div class="mt-4 h-2 rounded-full overflow-hidden" style="background: rgba(176, 141, 87, 0.2);">
                <div class="h-full rounded-full transition-all duration-500" style="width: 50%; background: #B08D57;"></div>
            </div>
        </div>

        {{-- البطاقة الرئيسية --}}
        <div class="rounded-3xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
            
            <div class="px-8 py-8" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                <h2 class="text-3xl font-black mb-2" style="color: #F3EDE6;">✨ اختاري خدمتك</h2>
                <p class="text-lg" style="color: rgba(243, 237, 230, 0.8);">نقدم لك تشكيلة مميزة من خدمات الرموش</p>
            </div>
            
            <div class="p-8">
                <form method="POST" action="{{ route('customer.bookings.step2.post') }}" id="serviceForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $services = [
                                'classic' => ['name' => 'Classic Set', 'price' => 30, 'desc' => 'تركيب رمش واحد طبيعي لكل رمش أصلي لمظهر يومي هادئ', 'icon' => 'feather'],
                                'wet' => ['name' => 'Wet Set', 'price' => 40, 'desc' => 'مظهر الرموش المبللة العصرية التي تعكس جرأة ملامحك', 'icon' => 'tint'],
                                'wispy' => ['name' => 'Wispy Set', 'price' => 50, 'desc' => 'تصميم ريشي متدرج يعطي العين مظهراً واسعاً وجذاباً', 'icon' => 'feather-alt'],
                                'volume' => ['name' => 'Volume Set', 'price' => 45, 'desc' => 'كثافة عالية وسواد فاحم، مثالي للمناسبات والحفلات', 'icon' => 'layer-group'],
                                'anime' => ['name' => 'Anime Set', 'price' => 55, 'desc' => 'ستايل الأنمي الشهير بتوزيع Spikes فني ومميز جداً', 'icon' => 'star'],
                            ];
                        @endphp
                        @foreach($services as $key => $service)
                            <label class="cursor-pointer block">
                                <input type="radio" name="service_type" value="{{ $key }}" class="hidden peer" required>
                                <div class="rounded-xl p-4 border-2 transition-all hover:shadow-md" 
                                     style="background: rgba(255, 255, 255, 0.8); border-color: rgba(176, 141, 87, 0.2);">
                                    <i class="fas fa-{{ $service['icon'] }} text-2xl mb-2 block" style="color: #B08D57;"></i>
                                    <h3 class="font-bold text-lg" style="color: #2B1E1A;">{{ $service['name'] }}</h3>
                                    <p class="text-sm mt-1" style="color: #7C8574;">{{ $service['desc'] }}</p>
                                    <p class="text-xl font-bold mt-3" style="color: #B08D57;">{{ $service['price'] }} د.أ</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-between gap-4 mt-8">
                        <a href="{{ route('customer.bookings.step1') }}" class="px-8 py-3 rounded-xl font-bold transition hover:opacity-80" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                            <i class="fas fa-arrow-right ml-2"></i> السابق
                        </a>
                        <button type="submit" class="flex-1 py-3 rounded-xl font-bold transition hover:shadow-xl" style="background: #B08D57; color: #F3EDE6;">
                            التالي <i class="fas fa-arrow-left mr-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .peer:checked ~ div {
        border-color: #B08D57 !important;
        background: rgba(176, 141, 87, 0.08) !important;
    }
    
    .peer:checked ~ div i {
        transform: scale(1.05);
    }
</style>

<script>
    // تأكد من أن النموذج يتم إرساله عند الضغط على "التالي"
    document.querySelector('form').addEventListener('submit', function(e) {
        const selected = document.querySelector('input[name="service_type"]:checked');
        if (!selected) {
            e.preventDefault();
            alert('الرجاء اختيار خدمة أولاً');
        }
    });
</script>
@endsection