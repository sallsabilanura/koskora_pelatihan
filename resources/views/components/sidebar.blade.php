<div id="sidebar" class="w-[260px] h-screen sticky top-0 z-40 transition-transform duration-300 ease-in-out max-lg:fixed max-lg:-translate-x-full flex flex-col text-slate-500 bg-white border-r border-slate-200">
    <div class="p-6 pb-4 flex items-center justify-center border-b border-slate-100 shrink-0">
        <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-10 w-auto">
    </div>
    
    <div class="flex-1 overflow-y-auto pb-6">
        <div class="px-4 mt-4">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2 px-3">Menu Utama</div>
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-home-alt w-5 text-center text-[15px]"></i>
                    <span>Dashboard</span>
                </a>
            </nav>
        </div>

        <div class="px-4 mt-6">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2 px-3">Data Master Kos</div>
            <nav class="space-y-1">
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.users.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-user-cog w-5 text-center text-[15px]"></i>
                    <span>Pengguna Akun</span>
                </a>
                
                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.properties.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-building w-5 text-center text-[15px]"></i>
                    <span>Properti</span>
                </a>
                
                <a href="{{ route('admin.facilities.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.facilities.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-concierge-bell w-5 text-center text-[15px]"></i>
                    <span>Fasilitas</span>
                </a>

                <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.rooms.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-door-open w-5 text-center text-[15px]"></i>
                    <span>Kamar</span>
                </a>
                
                <a href="{{ route('admin.room-rentals.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.room-rentals.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-tags w-5 text-center text-[15px]"></i>
                    <span>Tipe Harga Sewa</span>
                </a>
            </nav>
        </div>
        
        <div class="px-4 mt-6">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2 px-3">Operasional & Transaksi</div>
            <nav class="space-y-1">
                <a href="{{ route('admin.tenants.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.tenants.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-users w-5 text-center text-[15px]"></i>
                    <span>Data Penyewa</span>
                </a>

                <a href="{{ route('admin.rentals.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.rentals.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-file-signature w-5 text-center text-[15px]"></i>
                    <span>Kontrak Sewa</span>
                </a>

                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.payments.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-file-invoice-dollar w-5 text-center text-[15px]"></i>
                    <span>Pembayaran</span>
                </a>
                
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.reports.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-print w-5 text-center text-[15px]"></i>
                    <span>Cetak Laporan</span>
                </a>
            </nav>
        </div>
        
        {{-- Dimatikan sementara sesuai permintaan
        <div class="px-4 mt-6">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2 px-3">Sistem</div>
            <nav class="space-y-1">
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.settings.*') ? 'bg-brand/5 text-brand border border-brand/10' : 'text-slate-500 hover:bg-slate-50 hover:text-brand' }} rounded-lg font-medium text-sm transition-all">
                    <i class="fas fa-cogs w-5 text-center text-[15px]"></i>
                    <span>Konfigurasi Website</span>
                </a>
            </nav>
        </div>
        --}}
    </div>
    
</div>
