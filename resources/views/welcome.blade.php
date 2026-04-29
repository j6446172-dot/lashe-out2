<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lashe Out ✨ | Luxury Lash Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap');
        * { font-family: 'Tajawal', sans-serif; scroll-behavior: smooth; }
        
        :root { 
            --luxury-cream: #F5F2EE;
            --luxury-beige: #E8DCD0;
            --luxury-brown: #C8A27A;
            --luxury-dark: #8B6B4A;
            --luxury-shadow: #D6C3AE;
        }
        
        body { 
            background: linear-gradient(135deg, #F5F2EE, #E8DCD0);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(200, 162, 122, 0.2);
        }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(200, 162, 122, 0.2);
        }
        
        .service-card {
            transition: all 0.4s ease;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(200, 162, 122, 0.15);
        }
        
        .service-card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.9);
            border-color: var(--luxury-brown);
            box-shadow: 0 20px 35px -10px rgba(139, 107, 74, 0.2);
        }
        
        .btn-luxury {
            background: var(--luxury-brown);
            transition: all 0.3s ease;
        }
        
        .btn-luxury:hover {
            background: var(--luxury-dark);
            transform: scale(1.02);
        }
        
        .blob-1 {
            position: fixed;
            width: 500px;
            height: 500px;
            background: #E8DCD0;
            border-radius: 50%;
            top: -200px;
            left: -200px;
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
        }
        
        .blob-2 {
            position: fixed;
            width: 600px;
            height: 600px;
            background: #D6C3AE;
            border-radius: 50%;
            bottom: -250px;
            right: -200px;
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
        }
        
        .blob-3 {
            position: fixed;
            width: 300px;
            height: 300px;
            background: #C8A27A;
            border-radius: 50%;
            top: 50%;
            right: 20%;
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
        }
        
        .content-wrapper {
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>

    <div class="blob-1"></div>
    <div class="blob-2"></div>
    <div class="blob-3"></div>

    <div class="content-wrapper">
        
        <nav class="glass-nav sticky top-0 z-[100] py-4 shadow-sm">
            <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
                <a href="/" class="text-3xl font-black tracking-tighter" style="color: var(--luxury-dark);">LASHE OUT</a>
                <div class="hidden md:flex gap-8 items-center">
                    <a href="#services" class="font-bold transition hover:scale-105" style="color: var(--luxury-dark);">الخدمات</a>
                    <a href="#employees" class="font-bold transition hover:scale-105" style="color: var(--luxury-dark);">الخبيرات</a>
                    <a href="#about" class="font-bold transition hover:scale-105" style="color: var(--luxury-dark);">من نحن</a>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('customer.dashboard') }}" class="btn-luxury text-white px-6 py-2 rounded-full font-bold shadow-md">لوحة التحكم</a>
                        @elseif(auth()->user()->role === 'staff')
                            <a href="{{ route('staff.dashboard') }}" class="btn-luxury text-white px-6 py-2 rounded-full font-bold">لوحة التحكم</a>
                        @elseif(auth()->user()->role === 'owner')
                            <a href="{{ route('owner.dashboard') }}" class="btn-luxury text-white px-6 py-2 rounded-full font-bold">لوحة التحكم</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="font-bold transition hover:opacity-70" style="color: var(--luxury-dark);">تسجيل خروج</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="font-bold transition hover:opacity-70" style="color: var(--luxury-dark);">دخول</a>
                        <a href="{{ route('register') }}" class="btn-luxury text-white px-8 py-2.5 rounded-full font-bold shadow-md shadow-[#C8A27A]/30">ابدئي الآن</a>
                    @endauth
                </div>
            </div>
        </nav>

        <header class="py-20">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                <div class="text-right">
                    <span class="font-bold tracking-widest uppercase text-sm mb-4 block" style="color: var(--luxury-dark);">Professional Lash Studio</span>
                    <h1 class="text-6xl font-black leading-tight mb-6" style="color: var(--luxury-dark);">
                        جمالك يبدأ <br> 
                        <span style="color: var(--luxury-brown);">من عينيكِ</span>
                    </h1>
                    <p class="text-gray-600 text-xl leading-relaxed mb-10">نقدم لكِ أفضل أنواع الرموش العالمية بأيدي خبيرات متخصصات في الأردن.</p>
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('customer.bookings.step1') }}" class="btn-luxury text-white px-12 py-4 rounded-2xl font-bold text-lg inline-block shadow-xl shadow-[#C8A27A]/30">
                                احجزي موعدك الآن ✨
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn-luxury text-white px-12 py-4 rounded-2xl font-bold text-lg inline-block shadow-xl shadow-[#C8A27A]/30">
                                اذهبي إلى لوحة التحكم
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn-luxury text-white px-12 py-4 rounded-2xl font-bold text-lg inline-block shadow-xl shadow-[#C8A27A]/30">
                            سجلي الآن وابدأي
                        </a>
                    @endauth
                </div>
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#C8A27A]/20 to-transparent rounded-[3rem]"></div>
                    <img src="{{ asset('images/lash.jpeg') }}" class="w-full h-[500px] object-cover rounded-[3rem] shadow-2xl border-4 border-white/50">
                </div>
            </div>
        </header>

        <section id="services" class="py-24">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-black mb-4" style="color: var(--luxury-dark);">خدماتنا المميزة</h2>
                    <div class="w-20 h-1.5 mx-auto rounded-full" style="background: var(--luxury-brown);"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-right">
                    @php
                    $services = [
                        ['name' => 'Classic Set', 'price' => '30', 'img' => 'classic.jpeg', 'desc' => 'تركيب رمش واحد طبيعي لكل رمش أصلي لمظهر يومي هادئ.'],
                        ['name' => 'Volume Set', 'price' => '45', 'img' => 'volume.jpeg', 'desc' => 'كثافة عالية وسواد فاحم، مثالي للمناسبات والحفلات.'],
                        ['name' => 'Wispy Set', 'price' => '50', 'img' => 'wispy.jpeg', 'desc' => 'تصميم ريشي متدرج يعطي العين مظهراً واسعاً وجذاباً.'],
                    ];
                    @endphp

                    @foreach($services as $s)
                    <div class="service-card rounded-[2rem] p-5 flex flex-col">
                        <img src="{{ asset('images/'.$s['img']) }}" class="w-full h-56 object-cover rounded-[1.5rem] mb-6 shadow-md" alt="{{ $s['name'] }}">
                        <h3 class="text-2xl font-bold mb-2" style="color: var(--luxury-dark);">{{ $s['name'] }}</h3>
                        <p class="text-gray-500 mb-6 flex-grow">{{ $s['desc'] }}</p>
                        <div class="flex justify-between items-center border-t pt-4" style="border-color: rgba(200, 162, 122, 0.2);">
                            <span class="text-xl font-black" style="color: var(--luxury-brown);">{{ $s['price'] }} د.أ</span>
                            @auth
                                @if(auth()->user()->role === 'customer')
                                    <a href="{{ route('customer.bookings.step1') }}" class="btn-luxury text-white px-4 py-2 rounded-xl text-sm font-bold transition">حجز</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn-luxury text-white px-4 py-2 rounded-xl text-sm font-bold transition">سجلي أولاً</a>
                                @endif
                            @else
                                <a href="{{ route('register') }}" class="btn-luxury text-white px-4 py-2 rounded-xl text-sm font-bold transition">سجلي أولاً</a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="employees" class="py-24">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-black mb-4" style="color: var(--luxury-dark);">خبيراتنا المحترفات</h2>
                    <p class="font-bold" style="color: var(--luxury-brown);">جميعهن يقدمن كافة الخدمات المذكورة أعلاه بأعلى معايير الدقة</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="glass-card p-8 rounded-[3rem] text-center shadow-sm">
                        <img src="{{ asset('images/lina.jpeg') }}" class="w-48 h-48 mx-auto rounded-full object-cover border-4 shadow-md" style="border-color: var(--luxury-brown);" alt="لينا">
                        <h4 class="text-2xl font-bold mb-2 mt-6" style="color: var(--luxury-dark);">لينا العتوم</h4>
                        <p class="text-gray-500 mb-6 text-sm">خبرة 5 سنوات في فن الرموش، متقنة لجميع التقنيات العالمية.</p>
                        <a href="https://instagram.com" class="transition hover:scale-110 inline-block" style="color: var(--luxury-brown);"><i class="fab fa-instagram text-3xl"></i></a>
                    </div>
                    <div class="glass-card p-8 rounded-[3rem] text-center shadow-sm">
                        <img src="{{ asset('images/haya.jpeg') }}" class="w-48 h-48 mx-auto rounded-full object-cover border-4 shadow-md" style="border-color: var(--luxury-brown);" alt="هيا">
                        <h4 class="text-2xl font-bold mb-2 mt-6" style="color: var(--luxury-dark);">هيا الكردي</h4>
                        <p class="text-gray-500 mb-6 text-sm">متخصصة في رسم العين وتنسيق الرموش بما يناسب شكل الوجه.</p>
                        <a href="https://instagram.com" class="transition hover:scale-110 inline-block" style="color: var(--luxury-brown);"><i class="fab fa-instagram text-3xl"></i></a>
                    </div>
                    <div class="glass-card p-8 rounded-[3rem] text-center shadow-sm">
                        <img src="{{ asset('images/nada.jpeg') }}" class="w-48 h-48 mx-auto rounded-full object-cover border-4 shadow-md" style="border-color: var(--luxury-brown);" alt="ندى">
                        <h4 class="text-2xl font-bold mb-2 mt-6" style="color: var(--luxury-dark);">ندى جابر</h4>
                        <p class="text-gray-500 mb-6 text-sm">مدربة معتمدة في تقنيات الويسبـي والأنمي ومبدعة في كافة الموديلات.</p>
                        <a href="https://instagram.com" class="transition hover:scale-110 inline-block" style="color: var(--luxury-brown);"><i class="fab fa-instagram text-3xl"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-16 items-center">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full -z-10 opacity-50" style="background: var(--luxury-shadow);"></div>
                        <img src="{{ asset('images/about.jpeg') }}" class="rounded-[3rem] shadow-2xl border-4 border-white/50 w-full h-[450px] object-cover">
                    </div>
                    <div class="text-right order-1 md:order-2">
                        <h2 class="text-4xl font-black mb-8" style="color: var(--luxury-dark);">لماذا اختيار استوديو Lashe Out؟</h2>
                        <p class="text-gray-600 text-lg leading-relaxed mb-8">
                            نحن لسنا مجرد مركز تجميل، نحن وجهة لكل امرأة تبحث عن الثقة والتميز. بدأنا في عمان كأول استوديو متخصص حصرياً في تركيب الرموش بأحدث الصيحات العالمية.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center justify-end gap-3 font-bold" style="color: var(--luxury-dark);">
                                <span>100% استخدام مواد طبية آمنة</span>
                                <i class="fas fa-check-circle" style="color: var(--luxury-brown);"></i>
                            </li>
                            <li class="flex items-center justify-end gap-3 font-bold" style="color: var(--luxury-dark);">
                                <span>بيئة معقمة ومريحة جداً</span>
                                <i class="fas fa-check-circle" style="color: var(--luxury-brown);"></i>
                            </li>
                            <li class="flex items-center justify-end gap-3 font-bold" style="color: var(--luxury-dark);">
                                <span>نتائج تدوم لأسابيع مع الحفاظ على رموشك الطبيعية</span>
                                <i class="fas fa-check-circle" style="color: var(--luxury-brown);"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <footer class="glass-card py-8 mt-12">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <div class="flex justify-center gap-8 mb-6">
                    <a href="#" class="group relative">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: rgba(200, 162, 122, 0.15); color: var(--luxury-brown);">
                            <i class="fab fa-instagram text-xl"></i>
                        </div>
                    </a>
                    <a href="#" class="group relative">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: rgba(200, 162, 122, 0.15); color: var(--luxury-brown);">
                            <i class="fab fa-tiktok text-xl"></i>
                        </div>
                    </a>
                    <a href="#" class="group relative">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: rgba(200, 162, 122, 0.15); color: var(--luxury-brown);">
                            <i class="fab fa-whatsapp text-xl"></i>
                        </div>
                    </a>
                </div>
                
                <p class="text-gray-400 text-sm">©️ 2026 <span style="color: var(--luxury-brown);">LASHE OUT</span> STUDIO</p>
                <p class="text-gray-400 text-xs mt-1">عمان، الأردن</p>
            </div>
        </footer>

        {{-- ========== زر واتساب ثابت (Floating Button) ========== --}}
        <a href="https://wa.me/962791234567?text=مرحباً، لدي استفسار عن خدمات الرموش في Lashe Out" 
           target="_blank"
           class="fixed bottom-6 left-6 z-50 flex items-center justify-center w-14 h-14 rounded-full shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-xl"
           style="background: #25D366;">
            <i class="fab fa-whatsapp text-2xl" style="color: white;"></i>
        </a>
        
    </div>

</body>
</html>