@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="rounded-3xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
            
            {{-- هيدر الصفحة --}}
            <div class="px-8 py-8 text-center" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                <i class="fas fa-clock text-5xl mb-3" style="color: #F3EDE6;"></i>
                <h2 class="text-3xl font-black" style="color: #F3EDE6;">⏳ طابور الانتظار</h2>
                <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">سنخبرك عندما يحين دورك</p>
            </div>
            
            <div class="p-8 text-center">
                
                {{-- رقمك في الطابور --}}
                <div class="text-8xl font-black mb-2" style="color: #B08D57;">{{ $queue->position }}</div>
                <p class="mb-6" style="color: #7C8574;">رقمك في الطابور</p>
                
                {{-- تفاصيل الطابور --}}
                <div class="rounded-2xl p-6 my-6" style="background: rgba(176, 141, 87, 0.05);">
                    <div class="flex justify-between items-center mb-3">
                        <span style="color: #7C8574;">الأشخاص قبلك:</span>
                        <span class="font-bold text-2xl {{ $peopleAhead > 0 ? 'text-[#B08D57]' : 'text-green-600' }}">
                            {{ $peopleAhead }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span style="color: #7C8574;">الوقت المتوقع:</span>
                        <span class="font-bold text-2xl" style="color: #B08D57;">{{ $estimatedWait }} دقيقة</span>
                    </div>
                </div>
                
                {{-- رسالة إذا كان دورك قريب --}}
                @if($yourTurnSoon && $peopleAhead > 0)
                    <div class="rounded-2xl p-4 mb-6" style="background: rgba(176, 141, 87, 0.1);">
                        <p style="color: #B08D57;">🎉 دورك قريب! استعدي للحجز</p>
                    </div>
                @elseif($peopleAhead == 0 && $queue->position == 1)
                    <div class="rounded-2xl p-4 mb-6" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
                        <p class="font-bold" style="color: #10b981;">🎉 حان دورك الآن! يمكنك متابعة الحجز</p>
                        <a href="{{ route('customer.bookings.step1') }}" class="inline-block mt-2 px-6 py-2 rounded-xl transition hover:opacity-80" style="background: #B08D57; color: #F3EDE6;">
                            ابدئي الحجز الآن
                        </a>
                    </div>
                @endif
                
                {{-- إشعار واتساب --}}
                <div class="rounded-2xl p-4 mb-6" style="background: rgba(176, 141, 87, 0.05);">
                    <p class="text-sm" style="color: #7C8574;">
                        <i class="fab fa-whatsapp ml-1" style="color: #B08D57;"></i>
                        سنرسل لك إشعاراً على واتساب عندما يحين دورك
                    </p>
                </div>
                
                {{-- أزرار --}}
                <div class="flex gap-4">
                    <form method="POST" action="{{ route('customer.queue.cancel', $queue->id) }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 rounded-xl font-bold transition hover:opacity-80" style="background: #dc2626; color: white;" onclick="return confirm('هل أنت متأكدة من إلغاء طلب الانتظار؟')">
                            <i class="fas fa-times ml-2"></i> إلغاء الطلب
                        </button>
                    </form>
                    <a href="{{ route('customer.dashboard') }}" class="flex-1 py-3 rounded-xl font-bold transition hover:opacity-80 text-center" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                        <i class="fas fa-home ml-2"></i> الرئيسية
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection