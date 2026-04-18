<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | Lashe Out ✨</title>
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
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(200, 162, 122, 0.2);
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
        
        input {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(200, 162, 122, 0.3);
            transition: all 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: var(--luxury-brown);
            background: white;
            box-shadow: 0 0 0 3px rgba(200, 162, 122, 0.2);
        }
        
        input::placeholder {
            color: #c1b5a5;
        }
    </style>
</head>
<body>

    <div class="blob-1"></div>
    <div class="blob-2"></div>
    <div class="blob-3"></div>

    <div class="content-wrapper min-h-screen flex items-center justify-center p-6">
        
        <div class="glass-card rounded-3xl p-8 w-full max-w-md shadow-xl">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: rgba(200, 162, 122, 0.15);">
                    <i class="fas fa-star text-3xl" style="color: var(--luxury-brown);"></i>
                </div>
                <h2 class="text-2xl font-bold" style="color: var(--luxury-dark);">✨ تسجيل الدخول</h2>
                <p class="text-gray-500 mt-2">مرحباً بكِ مجدداً في Lashe Out</p>
            </div>

            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-right">
                    <i class="fas fa-check-circle ml-2"></i> {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-right">
                    <i class="fas fa-exclamation-circle ml-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" dir="rtl">
                @csrf

                <div class="mb-5">
                    <label class="block font-bold mb-2 text-right" style="color: var(--luxury-dark);">
                        <i class="fas fa-envelope ml-1" style="color: var(--luxury-brown);"></i> البريد الإلكتروني
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                        class="w-full px-4 py-3 rounded-xl text-right" 
                        placeholder="example@mail.com" required>
                </div>

                <div class="mb-5">
                    <label class="block font-bold mb-2 text-right" style="color: var(--luxury-dark);">
                        <i class="fas fa-lock ml-1" style="color: var(--luxury-brown);"></i> كلمة المرور
                    </label>
                    <input type="password" name="password" 
                        class="w-full px-4 py-3 rounded-xl text-right" 
                        placeholder="كلمة المرور" required>
                </div>

                <div class="mb-6 text-right">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" 
                            class="rounded border-gray-300 focus:ring-0" 
                            style="accent-color: var(--luxury-brown);">
                        <span class="text-gray-600">تذكريني</span>
                    </label>
                </div>

                <div class="flex justify-end mb-6">
                    <a href="{{ route('password.request') }}" class="text-sm transition hover:opacity-70" style="color: var(--luxury-brown);">
                        نسيت كلمة المرور؟
                    </a>
                </div>

                <button type="submit" class="btn-luxury w-full text-white font-bold py-3 rounded-xl transition shadow-md shadow-[#C8A27A]/30">
                    <i class="fas fa-sign-in-alt ml-2"></i> دخول
                </button>

                <div class="text-center mt-6">
                    <p class="text-gray-500">
                        ليس لديكِ حساب؟
                        <a href="{{ route('register') }}" class="font-bold transition hover:opacity-70" style="color: var(--luxury-brown);">
                            إنشاء حساب جديد
                        </a>
                    </p>
                </div>
            </form>

            <div class="text-center mt-6 pt-4 border-t" style="border-color: rgba(200, 162, 122, 0.2);">
                <a href="/" class="text-sm transition hover:opacity-70" style="color: var(--luxury-dark);">
                    <i class="fas fa-arrow-right ml-1"></i> العودة إلى الصفحة الرئيسية
                </a>
            </div>

        </div>

    </div>

</body>
</html>