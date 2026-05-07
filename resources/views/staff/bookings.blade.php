@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        
        <div class="rounded-2xl p-6 mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">📋 جميع الحجوزات</h1>
                <a href="{{ route('staff.dashboard') }}" class="text-white/80 hover:text-white">
                    ← العودة للداشبورد
                </a>
            </div>
        </div>

        <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px);">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 2px solid #B08D57;">
                            <th class="p-3 text-right">التاريخ</th>
                            <th class="p-3 text-right">الوقت</th>
                            <th class="p-3 text-right">الزبونة</th>
                            <th class="p-3 text-right">الخدمة</th>
                            <th class="p-3 text-right">السعر</th>
                            <th class="p-3 text-right">الموقع</th>
                            <th class="p-3 text-right">الحالة</th>
                            <th class="p-3 text-right">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings ?? [] as $booking)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td class="p-3">{{ $booking->booking_date }}</td>
                            <td class="p-3">{{ $booking->booking_time }}</td>
                            {{-- 🔥 تصحيح: استخدام user بدلاً من customer --}}
                            <td class="p-3">{{ $booking->user->name ?? 'زبونة' }}</td>
                            <td class="p-3">{{ $booking->service_type ?? 'خدمة' }}</td>
                            <td class="p-3">{{ $booking->price ? number_format($booking->price) . ' ج.م' : '—' }}</td>
                            <td class="p-3">{{ $booking->location ?? '—' }}</td>
                            <td class="p-3">
                                @if($booking->status == 'confirmed')
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">✅ مؤكد</span>
                                @elseif($booking->status == 'completed')
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">✓ مكتمل</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">✗ ملغي</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">⏳ قيد الانتظار</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <form action="{{ route('staff.booking.update-status', $booking->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="text-sm border rounded px-2 py-1">
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>تأكيد</option>
                                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center p-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-2 block"></i>
                                لا توجد حجوزات
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- إضافة روابط التصفح (Pagination) --}}
            @if(isset($bookings) && method_exists($bookings, 'links'))
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection