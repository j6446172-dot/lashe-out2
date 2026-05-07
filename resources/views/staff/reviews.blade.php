@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        
        <div class="rounded-2xl p-6 mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">⭐ تقييماتي</h1>
                    <p class="text-white/80 mt-1">آراء العملاء عن خدماتك</p>
                </div>
                <a href="{{ route('staff.dashboard') }}" class="text-white/80 hover:text-white">
                    ← العودة للداشبورد
                </a>
            </div>
        </div>

        {{-- Average Rating --}}
        <div class="rounded-xl p-6 mb-6 text-center" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
            <div class="inline-block p-4 rounded-full mb-3" style="background: rgba(245, 158, 11, 0.2);">
                <i class="fas fa-star text-4xl" style="color: #f59e0b;"></i>
            </div>
            <h2 class="text-3xl font-bold" style="color: #f59e0b;">
                {{ $avgRating ? number_format($avgRating, 1) : '0.0' }}
            </h2>
            <div class="flex justify-center gap-1 my-2">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($avgRating ?? 0))
                        <i class="fas fa-star text-2xl text-yellow-400"></i>
                    @elseif($i - 0.5 <= ($avgRating ?? 0))
                        <i class="fas fa-star-half-alt text-2xl text-yellow-400"></i>
                    @else
                        <i class="far fa-star text-2xl text-gray-300"></i>
                    @endif
                @endfor
            </div>
            <p class="text-gray-600">{{ $reviews->count() }} {{ $reviews->count() == 1 ? 'تقييم' : 'تقييمات' }}</p>
        </div>

        {{-- Reviews List --}}
        <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px);">
            <h3 class="text-lg font-bold mb-4">📝 آراء العملاء</h3>
            
            @forelse($reviews as $review)
            <div class="p-4 rounded-lg mb-3" style="background: rgba(176, 141, 87, 0.08); border-right: 3px solid #B08D57;">
                <div class="flex justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-user-circle text-xl" style="color: #B08D57;"></i>
                            <span class="font-bold">{{ $review->customer->name ?? 'عميل' }}</span>
                            <div class="flex text-yellow-400 text-sm mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-700">"{{ $review->comment ?? 'لا يوجد تعليق' }}"</p>
                        <div class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-tag ml-1"></i> {{ $review->booking->service_type ?? 'خدمة' }} |
                            <i class="fas fa-calendar ml-1"></i> {{ $review->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="text-center min-w-[60px]">
                        <span class="text-xl font-bold" style="color: #f59e0b;">{{ number_format($review->rating, 1) }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <i class="fas fa-star text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">لا توجد تقييمات بعد</p>
                <p class="text-sm text-gray-400">كوني أول من يحصل على تقييم ✨</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection