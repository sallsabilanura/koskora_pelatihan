<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>KosKora | Premium Living Redefined</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Fonts: System & Modern Weights -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS + Custom Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#1e1b9b',
                            red: '#d42e2e',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        'xl': '1rem',
                        '2xl': '1.25rem',
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                    }
                }
            }
        }
    </script>
    <style>
        /* custom overrides & brand behavior with refined rounding */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; background: #ffffff; -webkit-font-smoothing: antialiased; }

        /* Modern rounded utilities */
        .rounded-soft { border-radius: 1.25rem; }
        .rounded-soft-lg { border-radius: 1.5rem; }
        .rounded-soft-xl { border-radius: 2rem; }
        .rounded-card { border-radius: 1.5rem; }
        .rounded-button { border-radius: 0.875rem; }
        
        /* Brand-centric gradients & shadows */
        .brand-gradient-bg { background: linear-gradient(135deg, #1e1b9b 0%, #11106e 100%); }
        .brand-shadow { box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.02); }
        .brand-shadow-soft { box-shadow: 0 15px 30px -15px rgba(30, 27, 155, 0.1); }
        .brand-card-hover { transition: all 0.35s cubic-bezier(0.2, 0, 0, 0.2); }
        .brand-card-hover:hover { transform: translateY(-6px); box-shadow: 0 30px 50px -20px rgba(30, 27, 155, 0.25); border-color: rgba(30, 27, 155, 0.2); }
        
        .input-modern { transition: all 0.2s ease; background-color: #fcfdfe; border: 2px solid #eef2f6; border-radius: 1rem; }
        .input-modern:focus { background-color: #ffffff; border-color: #1e1b9b; outline: none; box-shadow: 0 0 0 4px rgba(30,27,155,0.05); }
        
        .btn-primary { transition: all 0.25s ease; background: #1e1b9b; border-radius: 0.875rem; }
        .btn-primary:hover { background: #11106e; transform: scale(0.98); }
        .btn-accent { transition: all 0.25s ease; background: #eb0008; border-radius: 0.875rem; }
        .btn-accent:hover { background: #b11f1f; transform: scale(0.98); }
        
        .glass-nav { background: rgba(255,255,255,0.96); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.03); border-radius: 0 0 2rem 2rem; }
        .section-title-highlight { position: relative; display: inline-block; }
        .section-title-highlight:after { content: ''; position: absolute; bottom: -8px; left: 0; width: 48px; height: 3px; background: #d42e2e; border-radius: 6px; }
        .badge-status { backdrop-filter: blur(4px); background: rgba(0,0,0,0.65); border-radius: 2rem; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        img { display: block; max-width: 100%; }
        
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.02); opacity: 0.95; }
        }
        .animate-pulse-slow { animation: pulse-slow 4s infinite ease-in-out; }

        /* Modern card inner rounding */
        .card-modern {
            border-radius: 1.5rem;
            transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
        }
        .service-card {
            border-radius: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -15px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="antialiased text-slate-800 overflow-x-hidden">

    <!-- ========== NAVIGATION with rounded bottom ========== -->
    <nav class="glass-nav fixed top-0 w-full z-50 py-4 md:py-5 px-4 md:px-10 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo + Branding -->
            <div class="flex items-center gap-3">
                <a href="/">
                    <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-8 w-auto md:h-10">
                </a>
                <span class="text-[10px] font-bold tracking-[0.2em] text-slate-500 hidden sm:block border-l border-slate-200 pl-3 uppercase">Premium Living</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#katalog" class="text-[12px] font-semibold text-slate-500 hover:text-brand-navy transition uppercase tracking-wide">Katalog</a>
                <a href="#layanan" class="text-[12px] font-semibold text-slate-500 hover:text-brand-navy transition uppercase tracking-wide">Layanan</a>
                <a href="#tentang" class="text-[12px] font-semibold text-slate-500 hover:text-brand-navy transition uppercase tracking-wide">Tentang</a>
            </div>
            
            <div class="flex items-center gap-3">
                @if (Route::has('admin.login'))
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 bg-brand-navy text-white text-[11px] font-bold rounded-button shadow-md hover:bg-brand-red transition-all duration-200 uppercase tracking-wide">Dashboard Admin</a>
                    @else
                        <a href="{{ route('admin.login') }}" class="hidden sm:inline-block text-[11px] font-bold text-slate-500 hover:text-brand-navy transition uppercase tracking-wider">Masuk Admin</a>
                    @endauth
                @endif

                <!-- Mobile hamburger -->
                <button onclick="document.getElementById('mobileNav').classList.toggle('hidden')" class="md:hidden p-2 rounded-xl hover:bg-slate-100 transition" aria-label="Menu">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Drawer -->
        <div id="mobileNav" class="hidden md:hidden mt-4 pb-2 border-t border-slate-100 pt-4">
            <div class="flex flex-col gap-3">
                <a href="#katalog" class="text-sm font-semibold text-slate-600 hover:text-brand-navy py-2">Katalog</a>
                <a href="#layanan" class="text-sm font-semibold text-slate-600 hover:text-brand-navy py-2">Layanan</a>
                <a href="#tentang" class="text-sm font-semibold text-slate-600 hover:text-brand-navy py-2">Tentang</a>
                @guest
                    <a href="{{ route('admin.login') }}" class="text-sm font-semibold text-brand-navy py-2">Masuk Admin</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- ========== HERO SECTION with rounded corners ========== -->
    <section class="relative pt-24 md:pt-32 pb-16 md:pb-24 px-4 md:px-6 overflow-hidden">
        <!-- Background Image with Overlay & Blur -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('hero-bg.png') }}" class="w-full h-full object-cover opacity-90 scale-105 transform">
            <div class="absolute inset-0 bg-white/40 backdrop-blur-[2px]"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-white/40 to-white"></div>
        </div>

        <div class="max-w-6xl mx-auto text-center relative z-10">
            <div class="inline-flex mb-8 bg-white/80 backdrop-blur-md rounded-full px-5 py-2 border border-brand-navy/10 shadow-sm animate-pulse-slow">
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-brand-navy flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-navy opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-navy"></span>
                    </span>
                    Premium Ecosystem
                </span>
            </div>
            <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.05] text-brand-navy max-w-4xl mx-auto drop-shadow-[0_10px_10px_rgba(255,255,255,0.8)]">
                Hunian Berkelas.<br>
                <span class="text-brand-red bg-clip-text">Pengelolaan Tanpa Ribet.</span>
            </h1>
            <p class="text-slate-700 text-xs md:text-lg max-w-2xl mx-auto mt-5 md:mt-8 font-semibold leading-relaxed drop-shadow-sm">
                Manajemen kos profesional & teknologi cerdas. <span class="text-brand-navy/60 font-medium hidden sm:inline">Nikmati kemudahan cari kamar, laundry, dan cleaner terverifikasi dalam satu genggaman.</span>
            </p>
            
            <!-- Search Console with rounded inputs -->
            <div class="max-w-4xl mx-auto mt-6 md:mt-8 bg-white/90 backdrop-blur-sm brand-shadow-soft p-2 md:p-3 rounded-2xl border border-slate-100">
                <form action="{{ url('/') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="flex-[2] relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari area, tipe..." class="w-full pl-12 pr-4 py-4 rounded-xl input-modern font-medium text-sm">
                    </div>
                    <div class="flex-1">
                        <select name="city" class="w-full px-5 py-4 rounded-xl input-modern font-medium text-sm cursor-pointer appearance-none">
                            <option value="">Semua Kota</option>
                            @foreach($cities ?? [] as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <select name="district" class="w-full px-5 py-4 rounded-xl input-modern font-medium text-sm cursor-pointer appearance-none">
                            <option value="">Semua Daerah</option>
                            @foreach($districts ?? [] as $district)
                                <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full md:w-auto px-8 md:px-12 py-4 bg-brand-navy text-white rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-brand-red transition-all shadow-md">Jelajahi</button>
                </form>
            </div>
            
            <!-- quick stats -->
            <div class="flex flex-wrap justify-center gap-8 mt-14">
                <div><span class="font-extrabold text-2xl text-brand-navy">150+</span><span class="text-xs font-semibold text-slate-500 block uppercase">Unit Premium</span></div>
                <div><span class="font-extrabold text-2xl text-brand-navy">12</span><span class="text-xs font-semibold text-slate-500 block uppercase">Kota Besar</span></div>
                <div><span class="font-extrabold text-2xl text-brand-navy">99%</span><span class="text-xs font-semibold text-slate-500 block uppercase">Kepuasan Tenant</span></div>
            </div>
        </div>
    </section>

    <!-- ========== CATALOG SECTION (ROOMS) rounded cards ========== -->
    <section id="katalog" class="py-16 px-6 scroll-mt-24 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap justify-between items-end gap-6 mb-12">
                <div>
                    <span class="text-[11px] font-black text-brand-red uppercase tracking-[0.3em]">Koleksi eksklusif</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mt-3">Rekomendasi Kamar<span class="text-slate-300">.</span></h2>
                    <p class="text-slate-400 text-sm max-w-lg mt-2">Unit terbaik dengan standar kebersihan & keamanan tinggi.</p>
                </div>
                <a href="#katalog" class="text-[12px] font-bold uppercase tracking-wider text-brand-navy border-b border-brand-navy pb-1 hover:text-brand-red transition">Lihat semua →</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($groupedRooms ?? [] as $property)
                    <div class="group bg-white rounded-2xl border border-slate-100 overflow-hidden brand-card-hover transition-all flex flex-col shadow-sm hover:shadow-xl">
                        <div class="relative h-64 bg-slate-50 overflow-hidden rounded-t-2xl">
                            @if($property->thumbnail)
                                <img src="{{ asset('storage/' . $property->thumbnail) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-100">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                <span class="px-3 py-1.5 bg-brand-navy text-white text-[10px] font-bold rounded-full shadow-md uppercase tracking-widest self-start">
                                    {{ $property->gender }}
                                </span>
                                @if($property->has_discount)
                                    <div class="flex flex-col gap-1">
                                        <span class="px-3 py-1.5 bg-brand-red text-white text-[10px] font-black rounded-full shadow-lg uppercase tracking-widest animate-pulse self-start">
                                            {{ $property->max_discount }}% OFF
                                        </span>
                                        @if($property->discount_label)
                                            <span class="px-2 py-1 bg-white/90 backdrop-blur text-brand-red text-[8px] font-black rounded-md shadow-sm uppercase tracking-tighter self-start border border-brand-red/20">
                                                {{ $property->discount_label }}
                                            </span>
                                        @endif
                                        @if($property->discount_end)
                                            <span class="px-2 py-1 bg-brand-navy/80 backdrop-blur text-white text-[7px] font-bold rounded-md shadow-sm uppercase tracking-wider self-start flex items-center gap-1">
                                                <i class="fas fa-clock text-[6px]"></i>
                                                Hingga {{ $property->discount_end->format('d M') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 capitalize">
                                <div class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-2xl shadow-lg flex items-center justify-between border border-white/50">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-star text-amber-400 text-[10px]"></i>
                                        <span class="text-[11px] font-bold text-slate-900">{{ number_format($property->avg_rating, 1) }}</span>
                                        <span class="text-[9px] text-slate-400 font-medium lowercase">({{ $property->total_reviews }} ulasan)</span>
                                    </div>
                                    <div class="h-4 w-[1px] bg-slate-200 mx-2"></div>
                                    <div class="flex gap-1">
                                        @foreach($property->room_types as $type)
                                            <span class="text-[8px] font-black text-brand-navy uppercase tracking-tighter">{{ $type }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-2xl font-extrabold text-slate-900 mb-1 leading-tight">{{ $property->name }}</h3>
                            <div class="flex items-center gap-1 text-slate-400 text-xs mb-4">
                                <svg class="w-4 h-4 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="text-[11px] font-medium uppercase tracking-wider">{{ $property->location }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between pt-5 border-t border-slate-50 mt-auto">
                                <div>
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Mulai dari</p>
                                    @if($property->has_discount)
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-slate-400 line-through font-bold">Rp {{ number_format($property->min_price, 0, ',', '.') }}</span>
                                            <p class="text-xl font-extrabold text-brand-red">Rp {{ number_format($property->min_discounted_price, 0, ',', '.') }}<span class="text-xs text-slate-400 font-normal">/bln</span></p>
                                        </div>
                                    @else
                                        <p class="text-xl font-extrabold text-brand-navy">Rp {{ number_format($property->min_price, 0, ',', '.') }}<span class="text-xs text-slate-400 font-normal">/bln</span></p>
                                    @endif
                                </div>
                                <a href="#" class="px-8 py-3 bg-brand-navy text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-red transition shadow-sm active:scale-95 inline-block">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center text-slate-300 font-bold uppercase tracking-[0.2em] border-2 border-dashed border-slate-100 rounded-3xl">
                        Katalog sedang diperbarui.
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="mt-12 flex justify-center">
                @if(isset($groupedRooms) && method_exists($groupedRooms, 'links'))
                    {{ $groupedRooms->appends(request()->query())->links() }}
                @endif
            </div>

        </div>
    </section>

  
    
    <!-- ========== TENTANG with rounded elements ========== -->
    <section id="tentang" class="py-16 px-6 scroll-mt-24 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-20">
            <div class="flex-1">
                <span class="text-[11px] font-black text-brand-red uppercase tracking-[0.3em]">KosKora way</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-brand-navy mt-3 leading-tight">Bukan sekadar kos, <br>tapi <span class="text-brand-red">gaya hidup premium.</span></h2>
                <p class="text-slate-500 mt-8 leading-relaxed font-medium">Kami menyatukan teknologi, kebersihan standar hotel, dan akses layanan instan. Setiap properti telah melalui kurasi desain, keamanan, dan konektivitas terbaik untuk profesional muda & ekspat.</p>
                <div class="mt-10 flex flex-wrap gap-8">
                    <div><span class="font-extrabold text-3xl text-brand-navy">24/7</span><span class="block text-[10px] font-semibold uppercase text-slate-500 mt-1">SLA Support</span></div>
                    <div><span class="font-extrabold text-3xl text-brand-navy">+45</span><span class="block text-[10px] font-semibold uppercase text-slate-500 mt-1">Smart Units</span></div>
                    <div><span class="font-extrabold text-3xl text-brand-navy">4.9★</span><span class="block text-[10px] font-semibold uppercase text-slate-500 mt-1">User Rating</span></div>
                </div>
                <button class="mt-12 px-10 py-4 bg-brand-navy text-white rounded-xl text-[12px] font-bold shadow-lg hover:bg-brand-red transition-all uppercase tracking-widest">Gabung Jadi Mitra</button>
            </div>
            <div class="flex-1 relative">
                <div class="bg-slate-50 rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-brand-navy/5 transform -skew-x-12 translate-x-32 group-hover:translate-x-40 transition-transform duration-1000 rounded-2xl"></div>
                    <img src="https://placehold.co/800x600/1e1b9b/ffffff?text=KOSKORA+ECOSYSTEM" alt="ecosystem" class="w-full rounded-xl shadow-2xl relative z-10 border border-white">
                </div>
            </div>
        </div>
    </section>
    
    <!-- ========== FOOTER ========== -->
    <footer class="border-t border-slate-100 py-12 px-6 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="flex flex-col items-center md:items-start gap-4">
                <img src="{{ asset('koskora.png') }}" class="h-8 w-auto" alt="Logo">
                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">© 2025 KosKora. All rights reserved.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-10 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                <a href="#" class="hover:text-brand-navy transition">Privasi</a>
                <a href="#" class="hover:text-brand-navy transition">TOS</a>
                <a href="#" class="hover:text-brand-navy transition">Support</a>
                <a href="#" class="hover:text-brand-navy border-b-2 border-brand-red">IG</a>
            </div>
        </div>
        <div class="text-center text-[9px] text-slate-300 mt-12 uppercase tracking-[0.3em]">Premium Living, Simplified.</div>
    </footer>

</body>
</html>