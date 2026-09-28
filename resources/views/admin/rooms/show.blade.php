<x-app-layout>
    @section('header_title', 'Detail Kamar')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.rooms.index') }}" class="text-sm font-semibold text-slate-500 hover:text-brand transition-colors flex items-center gap-2 mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kamar
            </a>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Kamar {{ $room->room_number }}</h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-primary bg-brand text-white hover:bg-brand-dark px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                <i class="fas fa-edit mr-2"></i> Edit Kamar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Informasi Kamar</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nomor Kamar</div>
                        <div class="text-base font-bold text-slate-800">{{ $room->room_number }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Properti</div>
                        <div class="text-base font-medium text-slate-700">{{ $room->property->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tipe Kamar</div>
                        <div class="text-base font-medium text-slate-700">{{ $room->room_type ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Lantai</div>
                        <div class="text-base font-medium text-slate-700">{{ $room->floor }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Target Penyewa</div>
                        <div class="text-base font-medium text-slate-700 capitalize">{{ $room->gender_target ?? 'Campur' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Status</div>
                        <div class="mt-1">
                            @if($room->status === 'available')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">Tersedia</span>
                            @elseif($room->status === 'occupied')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600">Disewa</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600">Perbaikan</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Fasilitas Kamar</h3>
                </div>
                <div class="p-6">
                    @if($room->facilities && $room->facilities->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($room->facilities as $facility)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                    <i class="fas {{ $facility->icon ?? 'fa-check' }} text-brand text-xs"></i> {{ $facility->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic">Belum ada fasilitas yang ditambahkan ke kamar ini.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Harga Sewa Aktif</h3>
                </div>
                <div class="p-6">
                    @if($room->roomRentals && $room->roomRentals->count() > 0)
                        <div class="space-y-3">
                            @foreach($room->roomRentals as $rentalPrice)
                                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50">
                                    <div class="text-sm font-semibold text-slate-700 capitalize">{{ $rentalPrice->rental_type }}</div>
                                    <div class="text-sm font-bold text-brand">Rp {{ number_format($rentalPrice->price, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic">Belum ada pengaturan harga sewa.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
