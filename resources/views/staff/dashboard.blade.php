@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        
        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">✨ مرحباً بكِ، {{ Auth::user()->name }}</h1>
                    <p class="text-white/80 mt-1">هذه هي إحصائيات وإدارة عملك اليومي</p>
                </div>
                <div class="text-white/70 text-sm">
                    {{ now()->format('l - d/m/Y') }}
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="rounded-xl p-5 text-center transition-all hover:scale-105" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="flex justify-center mb-2">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(176, 141, 87, 0.2);">
                        <i class="fas fa-calendar-day text-2xl" style="color: #B08D57;"></i>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mt-2">حجوزات اليوم</p>
                <p class="text-3xl font-bold mt-1" style="color: #B08D57;">{{ $todayBookings ?? 0 }}</p>
            </div>

            <div class="rounded-xl p-5 text-center transition-all hover:scale-105" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="flex justify-center mb-2">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(176, 141, 87, 0.2);">
                        <i class="fas fa-clock text-2xl" style="color: #B08D57;"></i>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mt-2">الحجوزات القادمة</p>
                <p class="text-3xl font-bold mt-1" style="color: #B08D57;">{{ $upcomingBookings ?? 0 }}</p>
            </div>

            <div class="rounded-xl p-5 text-center transition-all hover:scale-105" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="flex justify-center mb-2">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-check-circle text-2xl" style="color: #10b981;"></i>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mt-2">الحجوزات المكتملة</p>
                <p class="text-3xl font-bold mt-1" style="color: #10b981;">{{ $completedBookings ?? 0 }}</p>
            </div>

            <div class="rounded-xl p-5 text-center transition-all hover:scale-105" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="flex justify-center mb-2">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(245, 158, 11, 0.2);">
                        <i class="fas fa-star text-2xl" style="color: #f59e0b;"></i>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mt-2">تقييمي</p>
                <div class="mt-1">
                    @if(isset($myRatingValue) && $myRatingValue > 0)
                        <div class="flex justify-center items-center gap-2">
                            <span class="text-2xl font-bold" style="color: #f59e0b;">{{ number_format($myRatingValue, 1) }}</span>
                            <div class="flex text-yellow-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($myRatingValue))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $myRatingValue)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-1">لا توجد تقييمات بعد</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bookings and Schedule --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            {{-- Today's Bookings --}}
            <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">📋 حجوزات اليوم</h3>
                    <span class="text-sm px-3 py-1 rounded-full" style="background: rgba(176, 141, 87, 0.2); color: #B08D57;">
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
                
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse($bookings ?? [] as $booking)
                    <div class="p-4 rounded-lg" style="background: rgba(176, 141, 87, 0.08); border-right: 3px solid #B08D57;">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-lg" style="color: #B08D57;">{{ $booking->booking_time ?? '--' }}</span>
                                    <span class="text-gray-400">|</span>
                                    <span class="font-medium">{{ $booking->user->name ?? 'زبونة' }}</span>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-tag ml-1"></i> {{ $booking->service_type ?? 'خدمة' }}
                                </div>
                            </div>
                            <div>
                                @if($booking->status == 'confirmed')
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">✅ مؤكد</span>
                                @elseif($booking->status == 'completed')
                                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">✓ مكتمل</span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">⏳ قيد الانتظار</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-check text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">لا توجد حجوزات لهذا اليوم</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- My Schedule --}}
            <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">📅 دوامي هذا الأسبوع</h3>
                    <a href="{{ route('staff.schedule') }}" class="text-sm px-3 py-1 rounded-full" style="background: rgba(176, 141, 87, 0.2); color: #B08D57;">
                        عرض الكل <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>

                <div class="space-y-2">
                    @php
                        $daysOfWeek = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
                        $todayIndex = date('w') - 1;
                        if($todayIndex < 0) $todayIndex = 6;
                    @endphp

                    @foreach($daysOfWeek as $index => $dayName)
                        @php
                            $isToday = ($index == $todayIndex);
                            $daySchedule = isset($schedule[$index]) ? $schedule[$index] : null;
                            $isWorking = $daySchedule && isset($daySchedule->status) && $daySchedule->status == 'active';
                        @endphp
                        <div class="p-3 rounded-lg {{ $isToday ? 'bg-gradient-to-r from-amber-50 to-transparent' : '' }}">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <span class="font-medium {{ $isToday ? 'text-[#B08D57] font-bold' : '' }}">
                                        {{ $dayName }}
                                        @if($isToday)
                                            <span class="text-xs mr-2 text-[#B08D57]">(اليوم)</span>
                                        @endif
                                    </span>
                                    @if($isWorking)
                                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">دوام</span>
                                    @else
                                        <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">عطلة</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    @if($isWorking && $daySchedule && isset($daySchedule->start_time))
                                        <i class="far fa-clock ml-1"></i> {{ $daySchedule->start_time }} - {{ $daySchedule->end_time }}
                                    @else
                                        <span class="text-gray-400">— راحة —</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="rounded-xl p-4 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px);">
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('staff.schedule') }}" class="px-5 py-2 rounded-lg text-white" style="background: #B08D57;">
                    <i class="fas fa-calendar-alt"></i> جدول دوامي
                </a>
                <a href="{{ route('staff.bookings') }}" class="px-5 py-2 rounded-lg text-white" style="background: #6B7280;">
                    <i class="fas fa-list-ul"></i> كل الحجوزات
                </a>
                <a href="{{ route('staff.reviews') }}" class="px-5 py-2 rounded-lg text-white" style="background: #f59e0b;">
                    <i class="fas fa-star"></i> تقييماتي
                </a>
            </div>
        </div>

    </div>
</div>
@endsection