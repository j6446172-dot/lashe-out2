@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        
        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">📅 جدول دوامي الأسبوعي</h1>
                    <p class="text-white/70 text-xs mt-1">يتم التحديث بناءً على قرارات الإدارة والصالون</p>
                </div>
                <div class="text-white/80 bg-white/10 px-4 py-1 rounded-full text-sm">
                    {{ now()->format('Y / m / d') }}
                </div>
            </div>
        </div>

        <div class="rounded-xl p-6 shadow-sm" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
            @php
                // مصفوفة الأيام الثابتة
                $daysNames = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
                
                // حساب اليوم الحالي
                $w = (int)date('w'); 
                $todayIndex = ($w + 1) % 7; 
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="border-b-2 border-[#B08D57]">
                            <th class="p-4 text-gray-700">اليوم</th>
                            <th class="p-4 text-gray-700">حالة الصالون</th>
                            <th class="p-4 text-gray-700">حالتك الخاصة</th>
                            <th class="p-4 text-gray-700">بداية الدوام</th>
                            <th class="p-4 text-gray-700">نهاية الدوام</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daysNames as $index => $dayName)
                            @php
                                // 1. جلب حالة الصالون الأساسية من الأونر
                                $salonDay = \DB::table('salon_schedule')->where('day_of_week', $index)->first();
                                $salonIsOpen = $salonDay ? $salonDay->is_open : ($index != 6); // افتراضاً الجمعة عطلة لو الجدول فارغ

                                // 2. جلب سجل الموظفة الخاص (إجازات وضعها الأونر لكِ)
                                $staffDay = \DB::table('staff_schedule')
                                    ->where('staff_id', auth()->id())
                                    ->where('day_of_week', $index)
                                    ->first();

                                // 3. منطق تحديد الحالة النهائية
                                $isToday = ($index == $todayIndex);
                                
                                // هل يوجد دوام فعلي؟ (يجب أن يكون الصالون مفتوحاً والموظفة ليست في إجازة)
                                $isWorking = false;
                                if ($salonIsOpen) {
                                    if ($staffDay) {
                                        $isWorking = in_array($staffDay->status, ['active', 'open']);
                                    } else {
                                        $isWorking = true; // دوام تلقائي طالما الصالون مفتوح
                                    }
                                }

                                // تحديد أوقات الدوام (أولوية للموظفة ثم الصالون)
                                $startTime = ($staffDay && $staffDay->start_time) ? $staffDay->start_time : ($salonDay->start_time ?? null);
                                $endTime = ($staffDay && $staffDay->end_time) ? $staffDay->end_time : ($salonDay->end_time ?? null);
                            @endphp
                            
                            <tr class="border-b border-gray-100 transition-colors {{ $isToday ? 'bg-amber-50/50' : 'hover:bg-gray-50/50' }}">
                                <td class="p-4">
                                    <span class="font-bold {{ $isToday ? 'text-[#B08D57]' : 'text-gray-700' }}">
                                        {{ $dayName }}
                                    </span>
                                    @if($isToday)
                                        <span class="mr-2 text-[10px] bg-[#B08D57] text-white px-2 py-0.5 rounded-full">اليوم</span>
                                    @endif
                                </td>
                                
                                {{-- حالة الصالون --}}
                                <td class="p-4">
                                    @if($salonIsOpen)
                                        <span class="text-xs text-green-600 font-medium"><i class="fas fa-store ml-1"></i> مفتوح</span>
                                    @else
                                        <span class="text-xs text-red-600 font-medium"><i class="fas fa-store-slash ml-1"></i> مغلق</span>
                                    @endif
                                </td>

                                {{-- حالتك الخاصة (التي عدلها الأونر) --}}
                                <td class="p-4">
                                    @if(!$salonIsOpen)
                                        <span class="text-xs text-gray-400 italic">عطلة إجبارية</span>
                                    @elseif($isWorking)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <span class="w-2 h-2 ml-2 bg-green-500 rounded-full"></span>
                                            دوام
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <span class="w-2 h-2 ml-2 bg-red-500 rounded-full"></span>
                                            @if($staffDay && $staffDay->status == 'annual') إجازة سنوية 
                                            @elseif($staffDay && $staffDay->status == 'sick') إجازة مرضية
                                            @else إجازة @endif
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 font-mono text-gray-600 text-sm">
                                    @if($isWorking && $startTime)
                                        {{ date('g:i A', strtotime($startTime)) }}
                                    @else
                                        <span class="text-gray-300">—:—</span>
                                    @endif
                                </td>

                                <td class="p-4 font-mono text-gray-600 text-sm">
                                    @if($isWorking && $endTime)
                                        {{ date('g:i A', strtotime($endTime)) }}
                                    @else
                                        <span class="text-gray-300">—:—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- زر العودة --}}
        <div class="mt-8 text-center">
            <a href="{{ route('staff.dashboard') }}" class="inline-flex items-center px-8 py-3 rounded-xl text-white font-bold transition-transform hover:scale-105 active:scale-95 shadow-lg" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                <i class="fas fa-chevron-right ml-2"></i>
                العودة للوحة التحكم
            </a>
        </div>
    </div>
</div>
@endsection