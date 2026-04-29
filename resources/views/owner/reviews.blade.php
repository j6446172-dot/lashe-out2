@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                
                <div class="rounded-2xl p-6 text-center mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <h1 class="text-2xl font-bold" style="color: #F3EDE6;">⭐ التقييمات</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">تقييمات العملاء للخدمات</p>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">⭐ متوسط التقييم</p>
                        <p class="text-2xl font-black mt-2" style="color: #B08D57;">{{ number_format($avgRating ?? 0, 1) }} / 5</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">📝 عدد التقييمات</p>
                        <p class="text-2xl font-black mt-2" style="color: #B08D57;">{{ $totalReviews ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">🏆 أعلى تقييم</p>
                        <p class="text-2xl font-black mt-2" style="color: #f59e0b;">{{ number_format($maxRating ?? 0, 1) }} ⭐</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">📅 تقييمات اليوم</p>
                        <p class="text-2xl font-black mt-2" style="color: #10b981;">{{ $todayReviews ?? 0 }}</p>
                    </div>
                </div>

                <div class="flex gap-3 mb-4 items-center">
                    <select class="px-3 py-2.5 rounded-xl" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);" onchange="window.location.href='{{ route('owner.reviews') }}?rating='+this.value">
                        <option value="">كل التقييمات</option>
                        @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} نجوم</option>
                        @endfor
                    </select>
                    <select class="px-3 py-2.5 rounded-xl" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);" onchange="window.location.href='{{ route('owner.reviews') }}?staff='+this.value">
                        <option value="">كل الموظفات</option>
                        @foreach($staffList ?? [] as $s)
                        <option value="{{ $s->id }}" {{ request('staff') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('owner.dashboard') }}" class="px-5 py-2.5 rounded-xl font-bold transition" style="background: rgba(176, 141, 87, 0.1); color: #B08D57; text-decoration: none;">← العودة</a>
                </div>

                <div class="rounded-xl p-6 overflow-x-auto" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <table class="w-full text-right" style="border-collapse: collapse; min-width: 800px; font-size: 13px;">
                        <thead>
                            <tr style="border-bottom: 2px solid rgba(176, 141, 87, 0.2); color: #B08D57;">
                                <th class="p-3">العميلة</th>
                                <th class="p-3">الخدمة</th>
                                <th class="p-3">الموظفة</th>
                                <th class="p-3">التقييم</th>
                                <th class="p-3">التعليق</th>
                                <th class="p-3">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews ?? [] as $r)
                            <tr style="border-bottom: 1px solid rgba(176, 141, 87, 0.1);">
                                <td class="p-3 font-bold" style="color: #2B1E1A;">{{ $r->user->name ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $r->booking->service ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $r->staff->name ?? '—' }}</td>
                                <td class="p-3" style="color: #f59e0b;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $r->rating) ⭐ @else ☆ @endif
                                    @endfor
                                </td>
                                <td class="p-3" style="color: #7C8574;">{{ $r->comment ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $r->created_at ? $r->created_at->format('Y-m-d') : '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="p-8 text-center" style="color: #7C8574;">لا توجد تقييمات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection