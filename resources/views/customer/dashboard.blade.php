@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12">
        
        <div class="flex justify-start">
            <div class="w-full lg:w-10/12 xl:w-9/12">
                
                {{-- ========== رسائل التنبيه ========== --}}
                @if(session('success'))
                    <div class="mb-6 p-5 rounded-2xl text-right"
                         style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border-right: 3px solid #10b981;">
                        <i class="fas fa-check-circle ml-2" style="color: #10b981;"></i>
                        <span style="color: #2B1E1A;">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-5 rounded-2xl text-right"
                         style="background: rgba(254, 226, 226, 0.8); backdrop-filter: blur(8px); border-right: 3px solid #dc2626;">
                        <i class="fas fa-exclamation-circle ml-2" style="color: #dc2626;"></i>
                        <span style="color: #991b1b;">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- ========== إشعار الموعد اليوم ========== --}}
                @php
                    $todayBooking = auth()->user()->bookings()
                        ->where('booking_date', today())
                        ->where('status', 'confirmed')
                        ->first();
                @endphp

                @if($todayBooking)
                    <div class="mb-6 p-5 rounded-2xl"
                         style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.2);">
                        <div class="flex items-center justify-end gap-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: rgba(176, 141, 87, 0.15);">
                                <i class="fas fa-bell text-xl" style="color: #B08D57;"></i>
                            </div>
                            <div class="text-right">
                                <p class="font-bold" style="color: #2B1E1A;">🔔 لديكِ موعد اليوم!</p>
                                <p style="color: #7C8574; font-size: 0.875rem;">
                                    {{ ucfirst($todayBooking->service_type) }} - {{ \Carbon\Carbon::parse($todayBooking->booking_time)->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ========== بطاقة الطابور ========== --}}
                @php
                    $userInQueue = App\Models\Queue::where('user_id', auth()->id())->where('status', 'waiting')->first();
                @endphp

                @if($userInQueue)
                    @php
                        $peopleAhead = App\Models\Queue::where('status', 'waiting')->where('position', '<', $userInQueue->position)->count();
                    @endphp
                    <div class="mb-6 p-5 rounded-2xl"
                         style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.2);">
                        <div class="flex items-center justify-between gap-4">
                            <a href="{{ route('customer.queue.status', $userInQueue->id) }}" 
                               class="px-5 py-2 rounded-xl text-sm transition-all duration-300 hover:opacity-90 font-semibold"
                               style="background: #B08D57; color: #F3EDE6;">
                                تتبع <i class="fas fa-arrow-left mr-1"></i>
                            </a>
                            <div class="text-right">
                                <p class="font-bold mb-1" style="color: #2B1E1A;">⏳ أنتِ في طابور الانتظار</p>
                                <p style="color: #7C8574; font-size: 0.875rem;">
                                    رقم {{ $userInQueue->position }} - انتظارك {{ $peopleAhead * 30 }} دقيقة تقريباً
                                </p>
                            </div>
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: rgba(176, 141, 87, 0.1);">
                                <i class="fas fa-hourglass-half text-xl" style="color: #7C8574;"></i>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ========== بطاقة الترحيب ========== --}}
                <div class="relative mb-8 rounded-3xl overflow-hidden shadow-md"
                     style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.2);">
                    
                    <div class="relative px-8 py-8">
                        <div class="flex items-center justify-between">
                            <div class="text-left">
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm mb-4"
                                     style="background: rgba(176, 141, 87, 0.12);">
                                    <i class="fas fa-star text-xs" style="color: #B08D57;"></i>
                                    <span style="color: #B08D57;">مرحباً بعودتك</span>
                                </div>
                                <h1 class="text-3xl font-bold mb-2" style="color: #2B1E1A;">{{ auth()->user()->name }}</h1>
                                <p style="color: #7C8574;">استعدي لتجربة جمال راقية 🤎</p>
                            </div>
                            
                            {{-- صورة ثابتة --}}
                            <div class="relative">
                                <div class="w-24 h-24 rounded-full p-1 shadow-md"
                                     style="background: linear-gradient(135deg, #B08D57 0%, #D6C3AD 100%);">
                                    <div class="w-full h-full rounded-full flex items-center justify-center" style="background: #8B6B4A;">
                                        <i class="fas fa-user fa-3x" style="color: #F3EDE6;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== إحصائيات المستخدم (المصححة) ========== --}}
                @php
                    $user = auth()->user();
                    $completedCount = $user->bookings()->where('status', 'completed')->count();
                    $totalActiveCount = $user->bookings()->whereIn('status', ['confirmed', 'completed'])->count();
                    $loyaltyPoints = $user->loyalty_points ?? 0;
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                    
                    {{-- الخدمات المكتملة --}}
                    <div class="rounded-2xl p-5 text-center transition-all duration-300 hover:transform hover:-translate-y-1 shadow-sm"
                         style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3"
                             style="background: rgba(176, 141, 87, 0.12);">
                            <i class="fas fa-check-circle text-2xl" style="color: #B08D57;"></i>
                        </div>
                        <h3 class="text-sm mb-1" style="color: #7C8574;">الخدمات المكتملة</h3>
                        <p class="text-3xl font-bold" style="color: #2B1E1A;">{{ $completedCount }}</p>
                    </div>
                    
                    {{-- إجمالي الحجوزات (confirmed + completed) --}}
                    <div class="rounded-2xl p-5 text-center transition-all duration-300 hover:transform hover:-translate-y-1 shadow-sm"
                         style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3"
                             style="background: rgba(176, 141, 87, 0.12);">
                            <i class="fas fa-calendar-alt text-2xl" style="color: #B08D57;"></i>
                        </div>
                        <h3 class="text-sm mb-1" style="color: #7C8574;">إجمالي الحجوزات</h3>
                        <p class="text-3xl font-bold" style="color: #2B1E1A;">{{ $totalActiveCount }}</p>
                    </div>
                    
                    {{-- نقاط الولاء --}}
                    <div class="rounded-2xl p-5 text-center transition-all duration-300 hover:transform hover:-translate-y-1 shadow-sm"
                         style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3"
                             style="background: rgba(176, 141, 87, 0.12);">
                            <i class="fas fa-gem text-2xl" style="color: #B08D57;"></i>
                        </div>
                        <h3 class="text-sm mb-1" style="color: #7C8574;">نقاط الولاء</h3>
                        <p class="text-3xl font-bold" style="color: #2B1E1A;">{{ $loyaltyPoints }}</p>
                        <div class="mt-3">
                            <div class="w-full rounded-full h-1.5 overflow-hidden" style="background: rgba(176, 141, 87, 0.2);">
                                <div class="h-1.5 rounded-full transition-all duration-500" 
                                     style="width: {{ min(100, $loyaltyPoints) }}%; background: #B08D57;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== أزرار الإجراءات الرئيسية ========== --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <a href="{{ route('customer.bookings.step1') }}" 
                       class="rounded-2xl p-5 text-center transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg"
                       style="background: #B08D57;">
                        <i class="fas fa-calendar-plus text-2xl mb-2 block" style="color: #F3EDE6;"></i>
                        <span class="font-bold text-lg block" style="color: #F3EDE6;">حجز جديد</span>
                        <p style="color: rgba(243, 237, 230, 0.8); font-size: 0.75rem;" class="mt-1">احجزي موعدك الآن</p>
                    </a>

                    <a href="{{ route('customer.queue.form') }}" 
                       class="rounded-2xl p-5 text-center transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md"
                       style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.25);">
                        <i class="fas fa-clock text-2xl mb-2 block" style="color: #B08D57;"></i>
                        <span class="font-bold text-lg block" style="color: #2B1E1A;">طابور الانتظار</span>
                        <p style="color: #7C8574; font-size: 0.75rem;" class="mt-1">انضمي للطابور</p>
                    </a>

                    <a href="{{ route('customer.bookings.index') }}" 
                       class="rounded-2xl p-5 text-center transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md"
                       style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.25);">
                        <i class="fas fa-list-alt text-2xl mb-2 block" style="color: #B08D57;"></i>
                        <span class="font-bold text-lg block" style="color: #2B1E1A;">كل حجوزاتي</span>
                        <p style="color: #7C8574; font-size: 0.75rem;" class="mt-1">عرض وإدارة حجوزاتك</p>
                    </a>
                </div>

                {{-- ========== خدمة إزالة الرموش ========== --}}
                <div class="mb-8">
                    <a href="{{ route('customer.removal.step1') }}" 
                       class="group relative overflow-hidden rounded-2xl p-4 text-center transition-all duration-300 shadow-sm hover:shadow-md block"
                       style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.25);">
                        <div class="relative z-10 flex items-center justify-center gap-3">
                            <i class="fas fa-feather-alt text-xl" style="color: #B08D57;"></i>
                            <span class="font-bold text-lg" style="color: #2B1E1A;">خدمة إزالة الرموش</span>
                            <span style="color: #B9ADA3; font-size: 0.875rem;">إزالة آمنة ولطيفة</span>
                        </div>
                        <div class="absolute inset-0 translate-x-full group-hover:translate-x-0 transition-transform duration-500"
                             style="background: linear-gradient(90deg, transparent, rgba(176, 141, 87, 0.08), transparent);"></div>
                    </a>
                </div>

                {{-- ========== قسم الحجوزات القادمة ========== --}}
                <div class="rounded-2xl overflow-hidden shadow-sm"
                     style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    
                    <div class="px-6 py-4" style="background: rgba(176, 141, 87, 0.08);">
                        <h2 class="font-bold text-right flex items-center justify-end gap-2" style="color: #2B1E1A;">
                            <i class="fas fa-calendar-week"></i>
                            <span>حجوزاتك القادمة</span>
                        </h2>
                    </div>
                    
                    <div class="p-5">
                        @php
                            $upcomingBookings = auth()->user()->bookings()
                                ->where('booking_date', '>=', today())
                                ->where('status', 'confirmed')
                                ->orderBy('booking_date', 'asc')
                                ->take(5)
                                ->get();
                        @endphp

                        @if($upcomingBookings->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcomingBookings as $booking)
                                    @php
                                        $daysLeft = \Carbon\Carbon::parse($booking->booking_date)->diffInDays(today());
                                        if ($daysLeft == 0) {
                                            $dayText = 'اليوم';
                                            $dayBg = 'rgba(176, 141, 87, 0.15)';
                                            $dayColor = '#B08D57';
                                        } elseif ($daysLeft == 1) {
                                            $dayText = 'غداً';
                                            $dayBg = 'rgba(176, 141, 87, 0.1)';
                                            $dayColor = '#B08D57';
                                        } else {
                                            $dayText = "بعد {$daysLeft} أيام";
                                            $dayBg = 'rgba(124, 133, 116, 0.08)';
                                            $dayColor = '#7C8574';
                                        }
                                        
                                        $serviceIcon = $booking->service_type == 'classic' ? 'feather' : ($booking->service_type == 'wet' ? 'tint' : ($booking->service_type == 'wispy' ? 'feather-alt' : ($booking->service_type == 'volume' ? 'layer-group' : 'star')));
                                        $serviceName = [
                                            'classic' => 'كلاسيك', 'wet' => 'Wet Set', 'wispy' => 'Wispy Set', 'volume' => 'فولوم', 'anime' => 'Anime Set'
                                        ][$booking->service_type] ?? $booking->service_type;
                                    @endphp
                                    
                                    <div class="rounded-xl p-4 transition-all duration-300 hover:transform hover:-translate-x-1"
                                         style="background: #F3EDE6; border: 1px solid rgba(176, 141, 87, 0.15);">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                                     style="background: rgba(176, 141, 87, 0.1);">
                                                    <i class="fas fa-{{ $serviceIcon }} text-base" style="color: #B08D57;"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold" style="color: #2B1E1A;">{{ $serviceName }}</p>
                                                    <p style="color: #7C8574; font-size: 0.75rem;">
                                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-medium"
                                                      style="background: {{ $dayBg }}; color: {{ $dayColor }};">
                                                    📅 {{ $dayText }}
                                                </span>
                                                <a href="{{ route('customer.bookings.show', $booking->id) }}" 
                                                   style="color: #B08D57;" 
                                                   class="hover:opacity-80 transition">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4"
                                     style="background: rgba(176, 141, 87, 0.08);">
                                    <i class="fas fa-calendar-times text-3xl" style="color: #7C8574;"></i>
                                </div>
                                <p style="color: #7C8574;">لا توجد حجوزات قادمة</p>
                                <a href="{{ route('customer.bookings.step1') }}" 
                                   class="inline-flex items-center gap-2 mt-4 font-medium transition hover:opacity-80"
                                   style="color: #B08D57;">
                                    <i class="fas fa-plus"></i> احجزي الآن
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #D6C3AD;
    }
    ::-webkit-scrollbar-thumb {
        background: #B08D57;
        border-radius: 10px;
    }
    
    ::selection {
        background-color: #B08D57;
        color: #F3EDE6;
    }
</style>
@endsection