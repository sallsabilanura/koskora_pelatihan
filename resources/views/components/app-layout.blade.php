<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#1e1b9b">

        <title>KosKora — Platform Manajemen Kos Modern</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    
        <!-- Fonts & Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Design System & Logic -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="{{ asset('dashboard.css') }}?v={{ time() }}">
        
        <style>
            /* Force all tables to stretch 100% */
            .table-wrap, .overflow-x-auto { width: 100% !important; min-width: 100% !important; }
            .data-table { width: 100% !important; min-width: 100% !important; }
            
            /* Remove rounded corners from table headers that cause hanging edges */
            .data-table th, .data-table th:first-child, .data-table th:last-child {
                border-top-left-radius: 0 !important;
                border-top-right-radius: 0 !important;
            }
        </style>
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'brand': '#1e1b9b',
                            'brand-dark': '#14126d',
                            'brand-light': '#f0f1ff',
                        },
                        fontFamily: {
                            sans: ['Nunito', 'ui-sans-serif', 'system-ui'],
                        },
                        borderRadius: {
                            'premium': '16px',
                        }
                    }
                }
            }
        </script>
        <style type="text/tailwindcss">
            @layer utilities {
                .bnav-item {
                    @apply flex flex-col items-center gap-1 text-[10.4px] font-medium text-slate-500 transition-colors duration-200;
                }
                .bnav-item.active {
                    @apply text-brand;
                }
                .bnav-item:hover {
                    @apply text-brand;
                }
                .bnav-icon {
                    @apply text-xl mb-0.5;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased selection:bg-brand/10 selection:text-brand bg-slate-50 text-slate-600">
        <div class="flex min-h-screen overflow-hidden bg-slate-50">
            <!-- Sidebar Overlay (mobile) -->
            <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/20 backdrop-blur-[2px] z-[60] opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" onclick="closeSidebar()"></div>

            <!-- Sidebar Navigation -->
            @if(in_array(auth()->user()->role, ['user', 'laundry', 'cleaner']))
                <div class="hidden lg:block">
                    <x-sidebar />
                </div>
            @else
                <x-sidebar />
            @endif

            <!-- Main Panel -->
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto overflow-x-hidden relative bg-slate-50">
                {{-- Navbar (Premium & Sticky) --}}
                <nav class="flex items-center px-4 md:px-8 py-4 bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-20">
                    {{-- Mobile Hamburger --}}
                    @if(!in_array(auth()->user()->role, ['admin', 'superadmin']))
                    <button class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-400 hover:text-brand transition-colors mr-4" onclick="toggleSidebar()">
                        <i class="fas fa-bars-staggered"></i>
                    </button>
                    @endif

                    {{-- Page Context --}}
                    <div class="flex-1">
                        @if(in_array(auth()->user()->role, ['user', 'laundry', 'cleaner', 'admin', 'superadmin']))
                            {{-- On mobile, show logo; on desktop, show title text --}}
                            <div class="block lg:hidden">
                                <img src="{{ asset('koskora.png') }}" alt="KosKora" class="h-8 w-auto">
                            </div>
                            <div class="hidden lg:block">
                                <h1 class="text-lg font-semibold text-slate-800 tracking-tight">@yield('header_title', 'Dashboard')</h1>
                            </div>
                        @else
                            <h1 class="text-lg font-semibold text-slate-800 tracking-tight">@yield('header_title', 'Dashboard')</h1>
                        @endif
                    </div>

                    {{-- Top Actions --}}
                    <div class="flex items-center gap-2 sm:gap-4">
                        {{-- Notifications --}}
                        <div class="relative" x-data="{ openNotifications: false }">
                            <button @click="openNotifications = !openNotifications" @click.away="openNotifications = false" class="w-10 h-10 flex items-center justify-center text-brand hover:text-brand-dark transition-all relative group">
                                <i class="far fa-bell text-lg transition-transform group-hover:rotate-12"></i>
                            </button>
                            
                            {{-- Dropdown Notifications --}}
                            <div x-show="openNotifications" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50"
                                style="display: none;">
                                
                                <div class="px-4 py-3 border-b border-slate-50 flex items-center justify-between">
                                    <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                                </div>
                                
                                <div class="p-8 text-center flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-3">
                                        <i class="far fa-bell-slash text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-600 mb-1">Belum ada notifikasi</p>
                                    <p class="text-[11px] text-slate-400">Pemberitahuan dari aplikasi mobile akan muncul di sini.</p>
                                </div>
                                
                                <div class="px-4 py-2 border-t border-slate-50 text-center">
                                    <a href="{{ route('admin.notifications.index') }}" class="text-xs font-bold text-brand hover:underline">Lihat Semua Notifikasi</a>
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Logout for User / Partner roles --}}
                        @if(in_array(auth()->user()->role, ['user', 'laundry', 'cleaner', 'admin', 'superadmin']))
                        <form method="POST" action="{{ route('admin.logout') }}" class="block sm:hidden m-0 p-0">
                            @csrf
                            <button type="button" onclick="event.preventDefault(); window.dispatchEvent(new CustomEvent('open-confirm', { detail: { message: 'Keluar dari aplikasi?', form: this } }));" class="w-10 h-10 rounded-xl flex items-center justify-center text-red-400 hover:bg-red-50 hover:text-red-500 transition-all">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                        @endif

                        {{-- User Quick Access with Dropdown --}}
                        <div class="hidden sm:flex items-center pl-4 border-l border-slate-100 relative" x-data="{ openProfile: false }">
                            <button @click="openProfile = !openProfile" @click.away="openProfile = false" class="flex items-center gap-3 focus:outline-none text-left rounded-xl hover:bg-slate-50 p-1 -mr-1 transition-colors">
                                <div class="text-right">
                                    <div class="text-[12px] font-bold text-slate-800 leading-none capitalize">{{ auth()->user()->name }}</div>
                                    <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mt-1 opacity-80">{{ auth()->user()->role }}</div>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-bold text-xs transition-colors">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="openProfile ? 'rotate-180' : ''"></i>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="openProfile" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50 overflow-hidden"
                                 style="display: none;">
                                
                                <div class="px-4 py-2 border-b border-slate-50 mb-1">
                                    <p class="text-xs text-slate-500">Masuk sebagai</p>
                                    <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                <a href="{{ route('admin.profile.index') }}" class="w-full text-left px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors flex items-center gap-2">
                                    <i class="fas fa-user-circle w-4"></i> Profil Saya
                                </a>

                                <div class="border-t border-slate-100 my-1"></div>

                                <form method="POST" action="{{ route('admin.logout') }}" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors flex items-center gap-2">
                                        <i class="fas fa-sign-out-alt w-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content Ecosystem -->
                <main class="p-4 md:p-8 flex-1 {{ in_array(auth()->user()->role, ['user', 'laundry', 'cleaner', 'admin', 'superadmin']) ? 'pb-24' : '' }}">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- ===== GLOBAL BOTTOM NAV (User Role) ===== --}}
        @if(auth()->user()->role === 'user')
        <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 flex justify-around px-4 pt-2 pb-6 z-50 lg:hidden">
            <a href="#" class="bnav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home-alt bnav-icon"></i>
                <span>Home</span>
            </a>
            <a href="#" class="bnav-item {{ request()->routeIs('rent-payments.my-payments') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar bnav-icon"></i>
                <span>Tagihan</span>
            </a>
            <a href="#" class="bnav-item relative -top-5">
                <div class="w-12 h-12 bg-brand text-white rounded-full flex items-center justify-center text-xl shadow-[0_4px_10px_0_rgba(30,27,155,0.3)] border-[3px] border-slate-50 transition-transform duration-200 hover:scale-105">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <span class="mt-1">Layanan</span>
            </a>
            <a href="#" class="bnav-item {{ request()->routeIs('user.announcements.*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn bnav-icon"></i>
                <span>Info</span>
            </a>
            <a href="#" class="bnav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fas fa-user bnav-icon"></i>
                <span>Profil</span>
            </a>
        </nav>
        @endif

        {{-- ===== BOTTOM NAV: LAUNDRY PARTNER ===== --}}
        @if(auth()->user()->role === 'laundry')
        <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 flex justify-around px-4 pt-2 pb-6 z-50 lg:hidden">
            <a href="#" class="bnav-item {{ request()->routeIs('laundry.orders.*') ? 'active' : '' }}">
                <i class="fas fa-list-check bnav-icon"></i>
                <span>Pesanan</span>
            </a>
            <a href="#" class="bnav-item relative -top-5 {{ request()->routeIs('laundry.services.*') ? 'active' : '' }}">
                <div class="w-12 h-12 bg-brand text-white rounded-full flex items-center justify-center text-xl shadow-[0_4px_10px_0_rgba(30,27,155,0.3)] border-[3px] border-slate-50 transition-transform duration-200 hover:scale-105">
                    <i class="fas fa-soap"></i>
                </div>
                <span class="mt-1">Layanan</span>
            </a>
            <a href="#" class="bnav-item {{ request()->routeIs('laundry.withdrawals.*') ? 'active' : '' }}">
                <i class="fas fa-wallet bnav-icon"></i>
                <span>Saldo</span>
            </a>
        </nav>
        @endif

        {{-- ===== BOTTOM NAV: CLEANER PARTNER ===== --}}
        @if(auth()->user()->role === 'cleaner')
        <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 flex justify-around px-4 pt-2 pb-6 z-50 lg:hidden">
            <a href="#" class="bnav-item {{ request()->routeIs('cleaner.orders.*') ? 'active' : '' }}">
                <i class="fas fa-list-check bnav-icon"></i>
                <span>Tugas</span>
            </a>
            <a href="#" class="bnav-item relative -top-5">
                <div class="w-12 h-12 bg-brand text-white rounded-full flex items-center justify-center text-xl shadow-[0_4px_10px_0_rgba(30,27,155,0.3)] border-[3px] border-slate-50 transition-transform duration-200 hover:scale-105">
                    <i class="fas fa-broom"></i>
                </div>
                <span class="mt-1">Beranda</span>
            </a>
            <a href="#" class="bnav-item {{ request()->routeIs('cleaner.withdrawals.*') ? 'active' : '' }}">
                <i class="fas fa-wallet bnav-icon"></i>
                <span>Saldo</span>
            </a>
        </nav>
        @endif

        {{-- ===== BOTTOM NAV: ADMIN & SUPER ADMIN ===== --}}
        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
        <div x-data="{ openMoreMenu: false }">
            <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 flex justify-around px-4 pt-2 pb-6 z-50 lg:hidden">
                <a href="{{ route('admin.dashboard') }}" class="bnav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home-alt bnav-icon"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="bnav-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open bnav-icon"></i>
                    <span>Kamar</span>
                </a>
                <a href="javascript:void(0)" @click="openMoreMenu = true" class="bnav-item relative -top-5">
                    <div class="w-12 h-12 bg-brand text-white rounded-full flex items-center justify-center text-xl shadow-[0_4px_10px_0_rgba(30,27,155,0.3)] border-[3px] border-slate-50 transition-transform duration-200 hover:scale-105">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <span class="mt-1">Lainnya</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="bnav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-friends bnav-icon"></i>
                    <span>Penyewa</span>
                </a>
                <a href="{{ route('admin.payments.index') }}" class="bnav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card bnav-icon"></i>
                    <span>Bayar</span>
                </a>
            </nav>

            <!-- Bottom Sheet Menu Modal -->
            <div x-show="openMoreMenu" class="fixed inset-0 z-[60] flex items-end justify-center lg:hidden" style="display: none;">
                <!-- Backdrop -->
                <div x-show="openMoreMenu" x-transition.opacity @click="openMoreMenu = false" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
                
                <!-- Bottom Sheet Panel -->
                <div x-show="openMoreMenu" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="translate-y-full"
                     x-transition:enter-end="translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-y-0"
                     x-transition:leave-end="translate-y-full"
                     class="relative w-full max-h-[85vh] overflow-y-auto bg-white rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] pb-8 pt-4 px-6 flex flex-col gap-6">
                    
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto shrink-0 mb-2"></div>
                    
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 text-lg">Menu Lainnya</h3>
                        <button @click="openMoreMenu = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200"><i class="fas fa-times"></i></button>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                        <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center gap-2 text-slate-500 hover:text-brand transition-colors">
                            <div class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-xl"><i class="fas fa-user-cog"></i></div>
                            <span class="text-[10px] font-semibold text-center leading-tight">Pengguna</span>
                        </a>
                        <a href="{{ route('admin.properties.index') }}" class="flex flex-col items-center gap-2 text-slate-500 hover:text-brand transition-colors">
                            <div class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-xl"><i class="fas fa-building"></i></div>
                            <span class="text-[10px] font-semibold text-center leading-tight">Properti</span>
                        </a>
                        <a href="{{ route('admin.facilities.index') }}" class="flex flex-col items-center gap-2 text-slate-500 hover:text-brand transition-colors">
                            <div class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-xl"><i class="fas fa-concierge-bell"></i></div>
                            <span class="text-[10px] font-semibold text-center leading-tight">Fasilitas</span>
                        </a>
                        <a href="{{ route('admin.room-rentals.index') }}" class="flex flex-col items-center gap-2 text-slate-500 hover:text-brand transition-colors">
                            <div class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-xl"><i class="fas fa-tags"></i></div>
                            <span class="text-[10px] font-semibold text-center leading-tight">Tipe Harga</span>
                        </a>
                        <a href="{{ route('admin.rentals.index') }}" class="flex flex-col items-center gap-2 text-slate-500 hover:text-brand transition-colors">
                            <div class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-xl"><i class="fas fa-file-signature"></i></div>
                            <span class="text-[10px] font-semibold text-center leading-tight">Kontrak</span>
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="flex flex-col items-center gap-2 text-slate-500 hover:text-brand transition-colors">
                            <div class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-xl"><i class="fas fa-file-invoice-dollar"></i></div>
                            <span class="text-[10px] font-semibold text-center leading-tight">Riwayat Bayar</span>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center gap-2 text-slate-500 hover:text-brand transition-colors">
                            <div class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-xl"><i class="fas fa-print"></i></div>
                            <span class="text-[10px] font-semibold text-center leading-tight">Laporan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Layout Interaction Scripts -->
        <script>
            function toggleSidebar() {
                const sb = document.getElementById('sidebar');
                const ov = document.getElementById('sidebarOverlay');
                if (sb) {
                    sb.classList.toggle('-translate-x-full');
                }
                if (ov) {
                    ov.classList.toggle('opacity-0');
                    ov.classList.toggle('pointer-events-none');
                }
            }
            function closeSidebar() {
                const sb = document.getElementById('sidebar');
                const ov = document.getElementById('sidebarOverlay');
                if (sb && !sb.classList.contains('-translate-x-full')) {
                    sb.classList.add('-translate-x-full');
                }
                if (ov && !ov.classList.contains('opacity-0')) {
                    ov.classList.add('opacity-0');
                    ov.classList.add('pointer-events-none');
                }
            }
        </script>
        
        <!-- Global Confirm Modal -->
        <div x-data="{ 
                show: false, 
                message: '', 
                form: null 
             }" 
             @open-confirm.window="show = true; message = $event.detail.message; form = $event.detail.form"
             x-show="show" 
             style="display: none;" 
             class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-0">
            <!-- Backdrop -->
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="show = false"></div>

            <!-- Modal -->
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center flex flex-col p-6">
                <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 border-4 border-red-100">
                    <i class="fas fa-exclamation"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Konfirmasi</h3>
                <p class="text-slate-500 mb-6" x-text="message"></p>
                <div class="flex items-center justify-center gap-3 w-full">
                    <button type="button" @click="show = false" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5 py-2.5 text-sm font-semibold flex-1 transition-colors flex justify-center items-center">Batal</button>
                    <button type="button" @click="form.submit ? form.submit() : (form.closest('form') ? form.closest('form').submit() : null)" class="bg-[#d82a2a] text-white hover:bg-red-700 rounded-xl px-5 py-2.5 text-sm font-semibold flex-1 transition-colors flex justify-center items-center">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>

        <!-- Global Toasts -->
        <div class="fixed top-4 right-4 sm:top-6 sm:right-6 z-[250] flex flex-col gap-3 pointer-events-none w-[320px] max-w-[calc(100vw-32px)]">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="pointer-events-auto w-full bg-white rounded-xl shadow-xl shadow-emerald-500/5 border border-emerald-100 p-3 flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <div class="flex-1 mt-1.5">
                    <p class="text-[13px] font-bold text-slate-800 leading-snug">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition-colors mt-1.5 shrink-0">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="pointer-events-auto w-full bg-white rounded-xl shadow-xl shadow-rose-500/5 border border-rose-100 p-3 flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation text-sm"></i>
                </div>
                <div class="flex-1 mt-1.5">
                    <p class="text-[13px] font-bold text-slate-800 leading-snug">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition-colors mt-1.5 shrink-0">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            @endif
        </div>

        @stack('scripts')
    </body>
</html>
