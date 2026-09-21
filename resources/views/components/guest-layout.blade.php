<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KosKora') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'brand-blue': '#1e1b9b',
                            'brand-red': '#eb0008',
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            .brand-solid {
                background-color: #1e1b9b;
            }
            .brand-pattern {
                background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
                background-size: 20px 20px;
            }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-[#fdfdfe] overflow-x-hidden">
        <div class="min-h-screen flex flex-col md:flex-row divide-x divide-slate-100">
            <!-- Left Side: Brand Area -->
            <div class="hidden md:flex md:w-5/12 brand-solid brand-pattern relative flex-col justify-between p-16 lg:p-24 overflow-hidden shadow-[inset_-20px_0_30px_rgba(0,0,0,0.1)]">
                <div class="relative z-10">
                    <a href="/" class="inline-block transition-transform hover:scale-105 active:scale-95 duration-300">
                        <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-14 w-auto brightness-0 invert shadow-2xl rounded-none">
                    </a>
                </div>

                <div class="relative z-10">
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-[1.1] mb-8">
                        Modern.<br>
                        Visual.<br>
                        <span class="text-white/60">Kos</span><span class="text-brand-red">K</span><span class="text-white/60">ora.</span>
                    </h1>
                   
                  
                </div>

                <div class="relative z-10 pt-10">
                    <p class="text-white/30 text-xs font-black uppercase tracking-[0.2em]">&copy; 2024 KosKora. All Rights Reserved.</p>
                </div>
            </div>

            <!-- Right Side: Content Area -->
            <div class="flex-1 flex flex-col justify-center p-8 sm:p-12 lg:p-24 bg-[#fdfdfe] relative">
                <!-- Mobile Logo -->
                <div class="md:hidden mb-16 flex justify-center">
                    <a href="/">
                        <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-10 w-auto">
                    </a>
                </div>

                <div class="w-full max-w-md mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
