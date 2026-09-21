<x-app-layout>
    @section('header_title', 'Rooms Management')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== BREADCRUMB ===== --}}
        <nav class="flex text-sm text-slate-500 items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <span><i class="fas fa-chevron-right text-xs"></i></span>
            <span class="text-slate-900 font-medium">Manajemen Kamar</span>
        </nav>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.rooms.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        {{-- Search --}}
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nomor, properti..."
                                   style="padding-left:2.5rem; width:100%; margin:0;" class="form-input">
                        </div>
                        {{-- District --}}
                        <select name="district" onchange="this.form.submit()" style="width:160px; flex-shrink:0; margin:0;" class="form-input">
                            <option value="">Semua Daerah</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->district }}" {{ request('district') == $d->district ? 'selected' : '' }}>
                                    {{ $d->district }} ({{ $d->count }})
                                </option>
                            @endforeach
                        </select>
                        {{-- Status --}}
                        <select name="status" onchange="this.form.submit()" style="width:140px; flex-shrink:0; margin:0;" class="form-input">
                            <option value="">Semua Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        {{-- Submit --}}
                        <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                            <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'district']))
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary flex-shrink-0">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah Kamar
                </a>
            </div>

            {{-- Table --}}
            <div class="table-wrap border-0 rounded-none shadow-none">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Info Kamar</th>
                        <th>Kategori</th>
                        <th>Harga Sewa</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr class="group">
                            <td data-label="Kamar">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        @if(isset($room->image) && $room->image)
                                            <img src="{{ asset('storage/' . $room->image) }}" class="w-11 h-11 rounded-xl object-cover border border-slate-200 shadow-sm">
                                        @else
                                            <div class="w-11 h-11 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-300">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900 text-sm">#{{ $room->room_number }}</div>
                                        <div class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">{{ $room->property->name ?? 'KosKora Main' }}</div>
                                        <div class="text-[10px] font-semibold text-brand">Lantai {{ $room->floor }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Kategori">
                                <div class="text-sm font-medium text-slate-700">{{ $room->room_type }}</div>
                                @if(isset($room->gender_target) && $room->gender_target)
                                    <span class="badge {{ $room->gender_target == 'putri' ? 'badge-red' : ($room->gender_target == 'putra' ? 'badge-blue' : 'badge-gray') }} mt-1">
                                        {{ ucfirst($room->gender_target) }}
                                    </span>
                                @endif
                            </td>
                            <td data-label="Harga">
                                @if($room->roomRentals->isNotEmpty())
                                    <div class="space-y-1">
                                        @foreach($room->roomRentals as $rental)
                                            <div class="flex items-center text-xs">
                                                <span class="text-slate-500 capitalize w-16">{{ $rental->rental_type }}</span>
                                                <span class="font-semibold text-slate-900 ml-2">Rp {{ number_format($rental->price, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-xs text-slate-400 italic">Harga belum diatur</div>
                                @endif
                            </td>
                            <td class="text-center" data-label="Status">
                                @php
                                    $badgeCls = [
                                        'available' => 'badge-green',
                                        'occupied' => 'badge-red',
                                        'maintenance' => 'badge-amber',
                                    ][$room->status ?? ''] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $badgeCls }}">{{ strtoupper($room->status ?? 'unknown') }}</span>
                            </td>
                            <td class="text-right" data-label="Aksi">
                                <div class="flex items-center justify-end">
                                    <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                        <button @click="open = !open" type="button" class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-300 transition-all focus:outline-none">
                                            <i class="fas fa-ellipsis-v text-xs"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-0 mt-1.5 w-36 rounded-2xl bg-white border border-slate-200 shadow-lg z-50 py-1.5 text-left focus:outline-none"
                                             style="display: none;">
                                             
                                             <a href="{{ route('admin.rooms.show', $room->id ?? 1) }}" class="flex items-center gap-2 px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">
                                                 <i class="fas fa-eye w-4 text-center"></i> Detail
                                             </a>
                                             
                                             <a href="{{ route('admin.rooms.edit', $room->id ?? 1) }}" class="flex items-center gap-2 px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">
                                                 <i class="fas fa-edit w-4 text-center"></i> Ubah
                                             </a>
                                             
                                             <div class="h-px bg-slate-100 my-1"></div>
                                             
                                             <form action="{{ route('admin.rooms.destroy', $room->id ?? 1) }}" method="POST" class="block w-full">
                                                 @csrf @method('DELETE')
                                                 <button type="submit" onclick="return confirm('Hapus unit ini?')" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
                                                     <i class="fas fa-trash-alt w-4 text-center"></i> Hapus
                                                 </button>
                                             </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state text-center py-10">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-3">
                                        <i class="fas fa-door-closed text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada unit kamar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== PAGINATION ===== --}}
        </div>
        
        <div class="flex justify-center p-4 border-t border-slate-100">
            {{ method_exists($rooms, 'links') ? $rooms->appends(request()->query())->links() : '' }}
        </div>
        </div>
    </div>
</x-app-layout>
