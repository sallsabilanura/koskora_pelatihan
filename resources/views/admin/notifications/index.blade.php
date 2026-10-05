<x-app-layout>
    @section('header_title', 'Notifikasi')

    <div class="animate-fade-in space-y-4">
        {{-- BREADCRUMB --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors"><i class="fas fa-home"></i> Dashboard</a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Notifikasi</span>
            </nav>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 text-center flex flex-col items-center justify-center min-h-[400px]">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                <i class="far fa-bell-slash text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Belum ada notifikasi</h3>
            <p class="text-sm text-slate-500 max-w-sm">Pemberitahuan dari aplikasi mobile atau sistem akan muncul di sini.</p>
        </div>
    </div>
</x-app-layout>
