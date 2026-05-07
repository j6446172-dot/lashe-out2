@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                
                {{-- Header --}}
                <div class="rounded-2xl p-6 text-center mb-6 shadow-md"
                     style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <h1 class="text-2xl font-bold" style="color: #F3EDE6;">📋 حجوزاتي</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">عرض وإدارة جميع حجوزاتك</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl text-right"
                         style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border-right: 3px solid #B08D57;">
                        <i class="fas fa-check-circle ml-2" style="color: #B08D57;"></i>
                        <span style="color: #2B1E1A;">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl text-right"
                         style="background: rgba(254, 226, 226, 0.8); backdrop-filter: blur(8px); border-right: 3px solid #dc2626;">
                        <i class="fas fa-exclamation-circle ml-2" style="color: #dc2626;"></i>
                        <span style="color: #991b1b;">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Tabs --}}
                <div class="flex gap-2 mb-6 border-b" style="border-color: rgba(176, 141, 87, 0.2);">
                    <button class="tab-btn active px-5 py-2 font-bold transition" data-tab="active"
                            style="color: #B08D57; border-bottom: 2px solid #B08D57;">
                        <i class="fas fa-clock ml-1"></i> الحجوزات النشطة
                    </button>
                    <button class="tab-btn px-5 py-2 font-bold transition" data-tab="past"
                            style="color: #7C8574;">
                        <i class="fas fa-history ml-1"></i> الحجوزات السابقة
                    </button>
                    <button class="tab-btn px-5 py-2 font-bold transition" data-tab="cancelled"
                            style="color: #7C8574;">
                        <i class="fas fa-ban ml-1"></i> الحجوزات الملغاة
                    </button>
                </div>

                {{-- ===== الحجوزات النشطة ===== --}}
                <div id="tab-active" class="tab-content active">
                    @php
                        $activeBookings = auth()->user()->bookings()
                            ->where('status', 'confirmed')
                            ->where('booking_date', '>=', today())
                            ->orderBy('booking_date', 'asc')
                            ->orderBy('booking_time', 'asc')
                            ->get();
                    @endphp

                    @if($activeBookings->count() > 0)
                        @foreach($activeBookings as $booking)
                            @php
                                $daysLeft = \Carbon\Carbon::parse($booking->booking_date)->diffInDays(today());
                                if ($daysLeft == 0) {
                                    $dayText = '🔥 اليوم';
                                    $dayClass = 'days-today';
                                } elseif ($daysLeft == 1) {
                                    $dayText = '📅 غداً';
                                    $dayClass = 'days-tomorrow';
                                } else {
                                    $dayText = "📅 بعد {$daysLeft} أيام";
                                    $dayClass = 'days-upcoming';
                                }
                                
                                $serviceName = [
                                    'classic' => 'كلاسيك',
                                    'hybrid' => 'هايبرد',
                                    'volume' => 'فولوم',
                                    'removal' => 'إزالة رموش'
                                ][$booking->service_type] ?? $booking->service_type;
                                
                                $serviceIcon = $booking->service_type == 'classic' ? 'feather' : 
                                              ($booking->service_type == 'hybrid' ? 'magic' : 
                                              ($booking->service_type == 'volume' ? 'layer-group' : 'brush'));
                            @endphp
                            <div class="rounded-xl p-4 mb-4 transition-all duration-300 hover:shadow-md"
                                 style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                                
                                <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                                    <div class="px-3 py-1 rounded-full text-xs font-bold"
                                         style="background: rgba(16, 185, 129, 0.15); color: #065f46;">
                                        <i class="fas fa-check-circle ml-1"></i> مؤكد
                                    </div>
                                    <div class="px-3 py-1 rounded-full text-xs font-bold {{ $dayClass }}">
                                        {{ $dayText }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                             style="background: rgba(176, 141, 87, 0.1);">
                                            <i class="fas fa-{{ $serviceIcon }} text-xl" style="color: #B08D57;"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-lg" style="color: #2B1E1A;">{{ $serviceName }}</p>
                                            @if($booking->service_type != 'removal')
                                                <p class="text-sm" style="color: #7C8574;">{{ $booking->staff->name ?? 'غير محدد' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-base font-medium" style="color: #2B1E1A;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</p>
                                        <p class="text-sm" style="color: #7C8574;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-3 mt-4 pt-3 border-t" style="border-color: rgba(176, 141, 87, 0.1);">
                                    <a href="{{ route('customer.bookings.show', $booking->id) }}" 
                                       class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                       style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                                        <i class="fas fa-eye ml-1"></i> عرض
                                    </a>
                                    <button type="button" 
                                            class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                            style="background: rgba(220, 38, 38, 0.1); color: #dc2626;"
                                            onclick="openCancelModal({{ $booking->id }}, '{{ $serviceName }}', '{{ $booking->booking_date }}', '{{ $booking->booking_time }}')">
                                        <i class="fas fa-trash-alt ml-1"></i> إلغاء
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="rounded-xl p-10 text-center"
                             style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                            <i class="fas fa-calendar-week text-5xl mb-3" style="color: rgba(176, 141, 87, 0.3);"></i>
                            <p style="color: #7C8574;">لا توجد حجوزات نشطة</p>
                            <a href="{{ route('customer.bookings.step1') }}" 
                               class="inline-block mt-4 px-6 py-2 rounded-xl font-bold transition"
                               style="background: #B08D57; color: #F3EDE6;">
                                <i class="fas fa-plus ml-1"></i> حجز جديد
                            </a>
                        </div>
                    @endif
                </div>

                {{-- ===== الحجوزات السابقة ===== --}}
                <div id="tab-past" class="tab-content hidden">
                    @php
                        $pastBookings = auth()->user()->bookings()
                            ->where('status', 'confirmed')
                            ->where('booking_date', '<', today())
                            ->orderBy('booking_date', 'desc')
                            ->get();
                    @endphp

                    @if($pastBookings->count() > 0)
                        @foreach($pastBookings as $booking)
                            @php
                                $serviceName = [
                                    'classic' => 'كلاسيك',
                                    'hybrid' => 'هايبرد',
                                    'volume' => 'فولوم',
                                    'removal' => 'إزالة رموش'
                                ][$booking->service_type] ?? $booking->service_type;
                                $daysPassed = \Carbon\Carbon::parse($booking->booking_date)->diffInDays(today());
                            @endphp
                            <div class="rounded-xl p-4 mb-4"
                                 style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                                
                                <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(176, 141, 87, 0.1);">
                                    <div class="px-3 py-1 rounded-full text-xs font-bold"
                                         style="background: rgba(59, 130, 246, 0.1); color: #1e40af;">
                                        <i class="fas fa-check-double ml-1"></i> مكتمل
                                    </div>
                                    <div class="text-xs" style="color: #7C8574;">منذ {{ $daysPassed }} يوم</div>
                                </div>
                                
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                             style="background: rgba(124, 133, 116, 0.1);">
                                            <i class="fas fa-check text-xl" style="color: #7C8574;"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold" style="color: #2B1E1A;">{{ $serviceName }}</p>
                                            @if($booking->service_type != 'removal')
                                                <p class="text-sm" style="color: #7C8574;">{{ $booking->staff->name ?? 'غير محدد' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <p style="color: #2B1E1A;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</p>
                                        <p class="text-sm" style="color: #7C8574;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-3 pt-3 border-t" style="border-color: rgba(176, 141, 87, 0.1);">
                                    <a href="{{ route('customer.bookings.show', $booking->id) }}" 
                                       class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                       style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                                        <i class="fas fa-eye ml-1"></i> عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="rounded-xl p-10 text-center"
                             style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                            <i class="fas fa-history text-5xl mb-3" style="color: rgba(176, 141, 87, 0.3);"></i>
                            <p style="color: #7C8574;">لا توجد حجوزات سابقة</p>
                        </div>
                    @endif
                </div>

                {{-- ===== الحجوزات الملغاة ===== --}}
                <div id="tab-cancelled" class="tab-content hidden">
                    @php
                        $cancelledBookings = auth()->user()->bookings()
                            ->where('status', 'cancelled')
                            ->orderBy('booking_date', 'desc')
                            ->get();
                    @endphp

                    @if($cancelledBookings->count() > 0)
                        @foreach($cancelledBookings as $booking)
                            @php
                                $serviceName = [
                                    'classic' => 'كلاسيك',
                                    'hybrid' => 'هايبرد',
                                    'volume' => 'فولوم',
                                    'removal' => 'إزالة رموش'
                                ][$booking->service_type] ?? $booking->service_type;
                            @endphp
                            <div class="rounded-xl p-4 mb-4 opacity-80"
                                 style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(220, 38, 38, 0.2);">
                                
                                <div class="flex justify-between items-center pb-3 border-b" style="border-color: rgba(220, 38, 38, 0.1);">
                                    <div class="px-3 py-1 rounded-full text-xs font-bold"
                                         style="background: rgba(220, 38, 38, 0.1); color: #991b1b;">
                                        <i class="fas fa-ban ml-1"></i> ملغي
                                    </div>
                                    <div class="text-xs" style="color: #7C8574;">تم الإلغاء</div>
                                </div>
                                
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                             style="background: rgba(220, 38, 38, 0.1);">
                                            <i class="fas fa-trash-alt text-xl" style="color: #dc2626;"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold line-through" style="color: #7C8574;">{{ $serviceName }}</p>
                                            @if($booking->service_type != 'removal')
                                                <p class="text-sm" style="color: #B9ADA3;">{{ $booking->staff->name ?? 'غير محدد' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <p class="line-through" style="color: #7C8574;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</p>
                                        <p class="text-sm" style="color: #B9ADA3;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-3 pt-3 border-t" style="border-color: rgba(220, 38, 38, 0.1);">
                                    <a href="{{ route('customer.bookings.show', $booking->id) }}" 
                                       class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                       style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                                        <i class="fas fa-eye ml-1"></i> عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="rounded-xl p-10 text-center"
                             style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                            <i class="fas fa-ban text-5xl mb-3" style="color: rgba(176, 141, 87, 0.3);"></i>
                            <p style="color: #7C8574;">لا توجد حجوزات ملغاة</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- مودال الإلغاء --}}
<div id="cancelModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-md mx-4 overflow-hidden"
         style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);">
        
        <div class="p-4 text-right" style="background: #dc2626;">
            <h3 class="font-bold text-lg text-white"><i class="fas fa-exclamation-triangle ml-2"></i> تأكيد الإلغاء</h3>
        </div>
        
        <div class="p-6 text-center">
            <i class="fas fa-calendar-times text-5xl mb-4" style="color: #dc2626;"></i>
            <p class="text-lg mb-2" style="color: #2B1E1A;" id="cancelServiceName"></p>
            <p class="text-sm" style="color: #7C8574;" id="cancelDateTime"></p>
            <p class="text-sm mt-4" style="color: #dc2626;">⚠️ لا يمكن استرجاع الحجز بعد الإلغاء</p>
        </div>
        
        <div class="p-4 flex gap-3 justify-center border-t" style="border-color: rgba(176, 141, 87, 0.1);">
            <button onclick="closeCancelModal()" 
                    class="px-6 py-2 rounded-lg transition"
                    style="background: rgba(124, 133, 116, 0.1); color: #7C8574;">
                تراجع
            </button>
            <form id="cancelForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-6 py-2 rounded-lg transition"
                        style="background: #dc2626; color: white;">
                    نعم، إلغاء الحجز
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openCancelModal(id, serviceName, date, time) {
        document.getElementById('cancelServiceName').innerHTML = `هل أنت متأكدة من إلغاء حجز <strong>${serviceName}</strong>؟`;
        document.getElementById('cancelDateTime').innerHTML = `${date} - ${time}`;
        document.getElementById('cancelForm').action = `/customer/bookings/${id}`;
        document.getElementById('cancelModal').classList.remove('hidden');
        document.getElementById('cancelModal').classList.add('flex');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.getElementById('cancelModal').classList.remove('flex');
    }

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active');
                b.style.color = '#7C8574';
                b.style.borderBottom = 'none';
            });
            this.classList.add('active');
            this.style.color = '#B08D57';
            this.style.borderBottom = '2px solid #B08D57';
            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`tab-${tabId}`).classList.remove('hidden');
        });
    });

    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) closeCancelModal();
    });
</script>

<style>
    .tab-content.hidden {
        display: none;
    }
    .line-through {
        text-decoration: line-through;
    }
    .days-today {
        background: rgba(176, 141, 87, 0.15);
        color: #9a7848;
    }
    .days-tomorrow {
        background: rgba(214, 195, 173, 0.2);
        color: #B08D57;
    }
    .days-upcoming {
        background: rgba(124, 133, 116, 0.1);
        color: #7C8574;
    }
</style>
@endsection