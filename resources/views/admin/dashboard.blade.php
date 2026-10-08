<x-app-layout>
    @section('header_title', 'Ringkasan Dashboard')

    <div class="space-y-8 animate-fade-in">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Rooms -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500 mb-1">Total Kamar</div>
                        <div class="text-3xl font-semibold text-slate-900 leading-tight tracking-tight">{{ $totalRooms ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 text-brand flex items-center justify-center text-xl transition-transform group-hover:rotate-6">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                    <span class="text-emerald-500 mr-1"><i class="fas fa-arrow-up mr-1"></i>Diperbarui</span>
                    baru saja
                </div>
            </div>

            <!-- Available Rooms -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500 mb-1">Tersedia</div>
                        <div class="text-3xl font-semibold text-amber-500 leading-tight tracking-tight">{{ $availableRooms ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 text-amber-500 flex items-center justify-center text-xl transition-transform group-hover:rotate-6">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                    Siap dihuni
                </div>
            </div>

            <!-- Total Tenants -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500 mb-1">Penyewa Aktif</div>
                        <div class="text-3xl font-semibold text-slate-900 leading-tight tracking-tight">{{ $totalTenants ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 text-rose-500 flex items-center justify-center text-xl transition-transform group-hover:rotate-6">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                    Penghuni terdaftar
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500 mb-1">Total Pendapatan</div>
                        <div class="text-3xl font-semibold text-brand leading-tight tracking-tight">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="w-12 h-12 text-brand flex items-center justify-center text-xl transition-transform group-hover:rotate-6">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                    Pemasukan bulanan
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="mt-8">
            <!-- Recent Activity -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Pembayaran Terakhir</h3>
                    <a href="{{ route('admin.payments.index') }}" class="text-sm font-bold text-brand hover:underline">Lihat Laporan</a>
                </div>
                
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                    <th class="px-6 py-3.5">Penyewa</th>
                                    <th class="px-6 py-3.5">Kamar</th>
                                    <th class="px-6 py-3.5">Tanggal</th>
                                    <th class="px-6 py-3.5">Jumlah</th>
                                    <th class="px-6 py-3.5">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments ?? [] as $payment)
                                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-b-0">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                    {{ strtoupper(substr($payment->rental->user->name ?? '?', 0, 2)) }}
                                                </div>
                                                <span class="font-semibold text-slate-700">{{ $payment->rental->user->name ?? 'Tidak diketahui' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><span class="font-medium text-slate-500">{{ $payment->rental->roomRental->room->room_number ?? '-' }}</span></td>
                                        <td class="px-6 py-4"><span class="text-slate-400 text-sm">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</span></td>
                                        <td class="px-6 py-4"><span class="font-bold text-slate-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold tracking-wide capitalize {{ $payment->status === 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                                {{ $payment->status === 'paid' ? 'Lunas' : $payment->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12 text-slate-400 italic">Belum ada riwayat pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

          
    </div>
</x-app-layout>
