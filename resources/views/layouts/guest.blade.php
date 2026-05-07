<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lashe Out') }} ✨ | Luxury Lash Studio</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Font - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ========================================
           Design System - Lashe Out Luxury
           ======================================== */
        
        * {
            font-family: 'Tajawal', sans-serif;
            scroll-behavior: smooth;
        }
        
        :root {
            --cream: #F3EDE6;
            --sand: #D6C3AD;
            --primary-text: #2B1E1A;
            --secondary-text: #7C8574;
            --muted-text: #B9ADA3;
            --bronze: #B08D57;
            --bronze-dark: #9a7848;
        }
        
        body {
            background: linear-gradient(135deg, var(--cream), #E8DCD0);
            min-height: 100vh;
            margin: 0;
        }
        
        /* Background Blobs */
        .blob-1 {
            position: fixed;
            width: 500px;
            height: 500px;
            background: var(--sand);
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
            background: #C4B5A0;
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
            background: var(--bronze);
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        
        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(176, 141, 87, 0.2);
            border-radius: 24px;
        }
        
        /* Luxury Button */
        .btn-luxury {
            background: var(--bronze);
            transition: all 0.3s ease;
        }
        
        .btn-luxury:hover {
            background: var(--bronze-dark);
            transform: scale(1.02);
        }
        
        /* Form Inputs */
        input, textarea, select {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(176, 141, 87, 0.3);
            transition: all 0.3s ease;
            border-radius: 16px;
            padding: 12px 16px;
            width: 100%;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--bronze);
            background: white;
            box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.2);
        }
        
        input::placeholder, textarea::placeholder {
            color: var(--muted-text);
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--sand);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--bronze);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--bronze-dark);
        }
        
        /* Selection */
        ::selection {
            background-color: var(--bronze);
            color: white;
        }
        
        /* Links */
        a {
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .blob-1, .blob-2, .blob-3 {
                display: none;
            }
            .content-wrapper {
                padding: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <div class="blob-1"></div>
    <div class="blob-2"></div>
    <div class="blob-3"></div>

    <div class="content-wrapper">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>