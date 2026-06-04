@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- ========== Progress Bar ========== --}}
        <div class="mb-12">
            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #10b981;">
                        <i class="fas fa-check text-xl" style="color: white;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #10b981;">شكل العين</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #10b981;">
                        <i class="fas fa-check text-xl" style="color: white;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #10b981;">الخدمة</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #10b981;">
                        <i class="fas fa-check text-xl" style="color: white;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #10b981;">التاريخ</span>
                </div>
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-lg" style="background: #B08D57;">
                        <i class="fas fa-user-check text-xl" style="color: #F3EDE6;"></i>
                    </div>
                    <span class="text-sm font-bold" style="color: #B08D57;">التأكيد</span>
                </div>
            </div>
            <div class="mt-4 h-2 rounded-full overflow-hidden" style="background: rgba(176, 141, 87, 0.2);">
                <div class="h-full rounded-full" style="width: 100%; background: #B08D57;"></div>
            </div>
        </div>

        {{-- ========== Main Card ========== --}}
        <div class="rounded-3xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
            
            <div class="px-8 py-8" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                <h2 class="text-3xl font-black mb-2" style="color: #F3EDE6;">💁‍♀️ اختاري الموظفة المناسبة لكِ</h2>
                <p class="text-lg" style="color: rgba(243, 237, 230, 0.8);">جميع موظفاتنا خبيرات ومعتمدات بأعلى المعايير</p>
            </div>
            
            <form method="POST" action="{{ route('customer.bookings.step4.post') }}" class="p-8" id="bookingForm">
                @csrf
                
                {{-- ========== اختيار الموظفة ========== --}}
                <div class="mb-8">
                    <label class="block font-bold mb-4 text-right text-lg" style="color: #2B1E1A;">
                        <i class="fas fa-female ml-2" style="color: #B08D57;"></i> من تفضلين تقديم الخدمة؟
                    </label>
                    
                    @if($availableStaff->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($availableStaff as $staffMember)
                            <div class="staff-card rounded-2xl border-2 p-4 transition-all duration-300 cursor-pointer" 
                                 style="background: rgba(255, 255, 255, 0.8); border-color: rgba(176, 141, 87, 0.2);" 
                                 data-value="{{ $staffMember->id }}">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: rgba(176, 141, 87, 0.15);">
                                        <i class="fas fa-user-circle text-3xl" style="color: #B08D57;"></i>
                                    </div>
                                    <div class="flex-1 text-right">
                                        <h4 class="font-bold" style="color: #2B1E1A;">{{ $staffMember->name }}</h4>
                                        <p class="text-xs mt-1" style="color: #7C8574;">{{ $staffMember->experience ?? 'خبيرة تجميل معتمدة' }}</p>
                                        <span class="text-xs inline-block px-2 py-1 rounded-full mt-2" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                            ✅ متاحة الآن
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="staff_id" id="staff_id" required>
                    @else
                        <div class="text-center py-8 rounded-2xl" style="background: rgba(220, 38, 38, 0.1);">
                            <i class="fas fa-clock text-4xl mb-2" style="color: #dc2626;"></i>
                            <p class="font-bold" style="color: #dc2626;">⚠️ جميع الموظفات مشغولات في هذا الوقت</p>
                            <a href="{{ route('customer.bookings.step3') }}" class="inline-block mt-3 font-bold transition hover:opacity-70" style="color: #B08D57;">
                                <i class="fas fa-arrow-left ml-1"></i> اختيار وقت آخر
                            </a>
                        </div>
                    @endif
                </div>
                
                {{-- ========== اختيار المكان ========== --}}
                <div class="mb-8">
                    <label class="block font-bold mb-4 text-right text-lg" style="color: #2B1E1A;">
                        <i class="fas fa-map-marker-alt ml-2" style="color: #B08D57;"></i> أين تفضلين تلقي الخدمة؟
                    </label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="location-option border-2 rounded-xl p-4 cursor-pointer transition-all duration-300 hover:shadow-md" 
                               style="border-color: rgba(176, 141, 87, 0.2);">
                            <input type="radio" name="location" value="salon" class="ml-3" required style="accent-color: #B08D57;" {{ old('location', session('booking.location')) == 'salon' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-spa text-2xl" style="color: #B08D57;"></i>
                                <div>
                                    <span class="font-bold" style="color: #2B1E1A;">في الصالون</span>
                                    <p class="text-xs mt-1" style="color: #7C8574;">العبدلي - مجمع لاش أوت</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="location-option border-2 rounded-xl p-4 cursor-pointer transition-all duration-300 hover:shadow-md" 
                               style="border-color: rgba(176, 141, 87, 0.2);">
                            <input type="radio" name="location" value="home" class="ml-3" required style="accent-color: #B08D57;" {{ old('location', session('booking.location')) == 'home' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-home text-2xl" style="color: #B08D57;"></i>
                                <div>
                                    <span class="font-bold" style="color: #2B1E1A;">في المنزل</span>
                                    <p class="text-xs mt-1" style="color: #7C8574;">خدمة توصيل للمنزل <span class="font-bold" style="color: #B08D57;">+10 د.أ</span></p>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    {{-- ========== الخريطة (تظهر فقط عند اختيار خدمة منزلية والعميل ما عنده عنوان محفوظ) ========== --}}
                    @php
                        $hasSavedAddress = auth()->user()->default_latitude && auth()->user()->default_longitude && auth()->user()->default_address;
                    @endphp
                    
                    <div id="homeAddressSection" class="mt-4 {{ (old('location', session('booking.location')) == 'home' && !$hasSavedAddress) ? '' : 'hidden' }}">
                        <div class="rounded-2xl p-4" style="background: rgba(176, 141, 87, 0.08);">
                            
                            @if($hasSavedAddress)
                                {{-- العميل عنده عنوان محفوظ → يظهر له العنوان فقط بدون خريطة --}}
                                <div class="bg-white p-4 rounded-xl text-right mb-3">
                                    <p class="text-sm font-bold" style="color: #2B1E1A;">📍 عنوانك المحفوظ:</p>
                                    <p class="text-md mt-1" style="color: #2B1E1A;">{{ auth()->user()->default_address }}</p>
                                    @if(auth()->user()->default_building_number || auth()->user()->default_apartment)
                                        <p class="text-sm mt-1" style="color: #7C8574;">
                                            @if(auth()->user()->default_building_number) بناية {{ auth()->user()->default_building_number }} @endif
                                            @if(auth()->user()->default_apartment) - {{ auth()->user()->default_apartment }} @endif
                                        </p>
                                    @endif
                                    <p class="text-xs mt-2" style="color: #10b981;">✅ سيتم استخدام هذا العنوان تلقائياً</p>
                                </div>
                                
                                {{-- حقول مخفية بالقيم المحفوظة --}}
                                <input type="hidden" name="latitude" value="{{ auth()->user()->default_latitude }}">
                                <input type="hidden" name="longitude" value="{{ auth()->user()->default_longitude }}">
                                <input type="hidden" name="address_text" value="{{ auth()->user()->default_address }}">
                                <input type="hidden" name="building_number" value="{{ auth()->user()->default_building_number }}">
                                <input type="hidden" name="apartment" value="{{ auth()->user()->default_apartment }}">
                                <input type="hidden" name="save_address" value="1">
                                
                            @else
                                {{-- عميل جديد → يظهر الخريطة كاملة --}}
                                <h3 class="font-bold text-right mb-3" style="color: #2B1E1A;">📍 اختاري موقعك على الخريطة</h3>
                                
                                <div style="position: relative;">
                                    <div id="map" style="height: 350px; width: 100%; border-radius: 16px; margin-bottom: 12px;"></div>
                                    <input type="text" id="search-input" 
                                           class="search-control" 
                                           placeholder="🔍 ابحثي عن عنوان...">
                                </div>
                                
                                <div class="bg-white p-3 rounded-xl text-right mb-3">
                                    <p class="text-sm font-bold" style="color: #2B1E1A;">📍 العنوان المختار:</p>
                                    <p id="selected-address" class="text-sm mt-1" style="color: #7C8574;">اضغطي على الخريطة لتحديد موقعك</p>
                                </div>
                                
                                <input type="hidden" name="latitude" id="latitude" value="">
                                <input type="hidden" name="longitude" id="longitude" value="">
                                <input type="hidden" name="address_text" id="address_text" value="">
                                
                                <div class="grid grid-cols-2 gap-3 mt-3">
                                    <div>
                                        <label class="block text-sm font-bold text-right mb-1" style="color: #2B1E1A;">رقم البناية (اختياري)</label>
                                        <input type="text" name="building_number" 
                                               class="w-full p-2 rounded-lg border text-right"
                                               style="background: rgba(255,255,255,0.8);"
                                               placeholder="">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-right mb-1" style="color: #2B1E1A;">الطابق/الشقة (اختياري)</label>
                                        <input type="text" name="apartment" 
                                               class="w-full p-2 rounded-lg border text-right"
                                               style="background: rgba(255,255,255,0.8);"
                                               placeholder="">
                                    </div>
                                </div>
                                
                                <input type="hidden" name="save_address" value="1">
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- ========== ملخص حجزك ========== --}}
                <div class="rounded-2xl p-4 mb-6" style="background: rgba(176, 141, 87, 0.08);">
                    <h4 class="font-bold text-right mb-3" style="color: #2B1E1A;">📋 ملخص حجزك:</h4>
                    <div class="grid grid-cols-2 gap-3 text-right text-sm">
                        <div><span style="color: #7C8574;">الخدمة:</span> 
                            <span class="font-bold" style="color: #2B1E1A;">
                                @php
                                    $serviceNames = [
                                        'classic' => 'Classic Set',
                                        'wet' => 'Wet Set',
                                        'wispy' => 'Wispy Set',
                                        'volume' => 'Volume Set',
                                        'anime' => 'Anime Set'
                                    ];
                                @endphp
                                {{ $serviceNames[session('booking.service_type')] ?? session('booking.service_type') }}
                            </span>
                        </div>
                        <div><span style="color: #7C8574;">التاريخ:</span> <span class="font-bold" style="color: #2B1E1A;">{{ session('booking.booking_date', 'غير محدد') }}</span></div>
                        <div><span style="color: #7C8574;">الوقت:</span> <span class="font-bold" style="color: #2B1E1A;">{{ session('booking.booking_time', 'غير محدد') }}</span></div>
                        <div><span style="color: #7C8574;">شكل العين:</span> <span class="font-bold" style="color: #2B1E1A;">
                            @php
                                $eyeShapes = [
                                    'almond' => 'لوزية', 'round' => 'دائرية', 'hooded' => 'مبطنة',
                                    'downturned' => 'ناعسة', 'close-set' => 'متقاربة', 'wide-set' => 'متباعدة'
                                ];
                            @endphp
                            {{ $eyeShapes[session('booking.eye_shape')] ?? session('booking.eye_shape') }}
                        </span></div>
                    </div>
                </div>
                
                {{-- ========== الأزرار ========== --}}
                @if($availableStaff->count() > 0)
                <div class="flex justify-between gap-4">
                    <a href="{{ route('customer.bookings.step3') }}" class="px-8 py-3 rounded-xl font-bold transition-all duration-300 text-center" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                        <i class="fas fa-arrow-right ml-2"></i> السابق
                    </a>
                    <button type="submit" class="flex-1 font-bold py-3 rounded-xl transition-all duration-300 transform hover:scale-[1.02]" style="background: #B08D57; color: #F3EDE6;" id="submitBtn">
                        عرض الحجز والتأكيد <i class="fas fa-arrow-left mr-2"></i>
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- مكتبات الخريطة --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .search-control {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
        background: white;
        padding: 10px 15px;
        border-radius: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        width: 250px;
        border: 1px solid #ddd;
        font-size: 14px;
        text-align: right;
        direction: rtl;
    }
    .search-control:focus {
        outline: none;
        border-color: #B08D57;
        box-shadow: 0 0 0 2px rgba(176,141,87,0.2);
    }
    @media (max-width: 768px) {
        .search-control {
            width: 180px;
            top: 5px;
            right: 5px;
            font-size: 12px;
            padding: 6px 10px;
        }
    }
</style>

<script>
    // اختيار الموظفة
    document.querySelectorAll('.staff-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.staff-card').forEach(c => {
                c.classList.remove('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
                c.style.background = 'rgba(255,255,255,0.8)';
            });
            this.classList.add('border-[#B08D57]', 'ring-2', 'ring-[#B08D57]/30');
            this.style.background = 'rgba(176,141,87,0.08)';
            document.getElementById('staff_id').value = this.dataset.value;
        });
    });
    
    // إظهار/إخفاء الخريطة عند اختيار خدمة منزلية (فقط للعملاء الجدد)
    const locationRadios = document.querySelectorAll('input[name="location"]');
    const homeAddressSection = document.getElementById('homeAddressSection');
    const hasSavedAddress = {{ $hasSavedAddress ? 'true' : 'false' }};
    
    locationRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'home') {
                homeAddressSection.classList.remove('hidden');
                homeAddressSection.classList.add('block');
                // فقط إذا ما في عنوان محفوظ وكانت الخريطة موجودة
                if (!hasSavedAddress && typeof map !== 'undefined' && map) {
                    setTimeout(() => { map.invalidateSize(); }, 100);
                }
            } else {
                homeAddressSection.classList.add('hidden');
                homeAddressSection.classList.remove('block');
            }
        });
    });
    
    // ========== الخريطة (فقط للعملاء الجدد) ==========
    @if(!$hasSavedAddress)
    const defaultLat = 31.9539;
    const defaultLng = 35.9106;
    
    let map, marker;
    
    function initMap() {
        map = L.map('map').setView([defaultLat, defaultLng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        
        marker.on('dragend', function() {
            const latLng = marker.getLatLng();
            updateLocation(latLng);
        });
        
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateLocation(e.latlng);
        });
        
        updateLocation({ lat: defaultLat, lng: defaultLng });
    }
    
    function updateLocation(latLng) {
        const lat = latLng.lat;
        const lng = latLng.lng;
        
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ar`)
            .then(response => response.json())
            .then(data => {
                const address = data.display_name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                document.getElementById('selected-address').innerHTML = `📍 ${address}`;
                document.getElementById('address_text').value = address;
            })
            .catch(() => {
                document.getElementById('selected-address').innerHTML = `📍 ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                document.getElementById('address_text').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            });
    }
    
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        async function searchLocation() {
            const query = searchInput.value.trim();
            if (!query) return;
            
            searchInput.style.opacity = '0.5';
            searchInput.placeholder = 'جاري البحث...';
            
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1&accept-language=ar`);
                const data = await response.json();
                
                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    
                    map.setView([lat, lon], 15);
                    marker.setLatLng([lat, lon]);
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;
                    document.getElementById('selected-address').innerHTML = `📍 ${result.display_name}`;
                    document.getElementById('address_text').value = result.display_name;
                } else {
                    alert('⚠️ لم يتم العثور على هذا العنوان');
                }
            } catch (error) {
                alert('حدث خطأ في البحث');
            } finally {
                searchInput.style.opacity = '1';
                searchInput.placeholder = '🔍 ابحثي عن عنوان...';
            }
        }
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchLocation();
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', initMap);
    @endif
    
    // منع العميل الجديد من الإرسال بدون اختيار موقع (فقط إذا الخريطة موجودة)
    @if(!$hasSavedAddress)
    const latField = document.getElementById('latitude');
    const lngField = document.getElementById('longitude');
    
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        if ((!latField.value || latField.value == '') || (!lngField.value || lngField.value == '')) {
            e.preventDefault();
            alert('⚠️ الرجاء اختيار موقعك على الخريطة قبل المتابعة (اضغطي على الخريطة أو ابحثي عن عنوانك)');
        }
    });
    @endif
</script>
@endsection