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

        <div class="max-w-5xl mx-auto space-y-6 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Main Active Report (Laporan Keuangan) --}}
                <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-6 md:p-8 flex-1 border-b border-slate-100">
                        <div class="w-14 h-14 bg-brand/10 text-brand rounded-2xl flex items-center justify-center text-2xl mb-6">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800 mb-2">Laporan Keuangan</h2>
                        <p class="text-slate-500 text-sm leading-relaxed max-w-lg">
                            Cetak rekapitulasi data pembayaran uang kos secara otomatis. Data akan diekspor dalam format PDF (A4) yang siap untuk dicetak atau diarsipkan.
                        </p>
                    </div>
                    <div class="p-6 md:p-8 bg-slate-50/50">
                        <form action="{{ route('admin.reports.print') }}" method="GET" target="_blank" class="flex flex-col sm:flex-row gap-3 max-w-lg">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Periode Laporan</label>
                                <input type="month" name="month" value="{{ date('Y-m') }}" class="form-input w-full rounded-xl text-sm h-[42px] border-slate-200 focus:border-brand focus:ring-brand bg-white" required>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="btn bg-brand text-white hover:bg-brand-dark px-6 rounded-xl font-medium h-[42px] shadow-sm flex items-center justify-center gap-2 w-full sm:w-auto">
                                    <i class="fas fa-print"></i> Cetak PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Upcoming Reports Column --}}
                <div class="space-y-6 flex flex-col">
                    {{-- Laporan Penghuni --}}
                    <div class="flex-1 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative flex flex-col justify-center">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-400 uppercase tracking-widest">
                                Segera
                            </span>
                        </div>
                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-300 text-lg mb-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="font-bold text-slate-700 mb-1">Laporan Penghuni</h3>
                        <p class="text-xs text-slate-500">Statistik demografi dan riwayat sewa.</p>
                    </div>

                    {{-- Laporan Kamar --}}
                    <div class="flex-1 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative flex flex-col justify-center">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-400 uppercase tracking-widest">
                                Segera
                            </span>
                        </div>
                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-300 text-lg mb-4">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <h3 class="font-bold text-slate-700 mb-1">Laporan Kamar</h3>
                        <p class="text-xs text-slate-500">Analisis okupansi dan performa.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
