<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lashe Out') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Tajawal', sans-serif !important;
            }
            /* خلفية وردية ناعمة جداً تناسب الستوديو */
            .bg-custom-pink {
                background-color: #fff9f9;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-custom-pink">
            
            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl shadow-pink-100/50 overflow-hidden sm:rounded-[2.5rem] border border-pink-50">
                {{ $slot }}
            </div>

            <div class="mt-8 text-pink-400 font-bold tracking-widest text-sm italic">
                LASHE OUT STUDIO
            </div>
        </div>
    </body>
</html>