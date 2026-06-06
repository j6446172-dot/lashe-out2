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
                            <input type="radio" name="location" value="salon" class="ml-3" required style="accent-color: #B08D57;">
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
                            <input type="radio" name="location" value="home" class="ml-3" required style="accent-color: #B08D57;">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-home text-2xl" style="color: #B08D57;"></i>
                                <div>
                                    <span class="font-bold" style="color: #2B1E1A;">في المنزل</span>
                                    <p class="text-xs mt-1" style="color: #7C8574;">خدمة توصيل للمنزل <span class="font-bold" style="color: #B08D57;">+10 د.أ</span></p>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    {{-- ========== الخريطة ========== --}}
                    <div id="homeAddressSection" class="mt-4 hidden">
                        <div class="rounded-2xl p-4" style="background: rgba(176, 141, 87, 0.08);">
                            <h3 class="font-bold text-right mb-3" style="color: #2B1E1A;">📍 اختاري موقعك على الخريطة</h3>
                            
                            {{-- 🔥 رسالة توضيحية إذا كان فيه عنوان محفوظ --}}
                            @if(auth()->user()->default_latitude && auth()->user()->default_longitude)
                                <div class="mb-3 p-3 rounded-xl text-right" style="background: rgba(16, 185, 129, 0.1); border-right: 3px solid #10b981;">
                                    <i class="fas fa-check-circle ml-1" style="color: #10b981;"></i>
                                    <span class="font-bold" style="color: #2B1E1A;">✅ عنوانك المحفوظ:</span>
                                    <p class="text-sm mt-1" style="color: #7C8574;">{{ auth()->user()->default_address ?? 'تم تحديد موقعك مسبقاً' }}</p>
                                    <p class="text-xs mt-2" style="color: #B08D57;">
                                        <i class="fas fa-map-marker-alt ml-1"></i>
                                        يمكنك المتابعة مباشرة أو سحب العلامة لتعديل الموقع
                                    </p>
                                </div>
                            @endif
                            
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
                            
                            <input type="hidden" name="latitude" id="latitude" value="{{ session('booking.latitude', auth()->user()->default_latitude) }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ session('booking.longitude', auth()->user()->default_longitude) }}">
                            <input type="hidden" name="address_text" id="address_text" value="{{ session('booking.address_text', auth()->user()->default_address) }}">
                            
                            <div class="grid grid-cols-2 gap-3 mt-3">
                                <div>
                                    <label class="block text-sm font-bold text-right mb-1" style="color: #2B1E1A;">رقم البناية (اختياري)</label>
                                    <input type="text" name="building_number" 
                                           value="{{ old('building_number', session('booking.building_number', auth()->user()->default_building_number)) }}"
                                           class="w-full p-2 rounded-lg border text-right"
                                           style="background: rgba(255,255,255,0.8);"
                                           placeholder="مثال: 15">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-right mb-1" style="color: #2B1E1A;">الطابق/الشقة (اختياري)</label>
                                    <input type="text" name="apartment" 
                                           value="{{ old('apartment', session('booking.apartment', auth()->user()->default_apartment)) }}"
                                           class="w-full p-2 rounded-lg border text-right"
                                           style="background: rgba(255,255,255,0.8);"
                                           placeholder="مثال: طابق 3، شقة 5">
                                </div>
                            </div>
                            
                            <input type="hidden" name="save_address" value="1">
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
    
    // إظهار الخريطة عند اختيار خدمة منزلية
    const locationRadios = document.querySelectorAll('input[name="location"]');
    const homeAddressSection = document.getElementById('homeAddressSection');
    
    locationRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'home') {
                homeAddressSection.classList.remove('hidden');
                homeAddressSection.classList.add('block');
                setTimeout(() => { if (typeof map !== 'undefined' && map) map.invalidateSize(); }, 100);
            } else {
                homeAddressSection.classList.add('hidden');
                homeAddressSection.classList.remove('block');
            }
        });
    });
    
    // ========== الخريطة ==========
    let map, marker;
    let isFirstLoad = true;
    
    function initMap() {
        // جلب القيم من Session أو من قاعدة البيانات
        let startLat = {{ session('booking.latitude') ?? auth()->user()->default_latitude ?? 31.9539 }};
        let startLng = {{ session('booking.longitude') ?? auth()->user()->default_longitude ?? 35.9106 }};
        let startAddress = "{{ addslashes(session('booking.address_text', auth()->user()->default_address ?? '')) }}";
        
        // التحقق من وجود عنوان محفوظ في قاعدة البيانات
        let hasSavedAddress = {{ auth()->user()->default_latitude ? 'true' : 'false' }};
        
        // إذا كان فيه عنوان محفوظ والمستخدم ما اختار عنوان جديد في هذه الجلسة، استخدم المحفوظ
        if (hasSavedAddress && (!startAddress || startAddress == '')) {
            startLat = {{ auth()->user()->default_latitude ?? 31.9539 }};
            startLng = {{ auth()->user()->default_longitude ?? 35.9106 }};
            startAddress = "{{ addslashes(auth()->user()->default_address ?? '') }}";
            
            // حفظ العنوان المحفوظ في Session عشان يضل موجود
            saveAddressToSession(startLat, startLng, startAddress);
        }
        
        // تأكد إن القيم أرقام صحيحة
        startLat = parseFloat(startLat) || 31.9539;
        startLng = parseFloat(startLng) || 35.9106;
        
        // إنشاء الخريطة
        map = L.map('map').setView([startLat, startLng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        marker = L.marker([startLat, startLng], { draggable: true }).addTo(map);
        
        marker.on('dragend', function() {
            const latLng = marker.getLatLng();
            updateLocation(latLng);
        });
        
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateLocation(e.latlng);
        });
        
        // تعبئة العنوان إذا كان موجود
        if (startAddress && startAddress != '') {
            document.getElementById('selected-address').innerHTML = `📍 ${startAddress}`;
            document.getElementById('address_text').value = startAddress;
            document.getElementById('latitude').value = startLat;
            document.getElementById('longitude').value = startLng;
            
            // إذا كان أول تحميل ومافيش Session، نخزن العنوان في Session
            if (hasSavedAddress && !{{ session('booking.address_text') ? 'true' : 'false' }}) {
                saveAddressToSession(startLat, startLng, startAddress);
            }
        } else {
            updateLocation({ lat: startLat, lng: startLng });
        }
        
        isFirstLoad = false;
    }
    
    // دالة لحفظ العنوان في Session عبر AJAX
    function saveAddressToSession(lat, lng, address) {
        fetch('{{ route("customer.save-address-session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                latitude: lat,
                longitude: lng,
                address_text: address
            })
        }).catch(error => console.error('Error saving address:', error));
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
                
                // حفظ العنوان في Session إذا كان التغيير من المستخدم وليس أول تحميل
                if (!isFirstLoad) {
                    saveAddressToSession(lat, lng, address);
                }
            })
            .catch(() => {
                const address = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                document.getElementById('selected-address').innerHTML = `📍 ${address}`;
                document.getElementById('address_text').value = address;
                
                if (!isFirstLoad) {
                    saveAddressToSession(lat, lng, address);
                }
            });
    }
    
    // ========== شريط البحث ==========
    const searchInput = document.getElementById('search-input');
    
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
                
                // حفظ العنوان في Session
                saveAddressToSession(lat, lon, result.display_name);
                
                searchInput.style.borderColor = '#10b981';
                setTimeout(() => { searchInput.style.borderColor = '#ddd'; }, 2000);
            } else {
                alert('⚠️ لم يتم العثور على هذا العنوان. حاولي كتابة اسم المنطقة بدقة.');
                searchInput.style.borderColor = '#dc2626';
                setTimeout(() => { searchInput.style.borderColor = '#ddd'; }, 2000);
            }
        } catch (error) {
            console.error('خطأ في البحث:', error);
            alert('حدث خطأ في البحث. حاولي مرة أخرى.');
        } finally {
            searchInput.style.opacity = '1';
            searchInput.placeholder = '🔍 ابحثي عن عنوان...';
        }
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchLocation();
            }
        });
    }
    
    // ========== التحقق من الموقع عند الإرسال ==========
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const locationRadio = document.querySelector('input[name="location"]:checked');
        const isHomeService = locationRadio && locationRadio.value === 'home';
        
        if (!isHomeService) return;
        
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        const hasDefault = {{ auth()->user()->default_latitude ? 'true' : 'false' }};
        const hasSessionAddress = "{{ session('booking.address_text') }}" != '';
        
        // ✅ إذا عنده عنوان محفوظ في قاعدة البيانات أو في Session، يسمح له بالاستمرار
        if (hasDefault || hasSessionAddress) {
            return;
        }
        
        // ❌ إذا ما عنده عنوان، يتأكد إنه اختار موقع
        if (!lat || !lng || lat == '31.9539' && lng == '35.9106') {
            e.preventDefault();
            alert('⚠️ الرجاء اختيار موقعك على الخريطة قبل المتابعة');
        }
    });
    
    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection