@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                
                {{-- هيدر الصفحة --}}
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl shadow-lg mb-4" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                        <i class="fas fa-feather-alt text-3xl" style="color: #F3EDE6;"></i>
                    </div>
                    <h1 class="text-3xl font-bold mb-2" style="color: #2B1E1A;">✨ إزالة الرموش</h1>
                    <p class="text-lg" style="color: #7C8574;">إزالة آمنة ولطيفة بدون أي التزام</p>
                </div>

                {{-- بطاقة الخدمة --}}
                <div class="rounded-2xl shadow-xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
                    
                    {{-- رأس البطاقة --}}
                    <div class="p-6 text-center border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                        <div class="flex justify-center mb-3">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background: rgba(176, 141, 87, 0.1);">
                                <i class="fas fa-trash-alt text-2xl" style="color: #B08D57;"></i>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold mb-1" style="color: #2B1E1A;">إزالة رموش احترافية</h2>
                        <p class="text-sm" style="color: #7C8574;">في الصالون فقط</p>
                        <p class="text-3xl font-bold mt-3" style="color: #B08D57;">5 د.أ</p>
                    </div>

                    {{-- تفاصيل سريعة --}}
                    <div class="grid grid-cols-3 gap-2 p-4 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                        <div class="text-center p-2 rounded-xl" style="background: rgba(176, 141, 87, 0.05);">
                            <i class="fas fa-stopwatch text-sm mb-1 block" style="color: #B08D57;"></i>
                            <p class="text-xs" style="color: #2B1E1A;">10-15 دقيقة</p>
                        </div>
                        <div class="text-center p-2 rounded-xl" style="background: rgba(176, 141, 87, 0.05);">
                            <i class="fas fa-building text-sm mb-1 block" style="color: #B08D57;"></i>
                            <p class="text-xs" style="color: #2B1E1A;">في الصالون</p>
                        </div>
                        <div class="text-center p-2 rounded-xl" style="background: rgba(176, 141, 87, 0.05);">
                            <i class="fas fa-shield-alt text-sm mb-1 block" style="color: #B08D57;"></i>
                            <p class="text-xs" style="color: #2B1E1A;">آمنة ولطيفة</p>
                        </div>
                    </div>

                    {{-- نموذج الحجز المبسط --}}
                    <form method="POST" action="{{ route('customer.removal.store') }}" class="p-6">
                        @csrf
                        
                        {{-- التاريخ --}}
                        <div class="mb-5">
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">
                                <i class="fas fa-calendar-day ml-1" style="color: #B08D57;"></i> اختاري التاريخ
                            </label>
                            <input type="date" name="booking_date" 
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 rounded-xl text-right"
                                   style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);"
                                   required>
                        </div>

                        {{-- الوقت --}}
                        <div class="mb-6">
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">
                                <i class="fas fa-clock ml-1" style="color: #B08D57;"></i> اختاري الوقت
                            </label>
                            <select name="booking_time" 
                                    class="w-full px-4 py-3 rounded-xl text-right"
                                    style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);"
                                    required>
                                <option value="">-- اختر الوقت --</option>
                                <option value="10:00">10:00 صباحاً</option>
                                <option value="11:00">11:00 صباحاً</option>
                                <option value="12:00">12:00 ظهراً</option>
                                <option value="13:00">01:00 ظهراً</option>
                                <option value="14:00">02:00 ظهراً</option>
                                <option value="15:00">03:00 عصراً</option>
                                <option value="16:00">04:00 عصراً</option>
                                <option value="17:00">05:00 مساءً</option>
                                <option value="18:00">06:00 مساءً</option>
                            </select>
                        </div>

                        {{-- حقول مخفية --}}
                        <input type="hidden" name="service_type" value="removal">
                        <input type="hidden" name="location" value="salon">

                        {{-- زر تأكيد الحجز --}}
                        <button type="submit" class="w-full py-4 rounded-xl font-bold text-lg transition-all hover:shadow-lg hover:scale-[1.02]" style="background: #B08D57; color: #F3EDE6;">
                            <i class="fas fa-check-circle ml-2"></i>
                            تأكيد الحجز
                        </button>
                    </form>

                </div>

                {{-- زر العودة --}}
                <div class="text-center mt-6">
                    <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 text-sm transition hover:opacity-70" style="color: #7C8574;">
                        <i class="fas fa-arrow-right"></i>
                        العودة إلى لوحة التحكم
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection