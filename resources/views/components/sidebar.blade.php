<div id="sidebar" class="w-[260px] h-screen sticky top-0 z-40 transition-transform duration-300 ease-in-out max-lg:fixed max-lg:-translate-x-full flex flex-col justify-between text-slate-500 bg-white border-r border-slate-200">
    <div>
        <div class="p-6 pb-4 flex items-center justify-center border-b border-slate-100 mb-2">
            <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-7 w-auto">
        </div>
        
        <div class="px-4 mt-4">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2 px-3">Menu Utama</div>
            
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-home-alt w-5 text-center text-[15px]"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.properties.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-building w-5 text-center text-[15px]"></i>
                    <span>Properti</span>
                </a>

                <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.rooms.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-door-open w-5 text-center text-[15px]"></i>
                    <span>Kamar</span>
                </a>

                <a href="{{ route('admin.tenants.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.tenants.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-users w-5 text-center text-[15px]"></i>
                    <span>Penyewa</span>
                </a>

                <a href="{{ route('admin.rentals.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.rentals.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-file-signature w-5 text-center text-[15px]"></i>
                    <span>Sewa Aktif</span>
                </a>

                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.payments.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-file-invoice-dollar w-5 text-center text-[15px]"></i>
                    <span>Pembayaran</span>
                </a>
            </nav>
        </div>
        
        <div class="px-4 mt-8">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2 px-3">Pengaturan</div>
            <nav class="space-y-1">
                <a href="{{ route('admin.facilities.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.facilities.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-concierge-bell w-5 text-center text-[15px]"></i>
                    <span>Fasilitas</span>
                </a>
                <a href="{{ route('admin.room-rentals.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.room-rentals.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-tags w-5 text-center text-[15px]"></i>
                    <span>Tipe Sewa</span>
                </a>
            </nav>
        </div>
    </div>
    
    <div class="p-4 border-t border-slate-100 mt-auto">
        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white text-slate-500 flex items-center justify-center shadow-sm text-sm border border-slate-200">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="text-[13px] font-semibold text-slate-700">Administrator</div>
                    <div class="text-[11px] text-slate-400">Akses Penuh</div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                @csrf
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin keluar dari halaman Admin?')" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</div>
