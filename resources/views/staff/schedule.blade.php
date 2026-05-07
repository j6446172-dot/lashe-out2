@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        
        <div class="rounded-2xl p-6 mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <h1 class="text-2xl font-bold text-white">📅 جدول دوامي</h1>
        </div>

        <div class="rounded-xl p-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px);">
            {{-- التحقق من وجود المتغير schedule --}}
            @isset($schedule)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-[#B08D57]">
                                <th class="p-3 text-right">اليوم</th>
                                <th class="p-3 text-right">الحالة</th>
                                <th class="p-3 text-right">من الساعة</th>
                                <th class="p-3 text-right">إلى الساعة</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- افترضنا أن $days موجودة في الـ Controller كما هو مطلوب --}}
                            @forelse($days as $index => $dayName)
                            <tr class="border-b">
                                <td class="p-3">{{ $dayName }}</td>
                                <td class="p-3">
                                    {{-- نستخدم $schedule[$index] لأننا رتبنا المصفوفة بالأرقام --}}
                                    @if(isset($schedule[$index]) && $schedule[$index]->status == 'active')
                                        <span class="text-green-600">✅ دوام</span>
                                    @else
                                        <span class="text-red-600">🔴 عطلة</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    {{ isset($schedule[$index]) && $schedule[$index]->start_time ? $schedule[$index]->start_time : '—' }}
                                </td>
                                <td class="p-3">
                                    {{ isset($schedule[$index]) && $schedule[$index]->end_time ? $schedule[$index]->end_time : '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center p-4 text-red-500">
                                    لا توجد بيانات أيام محددة
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-red-600 p-8">
                    ⚠️ لا توجد بيانات للعرض
                    <br>
                    <small>Staff ID: {{ Auth::id() }}</small>
                </div>
            @endisset
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('staff.dashboard') }}" class="inline-block px-6 py-2 rounded-lg text-white" style="background: #B08D57;">
                ← العودة للوحة التحكم
            </a>
        </div>
    </div>
</div>
@endsection