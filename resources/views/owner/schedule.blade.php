@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                
                {{-- Header --}}
                <div class="rounded-2xl p-6 text-center mb-6 shadow-md"
                     style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <h1 class="text-2xl font-bold" style="color: #F3EDE6;">🕐 أوقات الدوام</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">إدارة ساعات العمل وأيام العطل</p>
                </div>

                @if(session('success'))
                <div class="mb-4 p-3 rounded-xl text-center text-sm font-bold" style="background: rgba(16, 185, 129, 0.15); color: #065f46;">
                    ✅ {{ session('success') }}
                </div>
                @endif

                {{-- بطاقات --}}
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">🕐 ساعات العمل</p>
                        <p class="text-2xl font-black mt-2" style="color: #B08D57;">10:00 ص - 6:00 م</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">📅 أيام العمل</p>
                        <p class="text-2xl font-black mt-2" style="color: #10b981;">السبت - الخميس</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">👩‍💼 عدد الموظفات</p>
                        <p class="text-2xl font-black mt-2" style="color: #B08D57;">{{ count($staffMembers ?? []) }}</p>
                    </div>
                </div>

                {{-- زر العودة --}}
                <div class="mb-4">
                    <a href="{{ route('owner.dashboard') }}" class="px-5 py-2.5 rounded-xl font-bold transition" style="background: rgba(176, 141, 87, 0.1); color: #B08D57; text-decoration: none;">← العودة</a>
                </div>

                {{-- دوام الصالون --}}
                <div class="rounded-xl p-6 mb-6 shadow-sm" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <h3 class="text-lg font-bold mb-4" style="color: #2B1E1A;">🏠 دوام الصالون</h3>
                    
                    <form method="POST" action="{{ route('owner.schedule.updateSalon') }}">
                        @csrf
                        <table class="w-full text-right" style="border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid rgba(176, 141, 87, 0.2); color: #B08D57;">
                                    <th class="p-3">اليوم</th>
                                    <th class="p-3">الحالة</th>
                                    <th class="p-3">البداية</th>
                                    <th class="p-3">النهاية</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $days = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة']; @endphp
                                @foreach($days as $index => $day)
                                @php
                                    $salonData = \DB::table('salon_schedule')->where('day_of_week', $index)->first();
                                    $isOpen = $salonData ? $salonData->is_open : ($index != 6);
                                    $startVal = $salonData ? $salonData->start_time : '10:00';
                                    $endVal = $salonData ? $salonData->end_time : '18:00';
                                @endphp
                                <tr style="border-bottom: 1px solid rgba(176, 141, 87, 0.1);">
                                    <td class="p-3 font-bold" style="color: #2B1E1A;">{{ $day }}</td>
                                    <td class="p-3">
                                        <select name="status[{{ $index }}]" class="px-3 py-2 rounded-lg text-sm" style="border: 1px solid rgba(176, 141, 87, 0.3);">
                                            <option value="open" {{ $isOpen ? 'selected' : '' }}>🟢 دوام</option>
                                            <option value="closed" {{ !$isOpen ? 'selected' : '' }}>🔴 عطلة</option>
                                        </select>
                                    </td>
                                    <td class="p-3">
                                        <input type="time" name="start[{{ $index }}]" value="{{ $startVal }}" class="px-3 py-2 rounded-lg text-sm" style="border: 1px solid rgba(176, 141, 87, 0.3);">
                                    </td>
                                    <td class="p-3">
                                        <input type="time" name="end[{{ $index }}]" value="{{ $endVal }}" class="px-3 py-2 rounded-lg text-sm" style="border: 1px solid rgba(176, 141, 87, 0.3);">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-left mt-4">
                            <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white transition" style="background: #B08D57;">💾 حفظ دوام الصالون</button>
                        </div>
                    </form>
                </div>

                {{-- دوام الموظفات --}}
                <div class="rounded-xl p-6 shadow-sm" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <h3 class="text-lg font-bold mb-4" style="color: #2B1E1A;">👩‍💼 دوام الموظفات</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right" style="border-collapse: collapse; min-width: 600px;">
                            <thead>
                                <tr style="border-bottom: 2px solid rgba(176, 141, 87, 0.2); color: #B08D57;">
                                    <th class="p-3">اليوم</th>
                                    @foreach($staffMembers ?? [] as $staff)
                                    <th class="p-3">{{ $staff->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($days as $index => $day)
                                <tr style="border-bottom: 1px solid rgba(176, 141, 87, 0.1);">
                                    <td class="p-3 font-bold" style="color: #2B1E1A;">{{ $day }}</td>
                                    @foreach($staffMembers ?? [] as $staff)
                                    <td class="p-3 text-center cursor-pointer hover:bg-gray-50 rounded" 
                                        onclick="openEditModal('{{ $staff->id }}', '{{ $staff->name }}', '{{ $day }}', '{{ $index }}')">
                                        @php
                                            $schedule = \DB::table('staff_schedule')
                                                ->where('staff_id', $staff->id)
                                                ->where('day_of_week', $index)
                                                ->first();
                                        @endphp
                                        @if($schedule && $schedule->status != 'active')
                                            <span class="text-red-500 text-xs">
                                                @if($schedule->status == 'annual') 🔴 سنوية
                                                @elseif($schedule->status == 'sick') 🤒 مرضية
                                                @elseif($schedule->status == 'emergency') 🚨 طارئة
                                                @elseif($schedule->status == 'unpaid') 💸 بدون راتب
                                                @elseif($schedule->status == 'half_day') 🌓 نصف يوم
                                                @elseif($schedule->status == 'swap') 🔄 تبديل
                                                @elseif($schedule->status == 'dayoff') 🔴 عطلة
                                                @else 🔴 إجازة
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-green-600 text-xs">🟢 {{ $schedule->start_time ?? '10:00' }}-{{ $schedule->end_time ?? '18:00' }}</span>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- نافذة تعديل دوام الموظفة --}}
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-md mx-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);">
        <div class="p-4 text-right" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <h3 class="font-bold text-lg" style="color: #F3EDE6;">✏️ تعديل دوام الموظفة</h3>
        </div>
        <div class="p-6">
            <input type="hidden" id="editStaffId">
            <input type="hidden" id="editDay">
            <div class="mb-4 text-center">
                <p class="font-bold text-lg" style="color: #2B1E1A;" id="editInfo"></p>
            </div>
            
            <div class="mb-4">
                <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">نوع الإجازة / الدوام</label>
                <select id="editStatus" class="w-full px-4 py-3 rounded-xl text-right" style="border: 1px solid rgba(176, 141, 87, 0.3);">
                    <option value="active">🟢 دوام</option>
                    <option value="annual">🔴 إجازة سنوية</option>
                    <option value="sick">🤒 إجازة مرضية</option>
                    <option value="emergency">🚨 طارئة</option>
                    <option value="unpaid">💸 بدون راتب</option>
                    <option value="half_day">🌓 نصف يوم</option>
                    <option value="swap">🔄 تبديل وردية</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">وقت البداية</label>
                <select id="editStart" class="w-full px-4 py-3 rounded-xl text-right" style="border: 1px solid rgba(176, 141, 87, 0.3);">
                    @for($h = 8; $h <= 18; $h++)
                    <option value="{{ $h }}:00" {{ $h == 10 ? 'selected' : '' }}>{{ sprintf('%02d', $h) }}:00</option>
                    @endfor
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">وقت النهاية</label>
                <select id="editEnd" class="w-full px-4 py-3 rounded-xl text-right" style="border: 1px solid rgba(176, 141, 87, 0.3);">
                    @for($h = 12; $h <= 20; $h++)
                    <option value="{{ $h }}:00" {{ $h == 18 ? 'selected' : '' }}>{{ sprintf('%02d', $h) }}:00</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="p-4 flex gap-3 justify-center border-t" style="border-color: rgba(176, 141, 87, 0.1);">
            <button onclick="closeEditModal()" class="px-6 py-2 rounded-lg" style="background: rgba(124, 133, 116, 0.1); color: #7C8574;">إلغاء</button>
            <button onclick="saveStaffSchedule()" class="px-6 py-2 rounded-lg text-white font-bold" style="background: #B08D57;">💾 حفظ</button>
        </div>
    </div>
</div>

<script>
function openEditModal(staffId, staffName, day, dayIndex) {
    document.getElementById('editStaffId').value = staffId;
    document.getElementById('editDay').value = dayIndex;
    document.getElementById('editInfo').textContent = staffName + ' - يوم ' + day;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    
    fetch(`/owner/staff-schedule/${staffId}?day=${dayIndex}`)
        .then(r => r.json())
        .then(data => {
            if (data.status) document.getElementById('editStatus').value = data.status;
            if (data.start_time) document.getElementById('editStart').value = data.start_time;
            if (data.end_time) document.getElementById('editEnd').value = data.end_time;
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

function saveStaffSchedule() {
    const staffId = document.getElementById('editStaffId').value;
    const day = document.getElementById('editDay').value;
    const status = document.getElementById('editStatus').value;
    const start = document.getElementById('editStart').value;
    const end = document.getElementById('editEnd').value;
    
    fetch('/owner/staff-schedule/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ staff_id: staffId, day_of_week: day, status: status, start_time: start, end_time: end })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { location.reload(); }
        else { alert('خطأ في الحفظ'); }
    });
}

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endsection