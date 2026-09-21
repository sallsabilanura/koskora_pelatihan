<x-app-layout>
    @section('header_title', 'Dashboard Overview')

    <div class="space-y-8 animate-fade-in">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Rooms -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500 mb-1">Total Rooms</div>
                        <div class="text-3xl font-semibold text-slate-900 leading-tight tracking-tight">{{ $totalRooms ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-brand-light text-brand flex items-center justify-center text-xl transition-transform group-hover:rotate-6">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                    <span class="text-emerald-500 mr-1"><i class="fas fa-arrow-up mr-1"></i>Updated</span>
                    just now
                </div>
            </div>

            <!-- Available Rooms -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500 mb-1">Available</div>
                        <div class="text-3xl font-semibold text-emerald-600 leading-tight tracking-tight">{{ $availableRooms ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl transition-transform group-hover:rotate-6">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                    Ready to occupied
                </div>
            </div>

            <!-- Total Tenants -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500 mb-1">Active Tenants</div>
                        <div class="text-3xl font-semibold text-slate-900 leading-tight tracking-tight">{{ $totalTenants ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl transition-transform group-hover:rotate-6">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                    Registered residents
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500 mb-1">Total Revenue</div>
                        <div class="text-3xl font-semibold text-brand leading-tight tracking-tight">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-brand-light text-brand flex items-center justify-center text-xl transition-transform group-hover:rotate-6">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                    Monthly income
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Recent Payments</h3>
                    <a href="#" class="text-sm font-bold text-brand hover:underline">View Analytics</a>
                </div>
                
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                    <th class="px-6 py-3.5">Tenant</th>
                                    <th class="px-6 py-3.5">Room</th>
                                    <th class="px-6 py-3.5">Date</th>
                                    <th class="px-6 py-3.5">Amount</th>
                                    <th class="px-6 py-3.5">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments ?? [] as $payment)
                                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-b-0">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                    {{ strtoupper(substr($payment->tenants->name, 0, 2)) }}
                                                </div>
                                                <span class="font-semibold text-slate-700">{{ $payment->tenants->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><span class="font-medium text-slate-500">{{ $payment->room->room_number }}</span></td>
                                        <td class="px-6 py-4"><span class="text-slate-400 text-sm">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</span></td>
                                        <td class="px-6 py-4"><span class="font-bold text-slate-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold tracking-wide capitalize {{ $payment->status === 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                                {{ $payment->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12 text-slate-400 italic">No payment history found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Announcements / Quick Actions -->
            <div class="space-y-4">
                <div class="px-2">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">System Info</h3>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative transition-all duration-200 hover:shadow-md hover:-translate-y-[1px] overflow-hidden">
                    <div class="relative z-10">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Active Broadcasts</div>
                        <div class="text-3xl font-extrabold text-slate-800 mb-4">{{ $announcementsCount ?? 0 }}</div>
                        <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-light hover:bg-brand/10 rounded-xl text-sm font-bold text-brand transition-colors">
                            Manage Info <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                    {{-- Decorative Circle --}}
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-brand-light/40 rounded-full blur-2xl"></div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm border-dashed border-2 flex items-center justify-center py-10 group cursor-pointer hover:bg-slate-50 transition-colors">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 transition-transform group-hover:scale-110">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="text-sm font-bold text-slate-600">Quick Property Add</div>
                        <div class="text-[11px] text-slate-400">Add new room or tenant</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
