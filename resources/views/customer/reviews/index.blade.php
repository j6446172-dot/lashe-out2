@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full max-w-3xl">
                
                {{-- هيدر الصفحة --}}
                <div class="rounded-2xl shadow-md p-6 text-center mb-6" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <i class="fas fa-star text-3xl mb-2" style="color: #F3EDE6;"></i>
                    <h1 class="text-xl font-bold" style="color: #F3EDE6;">⭐ تقييماتي</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">جميع تقييماتك للخدمات التي حصلت عليها</p>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl text-right" style="background: rgba(16, 185, 129, 0.1); border-right: 3px solid #10b981;">
                        <i class="fas fa-check-circle ml-2" style="color: #10b981;"></i>
                        <span style="color: #2B1E1A;">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 rounded-xl text-right" style="background: rgba(220, 38, 38, 0.1); border-right: 3px solid #dc2626;">
                        <i class="fas fa-exclamation-circle ml-2" style="color: #dc2626;"></i>
                        <span style="color: #991b1b;">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- ===== الخدمات التي تحتاج تقييم ===== --}}
                @php
                    $needReview = auth()->user()->bookings()
                        ->where('status', 'completed')
                        ->whereDoesntHave('review')
                        ->orderBy('booking_date', 'desc')
                        ->get();
                @endphp

                @if($needReview->count() > 0)
                <div class="rounded-xl p-5 mb-6 border-r-4" style="background: rgba(176, 141, 87, 0.08); border-right-color: #B08D57;">
                    <div class="flex items-center justify-end gap-2 mb-4">
                        <i class="fas fa-star" style="color: #B08D57;"></i>
                        <h3 class="font-bold" style="color: #2B1E1A;">خدماتك تحتاج تقييم</h3>
                    </div>
                    <div class="space-y-3">
                        @foreach($needReview as $booking)
                            <div class="flex justify-between items-center rounded-lg p-3" style="background: rgba(255, 255, 255, 0.8);">
                                <div class="text-left">
                                    <span class="text-xs" style="color: #7C8574;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold" style="color: #2B1E1A;">
                                        @if($booking->service_type == 'classic') كلاسيك
                                        @elseif($booking->service_type == 'hybrid') هايبرد
                                        @elseif($booking->service_type == 'volume') فولوم
                                        @else إزالة رموش
                                        @endif
                                    </p>
                                    <p class="text-sm" style="color: #7C8574;">{{ $booking->staff->name ?? 'موظفة' }}</p>
                                </div>
                                <a href="{{ route('customer.reviews.create', $booking->id) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-bold transition hover:shadow-lg hover:scale-105"
                                   style="background: #B08D57; color: #F3EDE6;">
                                    <i class="fas fa-star ml-1"></i> قيم الآن
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ===== التقييمات السابقة ===== --}}
                @php
                    $reviews = auth()->user()->reviews()->with('booking.staff')->orderBy('created_at', 'desc')->get();
                @endphp

                @if($reviews->count() > 0)
                    <div class="rounded-xl p-5" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
                        <div class="flex items-center justify-end gap-2 mb-4 pb-2 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                            <i class="fas fa-check-circle" style="color: #10b981;"></i>
                            <h3 class="font-bold" style="color: #2B1E1A;">✅ تقييماتك السابقة</h3>
                        </div>
                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <div class="border-b pb-3 last:border-0" style="border-color: rgba(176, 141, 87, 0.1);">
                                    <div class="flex justify-between items-start">
                                        <div class="text-left">
                                            <div class="flex mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-[#B08D57]' : 'text-[#D6C3AD]' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="text-xs" style="color: #7C8574;">{{ $review->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold" style="color: #2B1E1A;">
                                                @if($review->booking->service_type == 'classic') كلاسيك
                                                @elseif($review->booking->service_type == 'hybrid') هايبرد
                                                @elseif($review->booking->service_type == 'volume') فولوم
                                                @else إزالة رموش
                                                @endif
                                            </p>
                                            <p class="text-sm" style="color: #7C8574;">{{ $review->booking->staff->name ?? 'موظفة' }}</p>
                                        </div>
                                    </div>
                                    @if($review->comment)
                                        <div class="mt-2 pr-4">
                                            <p class="text-sm italic" style="color: #7C8574;">"{{ $review->comment }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($needReview->count() == 0)
                    <div class="rounded-xl p-10 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
                        <i class="fas fa-star-half-alt text-5xl mb-3" style="color: rgba(176, 141, 87, 0.3);"></i>
                        <p class="text-lg" style="color: #7C8574;">لا توجد تقييمات بعد</p>
                        <p class="text-sm mt-1" style="color: #B9ADA3;">بعد إتمام أي خدمة، يمكنك تقييم تجربتك</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection