<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <title>{{ config('app.name', 'Lashe Out') }} ✨</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Font - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { 
            font-family: 'Tajawal', sans-serif; 
        }
        
        :root {
            --cream: #F3EDE6;
            --sand: #D6C3AD;
            --primary-text: #2B1E1A;
            --secondary-text: #7C8574;
            --muted-text: #B9ADA3;
            --bronze: #B08D57;
            --bronze-dark: #9a7848;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        body {
            background: linear-gradient(135deg, var(--cream), #E8DCD0);
        }
        
        /* Glass Navigation */
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(176, 141, 87, 0.2);
        }
        
        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(176, 141, 87, 0.2);
        }
        
        .btn-luxury {
            background: var(--bronze);
            transition: all 0.3s ease;
        }
        .btn-luxury:hover {
            background: var(--bronze-dark);
            transform: scale(1.02);
        }
        
        .btn-outline-luxury {
            background: transparent;
            border: 2px solid var(--bronze);
            color: var(--bronze);
            transition: all 0.3s ease;
        }
        .btn-outline-luxury:hover {
            background: var(--bronze);
            color: white;
        }
        
        /* Service Cards */
        .service-card { 
            transition: all 0.4s ease; 
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(176, 141, 87, 0.15);
            border-radius: 1rem;
            padding: 1.5rem;
            cursor: pointer;
        }
        .service-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 20px 40px rgba(176, 141, 87, 0.15); 
            border-color: var(--bronze); 
        }
        
        .choice-card, .staff-card, .location-card, .time-slot {
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(176, 141, 87, 0.15);
            border-radius: 0.75rem;
            padding: 1rem;
        }
        .choice-card:hover, .staff-card:hover, .location-card:hover, .time-slot:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-color: var(--bronze);
        }
        
        .selected {
            background: var(--bronze);
            color: white;
            border-color: var(--bronze-dark);
        }
        .selected i, .selected p {
            color: white;
        }
        
        .booking-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(4px);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(176, 141, 87, 0.15);
            transition: all 0.3s ease;
        }
        .booking-card:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
            border-color: var(--bronze);
        }
        
        .days-left {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: bold;
        }
        .days-today {
            background: rgba(176, 141, 87, 0.15);
            color: var(--bronze-dark);
        }
        .days-tomorrow {
            background: rgba(214, 195, 173, 0.2);
            color: var(--bronze);
        }
        .days-upcoming {
            background: rgba(124, 133, 116, 0.1);
            color: var(--secondary-text);
        }
        
        .badge-confirmed {
            background: #d1fae5;
            color: #065f46;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            display: inline-block;
        }
        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            display: inline-block;
        }
        .badge-completed {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            display: inline-block;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(4px);
            border-radius: 1rem;
            padding: 1rem;
            text-align: center;
            border: 1px solid rgba(176, 141, 87, 0.15);
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }
        
        .progress-bar {
            background: rgba(176, 141, 87, 0.15);
            border-radius: 9999px;
            height: 0.5rem;
            overflow: hidden;
        }
        .progress-fill {
            background: var(--bronze);
            border-radius: 9999px;
            height: 100%;
            transition: width 0.5s ease;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--bronze), var(--bronze-dark));
            border-radius: 1rem;
            padding: 1.5rem;
            color: var(--cream);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
        }
        .action-view {
            background: #f3f4f6;
            color: #374151;
        }
        .action-view:hover {
            background: var(--bronze);
            color: white;
        }
        .action-cancel {
            background: #fee2e2;
            color: #dc2626;
        }
        .action-cancel:hover {
            background: #dc2626;
            color: white;
        }
        
        .queue-alert {
            background: rgba(255, 251, 235, 0.8);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(176, 141, 87, 0.3);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
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
        
        ::selection {
            background-color: var(--bronze);
            color: var(--cream);
        }
        
        @media (max-width: 768px) {
            .service-card, .choice-card {
                padding: 1rem;
            }
            .page-header {
                padding: 1rem;
            }
            .page-header h1 {
                font-size: 1.25rem;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>
    <div class="min-h-screen">
        @include('layouts.navigation')

        @isset($header)
            <header class="glass-card shadow-sm mb-6">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>