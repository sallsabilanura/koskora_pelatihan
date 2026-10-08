<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KosKora') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                        },
                        fontFamily: {
                            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
            .brand-solid {
                background-color: #1e1b9b;
            }
            .brand-pattern {
                background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
                background-size: 20px 20px;
            }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-[#fdfdfe] overflow-hidden">
        <!-- Premium Fast Loader -->
        <style>
            @keyframes loader-pulse-glow {
                0%, 100% { filter: drop-shadow(0 0 0 rgba(30, 27, 155, 0)); transform: scale(1); }
                50% { filter: drop-shadow(0 0 15px rgba(30, 27, 155, 0.5)); transform: scale(1.1); }
            }
            .loader-logo-card {
                animation: loader-pulse-glow 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            }
            @keyframes loader-bar-fill {
                0% { width: 0%; }
                100% { width: 100%; }
            }
            .loader-bar-inner {
                animation: loader-bar-fill 1s ease-in-out forwards;
            }
        </style>
        <div id="koskora-loader" class="fixed inset-0 z-[99999] bg-[#fdfdfe]/90 backdrop-blur-md flex items-center justify-center transition-all duration-500">
            <div class="relative flex flex-col items-center translate-y-[-10%]">
                <img src="{{ asset('favicon.png') }}" alt="Loading" class="w-16 h-16 relative z-10 loader-logo-card mb-6">
                <h2 class="text-[#1e1b9b] font-extrabold tracking-[0.2em] text-xs uppercase mb-4 opacity-80">Koskora</h2>
                <div class="w-32 h-1 bg-slate-200 rounded-full overflow-hidden">
                    <div class="h-full bg-[#1e1b9b] loader-bar-inner"></div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('load', function() {
                const loader = document.getElementById('koskora-loader');
                if (loader) {
                    loader.style.opacity = '0';
                    loader.style.visibility = 'hidden';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 500);
                }
            });
        </script>
        <div class="h-screen flex flex-col md:flex-row divide-x divide-slate-100">
            <!-- Left Side: Brand Area -->
            <div class="hidden md:flex md:w-2/3 h-full brand-solid brand-pattern relative flex-col justify-between p-16 lg:p-24 shadow-[inset_-20px_0_30px_rgba(0,0,0,0.1)]">
                <!-- Background Image Layer -->
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('real_kos_building.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-10 mix-blend-luminosity">
                </div>

                <div class="relative z-10">
                    <a href="/" class="inline-block transition-transform hover:scale-105 active:scale-95 duration-300">
                        <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-14 w-auto brightness-0 invert shadow-2xl rounded-none">
                    </a>
                </div>

                <div class="relative z-10">
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-[1.1] mb-6">
                        Modern.<br>
                        Visual.<br>
                        <span class="text-white/60">Kos</span><span class="text-brand-red">K</span><span class="text-white/60">ora.</span>
                    </h1>
                    <p class="text-white/80 text-lg font-medium tracking-wide">
                        Platform Manajemen Kos Terpadu & Pintar.
                    </p>
                   
                  
                </div>

                <div class="absolute bottom-6 left-16 lg:left-24 z-10">
                    <p class="text-white/20 text-[10px] font-medium tracking-widest">&copy; 2026 KosKora. All Rights Reserved.</p>
                </div>
            </div>

            <!-- Right Side: Content Area -->
            <div class="flex-1 flex flex-col p-8 sm:p-12 lg:px-16 xl:px-24 bg-[#fdfdfe] relative h-full overflow-y-auto">
                <div class="w-full max-w-xl mx-auto my-auto">
                    <div class="md:hidden mb-16 flex justify-center">
                        <a href="/">
                            <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-10 w-auto">
                        </a>
                    </div>
                    {{ $slot }}
                </div>

                <div class="mt-auto"></div>
            </div>
        </div>
    </body>
</html>
