<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lashe Out ✨ | فخامة الرموش</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap');
        :root { --brand-pink: #f472b6; }
        * { font-family: 'Tajawal', sans-serif; scroll-behavior: smooth; }
        body { background-color: #fff9f9; }
        .service-card { transition: all 0.4s ease; border: 1px solid rgba(244, 114, 182, 0.1); height: 100%; }
        .service-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(244, 114, 182, 0.15); border-color: var(--brand-pink); }
        .btn-grad { background: linear-gradient(to left, #ec4899, #f472b6); transition: 0.3s; }
        .btn-grad:hover { opacity: 0.9; transform: scale(1.02); }
        .glass-nav { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(244, 114, 182, 0.1); }
    </style>
</head>
<body class="antialiased text-gray-800">

    <nav class="glass-nav sticky top-0 z-[100] py-4">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-3xl font-black text-pink-600 tracking-tighter">LASHE OUT<span></span></a>
            <div class="hidden md:flex gap-8 items-center">
                <a href="#services" class="font-bold text-gray-600 hover:text-pink-500 transition">الخدمات</a>
                <a href="#employees" class="font-bold text-gray-600 hover:text-pink-500 transition">الخبيرات</a>
                <a href="#about" class="font-bold text-gray-600 hover:text-pink-500 transition">من نحن</a>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-grad text-white px-6 py-2 rounded-full font-bold">لوحة التحكم</a>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-gray-600 px-4">دخول</a>
                    <a href="{{ route('register') }}" class="btn-grad text-white px-8 py-2.5 rounded-full font-bold shadow-md shadow-pink-100">ابدئي الآن</a>
                @endauth
            </div>
        </div>
    </nav>

    <header class="py-20">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div class="text-right">
                <span class="text-pink-500 font-bold tracking-widest uppercase text-sm mb-4 block">Professional Lash Studio</span>
                <h1 class="text-6xl font-black text-gray-900 leading-tight mb-6">جمالك يبدأ <br> <span class="text-pink-500">من عينيكِ</span></h1>
                <p class="text-gray-500 text-xl leading-relaxed mb-10">نقدم لكِ أفضل أنواع الرموش العالمية بأيدي خبيرات متخصصات في الأردن.</p>
                <a href="{{ route('register') }}" class="btn-grad text-white px-12 py-4 rounded-2xl font-bold text-lg inline-block shadow-xl shadow-pink-200">احجزي موعدك الآن</a>
            </div>
            <div class="relative">
                <img src="{{ asset('images/profile.jpeg') }}" class="w-full h-[500px] object-cover rounded-[3rem] shadow-2xl border-8 border-white">
            </div>
        </div>
    </header>

    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black mb-4">خدماتنا المميزة</h2>
                <div class="w-20 h-1.5 bg-pink-500 mx-auto rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-right">
                @php
                $services = [
                    ['name' => 'Classic Set', 'price' => '30', 'img' => 'classic.jpeg', 'desc' => 'تركيب رمش واحد طبيعي لكل رمش أصلي لمظهر يومي هادئ.'],
                    ['name' => 'Volume Set', 'price' => '45', 'img' => 'volume.jpeg', 'desc' => 'كثافة عالية وسواد فاحم، مثالي للمناسبات والحفلات.'],
                    ['name' => 'Wispy Set', 'price' => '50', 'img' => 'wispy.jpeg', 'desc' => 'تصميم ريشي متدرج يعطي العين مظهراً واسعاً وجذاباً.'],
                    ['name' => 'Wet Set', 'price' => '40', 'img' => 'wet.jpeg', 'desc' => 'مظهر الرموش المبللة العصرية التي تعكس جرأة ملامحك.'],
                    ['name' => 'Anime Set', 'price' => '55', 'img' => 'anime.jpeg', 'desc' => 'ستايل الأنمي الشهير بتوزيع Spikes فني ومميز جداً.']
                ];
                @endphp

                @foreach($services as $s)
                <div class="service-card bg-pink-50/50 rounded-[2.5rem] p-5 flex flex-col">
                    <img src="{{ asset('images/'.$s['img']) }}" class="w-full h-56 object-cover rounded-[2rem] mb-6 shadow-md">
                    <h3 class="text-2xl font-bold mb-2">{{ $s['name'] }}</h3>
                    <p class="text-gray-500 mb-6 flex-grow">{{ $s['desc'] }}</p>
                    <div class="flex justify-between items-center border-t border-pink-100 pt-4">
                        <span class="text-xl font-black text-pink-600">{{ $s['price'] }} د.أ</span>
                        <a href="{{ route('register') }}" class="bg-pink-500 text-white px-4 py-2 rounded-xl text-sm font-bold">حجز</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="employees" class="py-24 bg-pink-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black mb-4">خبيراتنا المحترفات</h2>
                <p class="text-pink-500 font-bold">جميعهن يقدمن كافة الخدمات المذكورة أعلاه بأعلى معايير الدقة</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="bg-white p-8 rounded-[3rem] text-center shadow-sm">
                    <img src="https://img.freepik.com/free-photo/portrait-young-muslim-woman-wearing-hijab_23-2150930263.jpg" class="w-48 h-48 mx-auto rounded-full object-cover border-4 border-pink-100 mb-6">
                    <h4 class="text-2xl font-bold mb-2">لينا العتوم</h4>
                    <p class="text-gray-500 mb-6 text-sm">خبرة 5 سنوات في فن الرموش، متقنة لجميع التقنيات العالمية.</p>
                    <a href="https://instagram.com" class="text-pink-500 hover:scale-110 transition inline-block"><i class="fab fa-instagram text-3xl"></i></a>
                </div>
                <div class="bg-white p-8 rounded-[3rem] text-center shadow-sm">
                    <img src="https://img.freepik.com/free-photo/modern-muslim-woman-portrait_23-2150930261.jpg" class="w-48 h-48 mx-auto rounded-full object-cover border-4 border-pink-100 mb-6">
                    <h4 class="text-2xl font-bold mb-2">هيا الكردي</h4>
                    <p class="text-gray-500 mb-6 text-sm">متخصصة في رسم العين وتنسيق الرموش بما يناسب شكل الوجه.</p>
                    <a href="https://instagram.com" class="text-pink-500 hover:scale-110 transition inline-block"><i class="fab fa-instagram text-3xl"></i></a>
                </div>
                <div class="bg-white p-8 rounded-[3rem] text-center shadow-sm">
                    <img src="https://img.freepik.com/free-photo/portrait-young-muslim-woman_23-2150930225.jpg" class="w-48 h-48 mx-auto rounded-full object-cover border-4 border-pink-100 mb-6">
                    <h4 class="text-2xl font-bold mb-2">ندى جابر</h4>
                    <p class="text-gray-500 mb-6 text-sm">مدربة معتمدة في تقنيات الويسبـي والأنمي ومبدعة في كافة الموديلات.</p>
                    <a href="https://instagram.com" class="text-pink-500 hover:scale-110 transition inline-block"><i class="fab fa-instagram text-3xl"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative order-2 md:order-1">
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-pink-100 rounded-full -z-10"></div>
                    <img src="https://images.unsplash.com/photo-1560750588-73207b1ef5b8?q=80&w=1000" class="rounded-[3rem] shadow-2xl">
                </div>
                <div class="text-right order-1 md:order-2">
                    <h2 class="text-4xl font-black mb-8">لماذا اختيار استوديو Lashe Out؟</h2>
                    <p class="text-gray-600 text-lg leading-relaxed mb-8">
                        نحن لسنا مجرد مركز تجميل، نحن وجهة لكل امرأة تبحث عن الثقة والتميز. بدأنا في عمان كأول استوديو متخصص حصرياً في تركيب الرموش بأحدث الصيحات العالمية (Anime, Wispy, Wet).
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center justify-end gap-3 text-gray-700 font-bold">
                            استخدام مواد طبية آمنة 100% <i class="fas fa-check-circle text-pink-500"></i>
                        </li>
                        <li class="flex items-center justify-end gap-3 text-gray-700 font-bold">
                            بيئة معقمة ومريحة جداً <i class="fas fa-check-circle text-pink-500"></i>
                        </li>
                        <li class="flex items-center justify-end gap-3 text-gray-700 font-bold">
                            نتائج تدوم لأسابيع مع الحفاظ على رموشك الطبيعية <i class="fas fa-check-circle text-pink-500"></i>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-black text-pink-500 mb-6 underline decoration-wavy">LASHE OUT ✨</h2>
            <p class="text-gray-400 mb-8 max-w-lg mx-auto italic">المكان الذي تلتقي فيه الدقة بالجمال. خبرة احترافية تليق بكِ.</p>
            <div class="flex justify-center gap-10 text-2xl mb-12">
                <a href="#" class="hover:text-pink-500"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-pink-500"><i class="fab fa-tiktok"></i></a>
                <a href="#" class="hover:text-pink-500"><i class="fab fa-whatsapp"></i></a>
            </div>
            <div class="border-t border-gray-800 pt-8 text-gray-500 text-sm">
                &copy; 2026 جميع الحقوق محفوظة لـ LASHE OUT STUDIO. عمان، الأردن.
            </div>
        </div>
    </footer>

</body>
</html>