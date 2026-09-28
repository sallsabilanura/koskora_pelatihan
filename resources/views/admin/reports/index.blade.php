<x-app-layout>
    @section('header_title', 'Pusat Laporan')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Pusat Laporan</span>
            </nav>
        </div>

        <div class="max-w-3xl mx-auto space-y-6 mt-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
            <div class="w-16 h-16 bg-brand/10 text-brand rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fas fa-file-pdf"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight mb-2">Cetak Laporan Keuangan</h2>
            <p class="text-slate-500 mb-8 max-w-md mx-auto">Pilih bulan dan tahun untuk menghasilkan laporan rekapitulasi pembayaran uang kos secara otomatis. Laporan siap cetak dalam format kertas A4.</p>

            <form action="{{ route('admin.reports.print') }}" method="GET" target="_blank" class="flex flex-col sm:flex-row items-center justify-center gap-3 max-w-md mx-auto">
                <div class="flex-1 w-full">
                    <input type="month" name="month" value="{{ date('Y-m') }}" class="form-input w-full rounded-xl text-center font-medium h-[42px]" required>
                </div>
                <button type="submit" class="btn btn-primary bg-brand text-white hover:bg-brand-dark px-6 rounded-xl font-medium w-full sm:w-auto shadow-md h-[42px] whitespace-nowrap">
                    <i class="fas fa-print mr-2"></i> Buat Laporan
                </button>
            </form>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 opacity-60 pointer-events-none">
            <!-- Placeholder for future reports -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
                <i class="fas fa-users text-2xl text-slate-300 mb-3"></i>
                <h3 class="font-bold text-slate-600">Laporan Penghuni</h3>
                <p class="text-xs text-slate-400 mt-1">Segera Hadir</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
                <i class="fas fa-door-open text-2xl text-slate-300 mb-3"></i>
                <h3 class="font-bold text-slate-600">Laporan Kamar</h3>
                <p class="text-xs text-slate-400 mt-1">Segera Hadir</p>
            </div>
        </div>
    </div>
</x-app-layout>
