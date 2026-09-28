<x-app-layout>
    @section('header_title', 'Rooms Management')

    <div class="space-y-6 animate-fade-in" x-data="{ showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Manajemen Kamar</span>
            </nav>
        </div>

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

                <button type="button" @click="showCreateModal = true" class="btn btn-primary flex-shrink-0">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah Kamar
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto border-0 rounded-none shadow-none w-full">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 w-1/3"><x-sortable column="room_number" label="Info Kamar" /></th>
                        <th class="px-6 py-4 w-1/6"><x-sortable column="category" label="Kategori" /></th>
                        <th class="px-6 py-4 w-1/4"><x-sortable column="price" label="Harga Sewa" /></th>
                        <th class="px-6 py-4 text-center w-1/6"><x-sortable column="status" label="Status" /></th>
                        <th class="px-6 py-4 text-right w-1/12">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rooms as $room)
                        <tr class="hover:bg-slate-50 transition-colors" x-data="{ showEditModal: {{ $errors->any() && old('_method') == 'PUT' && old('id') == $room->id ? 'true' : 'false' }}, showDetailModal: false }">
                            <td class="px-6 py-4" data-label="Kamar">
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
                            <td class="px-6 py-4" data-label="Kategori">
                                <div class="text-sm font-medium text-slate-700">{{ $room->room_type ?: 'Standar' }}</div>
                                @if(isset($room->gender_target) && $room->gender_target)
                                    <span class="badge {{ $room->gender_target == 'putri' ? 'badge-red' : ($room->gender_target == 'putra' ? 'badge-blue' : 'badge-gray') }} mt-1">
                                        {{ ucfirst($room->gender_target) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4" data-label="Harga">
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
                            <td class="px-6 py-4 text-center" data-label="Status">
                                @php
                                    $badgeCls = [
                                        'available' => 'badge-green',
                                        'occupied' => 'badge-red',
                                        'maintenance' => 'badge-amber',
                                    ][$room->status ?? ''] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $badgeCls }}">{{ strtoupper($room->status ?? 'unknown') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right" data-label="Aksi">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="showDetailModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-brand transition-colors" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" @click="showEditModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-brand transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.rooms.destroy', $room->id ?? 1) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kamar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            </td>
                            <!-- Detail Modal -->
                            <td class="p-0 border-0">
                                <template x-teleport="body">
                                    <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
                                        <div x-show="showDetailModal" 
                                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showDetailModal = false"></div>
                                        <div x-show="showDetailModal"
                                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                             class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden text-left flex flex-col max-h-[90vh]">
                                            <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0 border-b border-slate-100">
                                                <h3 class="text-[17px] font-bold text-slate-800">Detail Kamar #{{ $room->room_number }}</h3>
                                                <button @click="showDetailModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                                                    <i class="fas fa-times text-lg"></i>
                                                </button>
                                            </div>
                                            <div class="px-6 pt-2 pb-6 space-y-6 overflow-y-auto">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Properti</div>
                                                        <div class="text-sm font-medium text-slate-700">{{ $room->property->name ?? '-' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tipe Kamar</div>
                                                        <div class="text-sm font-medium text-slate-700">{{ $room->room_type ?? '-' }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Lantai</div>
                                                        <div class="text-sm font-medium text-slate-700">{{ $room->floor }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Target Penyewa</div>
                                                        <div class="text-sm font-medium text-slate-700 capitalize">{{ $room->gender_target ?? 'Campur' }}</div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Fasilitas Kamar</div>
                                                    <div class="flex flex-wrap gap-2">
                                                        @forelse($room->facilities as $facility)
                                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                                                <i class="fas {{ $facility->icon ?? 'fa-check' }} text-brand"></i> {{ $facility->name }}
                                                            </span>
                                                        @empty
                                                            <span class="text-sm text-slate-500 italic">Tidak ada fasilitas terdaftar.</span>
                                                        @endforelse
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Harga Sewa Aktif</div>
                                                    <div class="space-y-2">
                                                        @forelse($room->roomRentals as $rentalPrice)
                                                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50">
                                                                <div class="text-sm font-semibold text-slate-700 capitalize">{{ $rentalPrice->rental_type }}</div>
                                                                <div class="text-sm font-bold text-brand">Rp {{ number_format($rentalPrice->price, 0, ',', '.') }}</div>
                                                            </div>
                                                        @empty
                                                            <p class="text-sm text-slate-500 italic">Belum ada pengaturan harga sewa.</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border-t border-slate-100 flex justify-end">
                                                <button type="button" @click="showDetailModal = false" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </td>
                            <!-- Edit Modal -->
                            <td class="p-0 border-0">
                                <template x-teleport="body">
                                    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
                                    <div x-show="showEditModal" 
                                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showEditModal = false"></div>
                                    <div x-show="showEditModal"
                                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden text-left flex flex-col max-h-[90vh]">
                                        <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0">
                                            <h3 class="text-[17px] font-bold text-slate-800">Ubah Kamar</h3>
                                        </div>
                                        <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $room->id }}">
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Nomor Kamar <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="room_number" value="{{ old('id') == $room->id ? old('room_number') : $room->room_number }}" class="form-input w-full rounded-xl" required>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Lantai <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="floor" value="{{ old('id') == $room->id ? old('floor') : $room->floor }}" class="form-input w-full rounded-xl" required>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Properti <span class="text-rose-500">*</span></label>
                                                    <select name="properties_id" class="form-input w-full rounded-xl" required>
                                                        @php $currProp = old('id') == $room->id ? old('properties_id') : $room->properties_id; @endphp
                                                        @foreach($properties as $property)
                                                            <option value="{{ $property->id }}" {{ $currProp == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Status <span class="text-rose-500">*</span></label>
                                                    <select name="status" class="form-input w-full rounded-xl" required>
                                                        @php $currStatus = old('id') == $room->id ? old('status') : $room->status; @endphp
                                                        <option value="available" {{ $currStatus == 'available' ? 'selected' : '' }}>Available</option>
                                                        <option value="occupied" {{ $currStatus == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                                        <option value="maintenance" {{ $currStatus == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                    </select>
                                                </div>
                                                <div class="space-y-3 pt-2 md:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700">Fasilitas Kamar</label>
                                                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                                        @php
                                                            $currFacs = old('id') == $room->id ? old('facilities', []) : $room->facilities->pluck('id')->toArray();
                                                        @endphp
                                                        @foreach($facilities as $facility)
                                                            <label class="cursor-pointer relative group">
                                                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="peer sr-only" {{ in_array($facility->id, $currFacs) ? 'checked' : '' }}>
                                                                <div class="rounded-xl border border-slate-200 p-3 flex flex-col items-center justify-center gap-2 hover:bg-slate-50 peer-checked:border-brand peer-checked:bg-brand/5 peer-checked:text-brand transition-all text-slate-500 min-h-[80px]">
                                                                    <i class="{{ $facility->icon ?? 'fas fa-check' }} text-xl mb-1"></i>
                                                                    <span class="text-xs font-medium text-center leading-tight">{{ $facility->name }}</span>
                                                                </div>
                                                                <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-brand transition-opacity">
                                                                    <i class="fas fa-check-circle text-sm"></i>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="pt-2 pb-2 flex items-center justify-end gap-3">
                                                <button type="button" @click="showEditModal = false" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5">Batal</button>
                                                <button type="submit" class="btn bg-brand text-white hover:bg-brand-dark rounded-xl px-5">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </template>
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

    <!-- Create Modal -->
    <template x-teleport="body">
        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
        <div x-show="showCreateModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showCreateModal = false"></div>
        <div x-show="showCreateModal"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden text-left flex flex-col max-h-[90vh]">
            <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0">
                <h3 class="text-[17px] font-bold text-slate-800">Tambah Kamar Baru</h3>
            </div>
            <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Nomor Kamar <span class="text-rose-500">*</span></label>
                        <input type="text" name="room_number" value="{{ !old('id') ? old('room_number') : '' }}" class="form-input w-full rounded-xl" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Lantai <span class="text-rose-500">*</span></label>
                        <input type="text" name="floor" value="{{ !old('id') ? old('floor') : '' }}" class="form-input w-full rounded-xl" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Properti <span class="text-rose-500">*</span></label>
                        <select name="properties_id" class="form-input w-full rounded-xl" required>
                            <option value="" disabled selected>Pilih Properti</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" {{ (!old('id') && old('properties_id') == $property->id) ? 'selected' : '' }}>{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="form-input w-full rounded-xl" required>
                            <option value="available" {{ (!old('id') && old('status') == 'available') ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ (!old('id') && old('status') == 'occupied') ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ (!old('id') && old('status') == 'maintenance') ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="space-y-3 pt-2 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Fasilitas Kamar</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                            @foreach($facilities as $facility)
                                <label class="cursor-pointer relative group">
                                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="peer sr-only" {{ (!old('id') && in_array($facility->id, old('facilities', []))) ? 'checked' : '' }}>
                                    <div class="rounded-xl border border-slate-200 p-3 flex flex-col items-center justify-center gap-2 hover:bg-slate-50 peer-checked:border-brand peer-checked:bg-brand/5 peer-checked:text-brand transition-all text-slate-500 min-h-[80px]">
                                        <i class="{{ $facility->icon ?? 'fas fa-check' }} text-xl mb-1"></i>
                                        <span class="text-xs font-medium text-center leading-tight">{{ $facility->name }}</span>
                                    </div>
                                    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-brand transition-opacity">
                                        <i class="fas fa-check-circle text-sm"></i>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                </div>
                <div class="pt-2 pb-2 flex items-center justify-end gap-3">
                    <button type="button" @click="showCreateModal = false" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5">Batal</button>
                    <button type="submit" class="btn bg-brand text-white hover:bg-brand-dark rounded-xl px-5">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    </template>
    </div>
</x-app-layout>
