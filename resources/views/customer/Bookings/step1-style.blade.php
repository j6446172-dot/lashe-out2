@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12">
        
        {{-- Progress Bar --}}
        <div class="mb-12">
            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #B08D57;">
                        <i class="fas fa-eye text-xl" style="color: #F3EDE6;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #B08D57;">شكل العين</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2" style="background: rgba(176, 141, 87, 0.2);">
                        <i class="fas fa-spa text-xl" style="color: #7C8574;"></i>
                    </div>
                    <span class="text-sm" style="color: #7C8574;">الخدمة</span>
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
                <div class="h-full rounded-full transition-all duration-500" style="width: 25%; background: #B08D57;"></div>
            </div>
        </div>

        {{-- البطاقة الرئيسية --}}
        <div class="rounded-3xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
            
            <div class="px-8 py-8" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                <h2 class="text-3xl font-black mb-2" style="color: #F3EDE6;">✨ اختاري شكل عينك</h2>
                <p class="text-lg" style="color: rgba(243, 237, 230, 0.8);">لنساعدك في اختيار الاستايل المثالي لك</p>
            </div>
            
            <div class="p-8">
                <form method="POST" action="{{ route('customer.bookings.step1.post') }}">
                    @csrf
                    
                    {{-- أشكال العيون مع الصور --}}
                    <div class="mb-8">
                        <label class="block font-bold mb-4 text-right text-lg" style="color: #2B1E1A;">👁️ اختاري شكل عينك</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @php
                                $eyeShapes = [
                                    'almond' => ['name' => 'لوزية', 'image' => 'images/Almond.png'],
                                    'round' => ['name' => 'دائرية', 'image' => 'images/Round.png'],
                                    'hooded' => ['name' => 'مبطنة', 'image' => 'images/Hooded.png'],
                                    'downturned' => ['name' => 'ناعسة', 'image' => 'images/downturned.png'],
                                    'close-set' => ['name' => 'متقاربة', 'image' => 'images/Close-set.png'],
                                    'wide-set' => ['name' => 'متباعدة', 'image' => 'images/wide-set.png'],
                                ];
                            @endphp
                            @foreach($eyeShapes as $value => $eye)
                            <label class="cursor-pointer block">
                                <input type="radio" name="eye_shape" value="{{ $value }}" 
                                       class="hidden peer" 
                                       {{ ($lastEyeShape ?? '') == $value ? 'checked' : '' }} required>
                                <div class="rounded-xl p-4 text-center border-2 transition-all hover:shadow-md" 
                                     style="background: rgba(255, 255, 255, 0.8); border-color: rgba(176, 141, 87, 0.2);">
                                    
                                    <div class="w-20 h-20 mx-auto mb-3 flex items-center justify-center">
                                        <img src="{{ asset($eye['image']) }}" 
                                             class="max-w-full max-h-full object-contain transition-all duration-300"
                                             alt="{{ $eye['name'] }}"
                                             onerror="this.src='https://placehold.co/80x80/D6C3AD/B08D57?text={{ urlencode($eye['name']) }}'">
                                    </div>
                                    
                                    <span class="font-bold block" style="color: #2B1E1A;">{{ $eye['name'] }}</span>
                                    <p class="text-xs mt-1" style="color: #7C8574;">
                                        @if($value == 'almond') كلاسيك / هايبرد
                                        @elseif($value == 'round') كلاسيك / ويسبي
                                        @elseif($value == 'hooded') حجم خفيف / كات آي
                                        @elseif($value == 'downturned') ويسبي / كات آي
                                        @elseif($value == 'close-set') فولوم خارجي
                                        @else كلاسيك / هايبرد
                                        @endif
                                    </p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                   {{-- مدة الرموش (لا تحفظ، تختار كل مرة) --}}
<div class="mb-8">
    <label class="block font-bold mb-4 text-right text-lg" style="color: #2B1E1A;">📅 مدة الرموش</label>
    <div class="grid grid-cols-3 gap-4">
        @php
            $durations = [
                'weekly' => ['icon' => 'fa-calendar-week', 'name' => 'أسبوعية'],
                'monthly' => ['icon' => 'fa-calendar-alt', 'name' => 'شهرية'],
                'one-time' => ['icon' => 'fa-star', 'name' => 'مرة واحدة'],
            ];
        @endphp
        @foreach($durations as $value => $duration)
        <label class="cursor-pointer block">
            <input type="radio" name="lash_duration" value="{{ $value }}" class="hidden peer" required>
            <div class="rounded-xl p-4 text-center border-2 transition-all hover:shadow-md" 
                 style="background: rgba(255, 255, 255, 0.8); border-color: rgba(176, 141, 87, 0.2);">
                <i class="fas {{ $duration['icon'] }} text-2xl mb-2 block" style="color: #B08D57;"></i>
                <span class="font-bold block" style="color: #2B1E1A;">{{ $duration['name'] }}</span>
            </div>
        </label>
        @endforeach
    </div>
</div>

                    {{-- الأزرار --}}
                    <div class="flex justify-between gap-4 mt-8">
                        <a href="{{ route('customer.dashboard') }}" class="px-8 py-3 rounded-xl font-bold transition hover:opacity-80" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
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
    
    .peer:checked ~ div img {
        transform: scale(1.05);
    }
    
    .peer:checked ~ div i {
        color: #B08D57 !important;
    }
</style>
@endsection