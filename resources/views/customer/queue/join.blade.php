@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                
                {{-- هيدر الصفحة --}}
                <div class="rounded-2xl shadow-md p-6 text-center mb-6" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <i class="fas fa-clock text-4xl mb-2" style="color: #F3EDE6;"></i>
                    <h1 class="text-2xl font-bold" style="color: #F3EDE6;">⏳ طابور الانتظار</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">انضمي لطابور الانتظار وسنخبرك عندما يتوفر موعد</p>
                </div>

                @php
                    $existingQueue = App\Models\Queue::where('user_id', auth()->id())->where('status', 'waiting')->first();
                    $waitingCount = App\Models\Queue::where('status', 'waiting')->count();
                @endphp

                @if($existingQueue)
                    @php
                        $peopleAhead = App\Models\Queue::where('status', 'waiting')->where('position', '<', $existingQueue->position)->count();
                    @endphp
                    <div class="rounded-xl p-6 mb-6 text-center" style="background: rgba(176, 141, 87, 0.1); border: 1px solid rgba(176, 141, 87, 0.2);">
                        <i class="fas fa-hourglass-half text-3xl mb-3" style="color: #B08D57;"></i>
                        <p class="font-bold" style="color: #2B1E1A;">🌟 أنتِ بالفعل في الطابور!</p>
                        <p class="text-3xl font-bold mt-2" style="color: #B08D57;">رقم {{ $existingQueue->position }}</p>
                        <p class="text-sm mt-1" style="color: #7C8574;">عدد الأشخاص قبلك: {{ $peopleAhead }}</p>
                        <p class="text-xs mt-3" style="color: #7C8574;">متوسط وقت الانتظار: {{ $peopleAhead * 30 }} دقيقة</p>
                        <div class="mt-4">
                            <a href="{{ route('customer.queue.status', $existingQueue->id) }}" class="inline-block px-4 py-2 rounded-xl text-sm transition hover:opacity-80" style="background: #B08D57; color: #F3EDE6;">
                                <i class="fas fa-eye ml-1"></i> تتبع الطابور
                            </a>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('customer.queue.join') }}" class="rounded-2xl p-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
                        @csrf
                        
                        {{-- الخدمة --}}
                        <div class="mb-5">
                            <label class="block font-bold mb-2" style="color: #2B1E1A;">
                                <i class="fas fa-spa ml-1" style="color: #B08D57;"></i> الخدمة المطلوبة
                            </label>
                            <select name="service_type" class="w-full px-4 py-3 rounded-xl text-right" style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);" required>
                                <option value="">-- اختاري الخدمة --</option>
                                <option value="classic">👁️ كلاسيك (30 د.أ)</option>
                                <option value="hybrid">✨ هايبرد (40 د.أ)</option>
                                <option value="volume">💎 فولوم (50 د.أ)</option>
                                <option value="removal">🧹 إزالة رموش (5 د.أ)</option>
                            </select>
                        </div>

                        {{-- التاريخ المفضل --}}
                        <div class="mb-5">
                            <label class="block font-bold mb-2" style="color: #2B1E1A;">
                                <i class="fas fa-calendar-alt ml-1" style="color: #B08D57;"></i> التاريخ المفضل
                            </label>
                            <input type="date" name="preferred_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                   class="w-full px-4 py-3 rounded-xl text-right" style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);" required>
                            <p class="text-xs mt-1" style="color: #7C8574;">سنحاول إيجاد موعد قريب من هذا التاريخ</p>
                        </div>

                        {{-- إشعار واتساب --}}
                        <div class="rounded-lg p-3 mb-5" style="background: rgba(176, 141, 87, 0.08); border: 1px solid rgba(176, 141, 87, 0.15);">
                            <div class="flex items-center gap-3">
                                <i class="fab fa-whatsapp text-xl" style="color: #B08D57;"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-bold" style="color: #2B1E1A;">📱 إشعار واتساب</p>
                                    <p class="text-xs" style="color: #7C8574;">سنرسل لك إشعاراً عندما يتوفر موعد</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="whatsapp_notify" class="sr-only peer" checked>
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all" style="background-color: #B08D57;"></div>
                                </label>
                            </div>
                        </div>

                        {{-- عدد الأشخاص في الطابور --}}
                        <div class="rounded-lg p-3 mb-5 text-center" style="background: rgba(176, 141, 87, 0.05);">
                            <p class="text-sm" style="color: #7C8574;">👥 عدد الأشخاص في الطابور حالياً</p>
                            <p class="text-2xl font-bold" style="color: #B08D57;">{{ $waitingCount }}</p>
                            <p class="text-xs mt-1" style="color: #7C8574;">متوسط وقت الانتظار: {{ $waitingCount * 30 }} دقيقة</p>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-lg transition hover:shadow-lg" style="background: #B08D57; color: #F3EDE6;">
                            <i class="fas fa-sign-in-alt ml-2"></i> انضم للطابور
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection