<x-app-layout>
    @section('header_title', 'Konfigurasi Website')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Konfigurasi Website</span>
            </nav>
        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white text-slate-800">
                <div>
                    <h2 class="text-lg font-semibold">System & Security Configuration</h2>
                    <p class="text-sm text-slate-500 mt-1">Pantau log keamanan, akses sistem, dan aktivitas krusial aplikasi.</p>
                </div>
                <div class="text-emerald-500 bg-emerald-50 px-3 py-1.5 rounded-lg text-sm font-medium border border-emerald-100">
                    <i class="fas fa-shield-check mr-1.5"></i> System Secure
                </div>
            </div>

            <div class="px-6 pt-2 pb-6 space-y-6">
                {{-- Security Log Display (Read Only) --}}
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-base font-semibold text-slate-800 flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-brand/10 text-brand flex items-center justify-center mr-3">
                                <i class="fas fa-list-ul"></i>
                            </div>
                            Log Keamanan & Aktivitas Website
                        </h3>
                        <span class="text-xs font-semibold bg-brand text-white px-3 py-1.5 rounded-lg shadow-sm">Live Monitor</span>
                    </div>
                    
                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Aktivitas</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td data-label="Waktu" class="text-slate-500 font-medium">Baru saja</td>
                                    <td data-label="User">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs border border-slate-200">S</div>
                                            <span class="font-semibold text-slate-800">Super Admin</span>
                                        </div>
                                    </td>
                                    <td data-label="Aktivitas" class="text-slate-600"><span class="font-semibold">Berhasil login</span> ke dalam sistem.</td>
                                    <td data-label="IP Address" class="font-mono text-slate-400 text-xs">127.0.0.1</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td data-label="Waktu" class="text-slate-500 font-medium">15 menit yang lalu</td>
                                    <td data-label="User">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs border border-slate-200"><i class="fas fa-user-secret"></i></div>
                                            <span class="font-semibold text-slate-800">Unknown</span>
                                        </div>
                                    </td>
                                    <td data-label="Aktivitas" class="text-slate-600"><span class="font-semibold">Gagal login</span> (Password salah). Diblokir sementara.</td>
                                    <td data-label="IP Address" class="font-mono text-slate-400 text-xs">192.168.1.14</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td data-label="Waktu" class="text-slate-500 font-medium">1 jam yang lalu</td>
                                    <td data-label="User">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs border border-slate-200">S</div>
                                            <span class="font-semibold text-slate-800">Super Admin</span>
                                        </div>
                                    </td>
                                    <td data-label="Aktivitas" class="text-slate-600">Menambahkan kamar baru <span class="font-semibold text-slate-800">#A02</span>.</td>
                                    <td data-label="IP Address" class="font-mono text-slate-400 text-xs">127.0.0.1</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td data-label="Waktu" class="text-slate-500 font-medium">3 jam yang lalu</td>
                                    <td data-label="User">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs border border-slate-200">S</div>
                                            <span class="font-semibold text-slate-800">Super Admin</span>
                                        </div>
                                    </td>
                                    <td data-label="Aktivitas" class="text-slate-600">Mencatat pembayaran sewa sebesar <span class="font-semibold text-slate-800">Rp 2.000.000</span>.</td>
                                    <td data-label="IP Address" class="font-mono text-slate-400 text-xs">127.0.0.1</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td data-label="Waktu" class="text-slate-500 font-medium">Kemarin, 14:30</td>
                                    <td data-label="User">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs border border-slate-200">A</div>
                                            <span class="font-semibold text-slate-800">Admin Kos</span>
                                        </div>
                                    </td>
                                    <td data-label="Aktivitas" class="text-slate-600">Mencetak Laporan Keuangan bulan <span class="font-semibold text-slate-800">September 2026</span>.</td>
                                    <td data-label="IP Address" class="font-mono text-slate-400 text-xs">114.122.10.45</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
