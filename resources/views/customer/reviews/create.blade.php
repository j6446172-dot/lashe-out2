@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                
                {{-- هيدر الصفحة --}}
                <div class="rounded-2xl shadow-md p-6 text-center mb-6" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <i class="fas fa-star text-3xl mb-2" style="color: #F3EDE6;"></i>
                    <h1 class="text-xl font-bold" style="color: #F3EDE6;">⭐ قيمي تجربتك</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">رأيك يهمنا ويساعدنا نكون أفضل</p>
                </div>

                {{-- بطاقة التقييم --}}
                <div class="rounded-2xl p-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
                    
                    {{-- رسالة إنسانية --}}
                    <div class="rounded-xl p-4 mb-5 text-right border-r-4" style="background: rgba(176, 141, 87, 0.08); border-right-color: #B08D57;">
                        <div class="flex items-start justify-end gap-3">
                            <i class="fas fa-heart text-lg mt-0.5" style="color: #B08D57;"></i>
                            <div>
                                <p class="text-sm font-medium" style="color: #2B1E1A;">✨ كلمتك تقدرها الموظفة</p>
                                <p class="text-xs mt-1" style="color: #7C8574;">تقييمك البناء يساعد الموظفة على تحسين مهاراتها</p>
                            </div>
                        </div>
                    </div>

                    {{-- معلومات الحجز --}}
                    <div class="rounded-lg p-3 mb-5" style="background: rgba(176, 141, 87, 0.05);">
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: #7C8574;">الخدمة:</span>
                            <span class="text-sm font-bold" style="color: #2B1E1A;">
                                @if($booking->service_type == 'classic') كلاسيك
                                @elseif($booking->service_type == 'hybrid') هايبرد
                                @elseif($booking->service_type == 'volume') فولوم
                                @else إزالة رموش
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-sm" style="color: #7C8574;">الموظفة:</span>
                            <span class="text-sm font-bold" style="color: #2B1E1A;">{{ $booking->staff->name ?? 'موظفة' }}</span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-sm" style="color: #7C8574;">التاريخ:</span>
                            <span class="text-sm" style="color: #2B1E1A;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('customer.reviews.store', $booking->id) }}">
                        @csrf
                        
                        {{-- التقييم بالنجوم --}}
                        <div class="mb-6">
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">⭐ اختاري التقييم</label>
                            <div class="flex justify-center gap-3 py-3" id="ratingStars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star text-4xl cursor-pointer hover:scale-110 transition" style="color: #D6C3AD;" data-rating="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating" required>
                            <p class="text-xs text-center mt-2" id="ratingText" style="color: #7C8574;"></p>
                        </div>

                        {{-- التعليق --}}
                        <div class="mb-6">
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">💬 تعليقك (اختياري)</label>
                            <textarea name="comment" rows="4" class="w-full px-4 py-3 rounded-xl text-right" style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);" placeholder="شاركنا رأيك... ماذا أعجبك؟ ماذا يمكننا تحسينه؟"></textarea>
                        </div>

                        {{-- الأزرار --}}
                        <div class="flex gap-3">
                            <a href="{{ route('customer.reviews.index') }}" class="flex-1 text-center py-2 rounded-xl font-bold transition hover:opacity-80" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                                <i class="fas fa-times ml-2"></i> تذكر لاحقاً
                            </a>
                            <button type="submit" class="flex-1 py-2 rounded-xl font-bold transition hover:shadow-lg" style="background: #B08D57; color: #F3EDE6;">
                                <i class="fas fa-check ml-2"></i> إرسال التقييم
                            </button>
                        </div>
                        
                        <p class="text-xs text-center mt-4" style="color: #7C8574;">
                            <i class="fas fa-lock-alt ml-1"></i> تقييمك سيساعد غيرك في اختيار الخدمة المناسبة
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    let selectedRating = 0;
    const stars = document.querySelectorAll('#ratingStars i');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('ratingText');

    const ratingMessages = {
        1: '😞 سيء - لم تكن تجربة جيدة',
        2: '🙁 مقبول - يحتاج للتحسين',
        3: '😐 جيد - يمكن أن يكون أفضل',
        4: '🙂 جيد جداً - ممتازة',
        5: '😍 ممتاز - تجربة رائعة!'
    };

    stars.forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = this.dataset.rating;
            ratingInput.value = selectedRating;
            ratingText.textContent = ratingMessages[selectedRating] || '';
            
            stars.forEach((s, index) => {
                if (index < selectedRating) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                    s.style.color = '#B08D57';
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                    s.style.color = '#D6C3AD';
                }
            });
        });
    });
</script>
@endsection