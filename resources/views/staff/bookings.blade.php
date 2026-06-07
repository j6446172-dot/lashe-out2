@extends('layouts.app')

@section('content')
<meta http-equiv="refresh" content="30">
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        
        <!-- عرض رسائل النجاح -->
        @if(session('success'))
            <div class="rounded-xl p-4 mb-6 shadow-md" style="background: #4CAF50; color: white;">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="rounded-xl p-4 mb-6 shadow-md" style="background: #f44336; color: white;">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="rounded-2xl p-6 mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">📋 جميع الحجوزات</h1>
                <a href="{{ route('staff.dashboard') }}" class="text-white/80 hover:text-white">
                    ← العودة للداشبورد
                </a>
            </div>
        </div>

        <!-- ========== أزرار تبديل الأقسام ========== -->
        <div class="flex gap-4 mb-6 justify-center">
            <button onclick="showSection('upcoming')" id="btn-upcoming" 
                    class="px-6 py-3 rounded-xl font-bold text-lg transition shadow-md"
                    style="background: #B08D57; color: white;">
                📅 حجوزات قادمة
            </button>
            <button onclick="showSection('past')" id="btn-past" 
                    class="px-6 py-3 rounded-xl font-bold text-lg transition shadow-md"
                    style="background: #9a7848; color: white;">
                ✅ حجوزات سابقة
            </button>
            <button onclick="showSection('cancelled')" id="btn-cancelled" 
                    class="px-6 py-3 rounded-xl font-bold text-lg transition shadow-md"
                    style="background: #9a7848; color: white;">
                ❌ حجوزات ملغية
            </button>
        </div>

        <!-- ==================== القسم 1: حجوزات قادمة (confirmed فقط) ==================== -->
        <div id="section-upcoming" class="rounded-xl p-6 mb-8" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px);">
            <h2 class="text-xl font-bold mb-4" style="color: #2B1E1A;">
                📅 حجوزات قادمة 
                <span class="text-sm font-normal text-gray-600">({{ $upcomingBookings->count() }})</span>
            </h2>
            
            <div class="flex gap-3 mb-4 flex-wrap">
                <a href="{{ route('staff.bookings', ['upcoming_filter' => 'today', 'past_filter' => $pastFilter, 'cancelled_filter' => $cancelledFilter, 'section' => 'upcoming']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $upcomingFilter == 'today' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $upcomingFilter == 'today' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📅 اليوم
                </a>
                <a href="{{ route('staff.bookings', ['upcoming_filter' => 'week', 'past_filter' => $pastFilter, 'cancelled_filter' => $cancelledFilter, 'section' => 'upcoming']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $upcomingFilter == 'week' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $upcomingFilter == 'week' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📆 هذا الأسبوع
                </a>
                <a href="{{ route('staff.bookings', ['upcoming_filter' => 'all', 'past_filter' => $pastFilter, 'cancelled_filter' => $cancelledFilter, 'section' => 'upcoming']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $upcomingFilter == 'all' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $upcomingFilter == 'all' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📋 الكل
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1200px]">
                    <thead>
                        <tr style="border-bottom: 2px solid #B08D57;">
                            <th class="p-3 text-right">التاريخ</th>
                            <th class="p-3 text-right">الوقت</th>
                            <th class="p-3 text-right">الزبونة</th>
                            <th class="p-3 text-right">رقم التليفون</th>
                            <th class="p-3 text-right">الخدمة</th>
                            <th class="p-3 text-right">السعر</th>
                            <th class="p-3 text-right">الموقع</th>
                            <th class="p-3 text-right" style="min-width: 280px;">📍 العنوان</th>
                            <th class="p-3 text-right">الحالة</th>
                            <th class="p-3 text-right">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingBookings as $booking)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td class="p-3">{{ $booking->booking_date }}</td>
                            <td class="p-3">{{ $booking->booking_time }}</td>
                            <td class="p-3">{{ $booking->user->name ?? 'زبونة' }}</td>
                            <td class="p-3">
                                <a href="tel:{{ $booking->user->phone }}" class="hover:underline" style="color: #2196F3;">
                                    📞 {{ $booking->user->phone ?? '—' }}
                                </a>
                            </td>
                            <td class="p-3">{{ $booking->service_type ?? 'خدمة' }}</td>
                            <td class="p-3">{{ $booking->price ? number_format($booking->price) . ' ج.م' : '—' }}</td>
                            <td class="p-3">
                                @if($booking->location == 'home')
                                    <span class="text-xs font-bold" style="color: #B08D57;">🏠 خدمة منزلية</span>
                                @else
                                    <span class="text-xs" style="color: #7C8574;">🏢 في الصالون</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($booking->location == 'home')
                                    {{-- زر إظهار الموقع --}}
                                    <button onclick="toggleLocation({{ $booking->id }})" 
                                            class="show-location-btn-{{ $booking->id }} text-xs font-bold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                            style="background: #B08D57; color: white;">
                                        📍 إظهار الموقع
                                    </button>
                                    
                                    {{-- تفاصيل الموقع المخفية --}}
                                    <div id="location-details-{{ $booking->id }}" 
                                         class="location-details hidden mt-2 p-3 rounded-lg text-right"
                                         style="background: rgba(176, 141, 87, 0.1); border-right: 3px solid #B08D57;">
                                        
                                        <div class="mb-2">
                                            <i class="fas fa-location-dot ml-1" style="color: #B08D57;"></i>
                                            <strong>العنوان:</strong>
                                            <div class="mt-1 text-sm" style="color: #2B1E1A;">
                                                {{ $booking->address_text ? Str::limit($booking->address_text, 100) : 'لم يتم إضافة عنوان' }}
                                            </div>
                                        </div>
                                        
                                        @if($booking->building_number || $booking->apartment)
                                            <div class="flex flex-wrap gap-2 mb-2">
                                                @if($booking->building_number)
                                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full" style="background: rgba(176, 141, 87, 0.15); color: #5d4e32;">
                                                        🏢 بناية: {{ $booking->building_number }}
                                                    </span>
                                                @endif
                                                @if($booking->apartment)
                                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full" style="background: rgba(176, 141, 87, 0.15); color: #5d4e32;">
                                                        🏠 شقة: {{ $booking->apartment }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($booking->latitude && $booking->longitude)
                                            <div class="mt-2">
                                                <a href="https://www.google.com/maps?q={{ $booking->latitude }},{{ $booking->longitude }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                                   style="background: #2196F3; color: white;">
                                                    <i class="fas fa-map-marker-alt"></i> فتح الموقع على الخريطة
                                                </a>
                                            </div>
                                            <div class="mt-1 text-[10px]" style="color: #999;">
                                                📍 {{ number_format($booking->latitude, 6) }}, {{ number_format($booking->longitude, 6) }}
                                            </div>
                                        @else
                                            <div class="mt-2 text-xs" style="color: #f44336;">
                                                <i class="fas fa-exclamation-triangle"></i> لا توجد إحداثيات للموقع
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-3 py-1.5 rounded-full" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                                        <i class="fas fa-building"></i> العنوان: العبدلي - مجمع لاش أوت
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">✅ مؤكد</span>
                            </td>
                            <td class="p-3">
                                <div class="flex gap-2 flex-wrap">
                                    <form action="{{ route('staff.booking.update-status', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-3 py-1 text-xs font-bold rounded transition" style="background: #2196F3; color: white;" onclick="return confirm('هل أنت متأكدة من إكمال هذه الخدمة؟')">
                                            ✨ مكتمل
                                        </button>
                                    </form>
                                    <form action="{{ route('staff.booking.update-status', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="px-3 py-1 text-xs font-bold rounded transition" style="background: #f44336; color: white;" onclick="return confirm('هل أنت متأكدة من إلغاء هذا الحجز؟')">
                                            ❌ ملغي
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center p-8 text-gray-500">
                                <i class="fas fa-calendar-check text-4xl mb-2 block"></i>
                                لا توجد حجوزات قادمة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== القسم 2: حجوزات سابقة (مكتملة) ==================== -->
        <div id="section-past" class="rounded-xl p-6 mb-8" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); display: none;">
            <h2 class="text-xl font-bold mb-4" style="color: #2B1E1A;">
                ✅ حجوزات سابقة (مكتملة)
                <span class="text-sm font-normal text-gray-600">({{ $pastBookings->count() }})</span>
            </h2>
            
            <div class="flex gap-3 mb-4 flex-wrap">
                <a href="{{ route('staff.bookings', ['upcoming_filter' => $upcomingFilter, 'past_filter' => 'today', 'cancelled_filter' => $cancelledFilter, 'section' => 'past']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $pastFilter == 'today' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $pastFilter == 'today' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📅 اليوم
                </a>
                <a href="{{ route('staff.bookings', ['upcoming_filter' => $upcomingFilter, 'past_filter' => 'week', 'cancelled_filter' => $cancelledFilter, 'section' => 'past']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $pastFilter == 'week' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $pastFilter == 'week' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📆 الأسبوع الماضي
                </a>
                <a href="{{ route('staff.bookings', ['upcoming_filter' => $upcomingFilter, 'past_filter' => 'all', 'cancelled_filter' => $cancelledFilter, 'section' => 'past']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $pastFilter == 'all' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $pastFilter == 'all' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📋 الكل
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1200px]">
                    <thead>
                        <tr style="border-bottom: 2px solid #B08D57;">
                            <th class="p-3 text-right">التاريخ</th>
                            <th class="p-3 text-right">الوقت</th>
                            <th class="p-3 text-right">الزبونة</th>
                            <th class="p-3 text-right">رقم التليفون</th>
                            <th class="p-3 text-right">الخدمة</th>
                            <th class="p-3 text-right">السعر</th>
                            <th class="p-3 text-right">الموقع</th>
                            <th class="p-3 text-right" style="min-width: 280px;">📍 العنوان</th>
                            <th class="p-3 text-right">الحالة</th>
                            <th class="p-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pastBookings as $booking)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td class="p-3">{{ $booking->booking_date }}</td>
                            <td class="p-3">{{ $booking->booking_time }}</td>
                            <td class="p-3">{{ $booking->user->name ?? 'زبونة' }}</td>
                            <td class="p-3">
                                <a href="tel:{{ $booking->user->phone }}" class="hover:underline" style="color: #2196F3;">
                                    📞 {{ $booking->user->phone ?? '—' }}
                                </a>
                            </td>
                            <td class="p-3">{{ $booking->service_type ?? 'خدمة' }}</td>
                            <td class="p-3">{{ $booking->price ? number_format($booking->price) . ' ج.م' : '—' }}</td>
                            <td class="p-3">
                                @if($booking->location == 'home')
                                    <span class="text-xs font-bold" style="color: #B08D57;">🏠 خدمة منزلية</span>
                                @else
                                    <span class="text-xs" style="color: #7C8574;">🏢 في الصالون</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($booking->location == 'home')
                                    <button onclick="toggleLocation({{ $booking->id }})" 
                                            class="show-location-btn-{{ $booking->id }} text-xs font-bold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                            style="background: #B08D57; color: white;">
                                        📍 إظهار الموقع
                                    </button>
                                    <div id="location-details-{{ $booking->id }}" 
                                         class="location-details hidden mt-2 p-3 rounded-lg text-right"
                                         style="background: rgba(176, 141, 87, 0.1); border-right: 3px solid #B08D57;">
                                        <div class="mb-2">
                                            <i class="fas fa-location-dot ml-1" style="color: #B08D57;"></i>
                                            <strong>العنوان:</strong>
                                            <div class="mt-1 text-sm" style="color: #2B1E1A;">
                                                {{ $booking->address_text ? Str::limit($booking->address_text, 100) : 'لم يتم إضافة عنوان' }}
                                            </div>
                                        </div>
                                        @if($booking->building_number || $booking->apartment)
                                            <div class="flex flex-wrap gap-2 mb-2">
                                                @if($booking->building_number)
                                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full" style="background: rgba(176, 141, 87, 0.15); color: #5d4e32;">🏢 بناية: {{ $booking->building_number }}</span>
                                                @endif
                                                @if($booking->apartment)
                                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full" style="background: rgba(176, 141, 87, 0.15); color: #5d4e32;">🏠 شقة: {{ $booking->apartment }}</span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($booking->latitude && $booking->longitude)
                                            <div class="mt-2">
                                                <a href="https://www.google.com/maps?q={{ $booking->latitude }},{{ $booking->longitude }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                                   style="background: #2196F3; color: white;">
                                                    <i class="fas fa-map-marker-alt"></i> فتح الموقع
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-3 py-1.5 rounded-full" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                                        <i class="fas fa-building"></i> في الصالون
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">✓ مكتمل</span>
                            </td>
                            <td class="p-3">–
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center p-8 text-gray-500">
                                <i class="fas fa-check-circle text-4xl mb-2 block"></i>
                                لا توجد حجوزات سابقة مكتملة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== القسم 3: حجوزات ملغية ==================== -->
        <div id="section-cancelled" class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); display: none;">
            <h2 class="text-xl font-bold mb-4" style="color: #2B1E1A;">
                ❌ حجوزات ملغية
                <span class="text-sm font-normal text-gray-600">({{ $cancelledBookings->count() }})</span>
            </h2>
            
            <div class="flex gap-3 mb-4 flex-wrap">
                <a href="{{ route('staff.bookings', ['upcoming_filter' => $upcomingFilter, 'past_filter' => $pastFilter, 'cancelled_filter' => 'today', 'section' => 'cancelled']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $cancelledFilter == 'today' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $cancelledFilter == 'today' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📅 اليوم
                </a>
                <a href="{{ route('staff.bookings', ['upcoming_filter' => $upcomingFilter, 'past_filter' => $pastFilter, 'cancelled_filter' => 'week', 'section' => 'cancelled']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $cancelledFilter == 'week' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $cancelledFilter == 'week' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📆 الأسبوع الماضي
                </a>
                <a href="{{ route('staff.bookings', ['upcoming_filter' => $upcomingFilter, 'past_filter' => $pastFilter, 'cancelled_filter' => 'all', 'section' => 'cancelled']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $cancelledFilter == 'all' ? 'text-white' : 'text-gray-700' }}" 
                   style="{{ $cancelledFilter == 'all' ? 'background: #B08D57;' : 'background: #f0f0f0;' }}">
                    📋 الكل
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1200px]">
                    <thead>
                        <tr style="border-bottom: 2px solid #B08D57;">
                            <th class="p-3 text-right">التاريخ</th>
                            <th class="p-3 text-right">الوقت</th>
                            <th class="p-3 text-right">الزبونة</th>
                            <th class="p-3 text-right">رقم التليفون</th>
                            <th class="p-3 text-right">الخدمة</th>
                            <th class="p-3 text-right">السعر</th>
                            <th class="p-3 text-right">الموقع</th>
                            <th class="p-3 text-right" style="min-width: 280px;">📍 العنوان</th>
                            <th class="p-3 text-right">الحالة</th>
                            <th class="p-3 text-right"></th>
                         </tr>
                    </thead>
                    <tbody>
                        @forelse($cancelledBookings as $booking)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td class="p-3">{{ $booking->booking_date }}</td>
                            <td class="p-3">{{ $booking->booking_time }}</td>
                            <td class="p-3">{{ $booking->user->name ?? 'زبونة' }}</td>
                            <td class="p-3">
                                <a href="tel:{{ $booking->user->phone }}" class="hover:underline" style="color: #2196F3;">
                                    📞 {{ $booking->user->phone ?? '—' }}
                                </a>
                            </td>
                            <td class="p-3">{{ $booking->service_type ?? 'خدمة' }}</td>
                            <td class="p-3">{{ $booking->price ? number_format($booking->price) . ' ج.م' : '—' }}</td>
                            <td class="p-3">
                                @if($booking->location == 'home')
                                    <span class="text-xs font-bold" style="color: #B08D57;">🏠 خدمة منزلية</span>
                                @else
                                    <span class="text-xs" style="color: #7C8574;">🏢 في الصالون</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($booking->location == 'home')
                                    <button onclick="toggleLocation({{ $booking->id }})" 
                                            class="show-location-btn-{{ $booking->id }} text-xs font-bold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                            style="background: #B08D57; color: white;">
                                        📍 إظهار الموقع
                                    </button>
                                    <div id="location-details-{{ $booking->id }}" 
                                         class="location-details hidden mt-2 p-3 rounded-lg text-right"
                                         style="background: rgba(176, 141, 87, 0.1); border-right: 3px solid #B08D57;">
                                        <div class="mb-2">
                                            <i class="fas fa-location-dot ml-1" style="color: #B08D57;"></i>
                                            <strong>العنوان:</strong>
                                            <div class="mt-1 text-sm" style="color: #2B1E1A;">
                                                {{ $booking->address_text ? Str::limit($booking->address_text, 100) : 'لم يتم إضافة عنوان' }}
                                            </div>
                                        </div>
                                        @if($booking->building_number || $booking->apartment)
                                            <div class="flex flex-wrap gap-2 mb-2">
                                                @if($booking->building_number)
                                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full" style="background: rgba(176, 141, 87, 0.15); color: #5d4e32;">🏢 بناية: {{ $booking->building_number }}</span>
                                                @endif
                                                @if($booking->apartment)
                                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full" style="background: rgba(176, 141, 87, 0.15); color: #5d4e32;">🏠 شقة: {{ $booking->apartment }}</span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($booking->latitude && $booking->longitude)
                                            <div class="mt-2">
                                                <a href="https://www.google.com/maps?q={{ $booking->latitude }},{{ $booking->longitude }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                                   style="background: #2196F3; color: white;">
                                                    <i class="fas fa-map-marker-alt"></i> فتح الموقع
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-3 py-1.5 rounded-full" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                                        <i class="fas fa-building"></i> في الصالون
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">✗ ملغي</span>
                            </td>
                            <td class="p-3">–
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center p-8 text-gray-500">
                                <i class="fas fa-ban text-4xl mb-2 block"></i>
                                لا توجد حجوزات ملغية
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
    .hidden {
        display: none;
    }
    .block {
        display: block;
    }
    .location-details {
        transition: all 0.3s ease;
    }
</style>

<script>
    function showSection(section) {
        const upcoming = document.getElementById('section-upcoming');
        const past = document.getElementById('section-past');
        const cancelled = document.getElementById('section-cancelled');
        
        if (upcoming) upcoming.style.display = 'none';
        if (past) past.style.display = 'none';
        if (cancelled) cancelled.style.display = 'none';
        
        const activeSection = document.getElementById('section-' + section);
        if (activeSection) activeSection.style.display = 'block';
        
        const btnUpcoming = document.getElementById('btn-upcoming');
        const btnPast = document.getElementById('btn-past');
        const btnCancelled = document.getElementById('btn-cancelled');
        
        const buttons = [btnUpcoming, btnPast, btnCancelled];
        buttons.forEach(btn => {
            if (btn) {
                btn.style.opacity = '0.7';
                btn.style.background = '#9a7848';
            }
        });
        
        if (section === 'upcoming' && btnUpcoming) {
            btnUpcoming.style.opacity = '1';
            btnUpcoming.style.background = '#B08D57';
        } else if (section === 'past' && btnPast) {
            btnPast.style.opacity = '1';
            btnPast.style.background = '#B08D57';
        } else if (section === 'cancelled' && btnCancelled) {
            btnCancelled.style.opacity = '1';
            btnCancelled.style.background = '#B08D57';
        }
        
        localStorage.setItem('selectedBookingSection', section);
    }
    
    // دالة إظهار/إخفاء تفاصيل الموقع
    function toggleLocation(bookingId) {
        const detailsDiv = document.getElementById('location-details-' + bookingId);
        const btn = document.querySelector('.show-location-btn-' + bookingId);
        
        if (detailsDiv.classList.contains('hidden')) {
            detailsDiv.classList.remove('hidden');
            detailsDiv.classList.add('block');
            btn.innerHTML = '📍 إخفاء الموقع';
            btn.style.background = '#6c5ce7';
        } else {
            detailsDiv.classList.add('hidden');
            detailsDiv.classList.remove('block');
            btn.innerHTML = '📍 إظهار الموقع';
            btn.style.background = '#B08D57';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        let section = urlParams.get('section');
        
        if (!section) {
            section = localStorage.getItem('selectedBookingSection');
        }
        if (!section) {
            section = 'upcoming';
        }
        showSection(section);
    });
</script>
@endsection