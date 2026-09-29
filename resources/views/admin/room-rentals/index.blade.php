<x-app-layout>
    @section('header_title', 'Room Rentals Management')

    <div class="space-y-6 animate-fade-in" x-data="{ showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Tipe Harga Sewa Kamar</span>
            </nav>
        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.room-rentals.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari tipe sewa..."
                                   style="padding-left:2.5rem; width:100%; margin:0;" class="form-input">
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                            <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search']))
                            <a href="{{ route('admin.room-rentals.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <button type="button" @click="showCreateModal = true" class="btn btn-primary flex-shrink-0">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah Tipe Sewa
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto border-0 rounded-none shadow-none w-full">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4"><x-sortable column="room_number" label="Info Kamar" /></th>
                        <th class="px-6 py-4">Daftar Tipe Sewa & Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rooms as $room)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 align-top pt-5 w-1/3">
                                <div class="font-semibold text-slate-900 text-base">Kamar #{{ $room->room_number }}</div>
                                <div class="text-xs font-medium text-slate-500 mt-1"><i class="fas fa-building mr-1"></i> {{ $room->property->name ?? 'Properti Tidak Diketahui' }}</div>
                                <div class="text-[11px] font-semibold text-brand mt-1">Lantai {{ $room->floor }}</div>
                            </td>
                            <td class="px-6 py-4 pt-4 pb-4">
                                <div class="space-y-3">
                                    @foreach($room->roomRentals as $rental)
                                        <div x-data="{ showEditModal: {{ $errors->any() && old('_method') == 'PUT' && old('id') == $rental->id ? 'true' : 'false' }} }" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-xl transition-all group/rental">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-brand/5 flex items-center justify-center text-brand">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-800 capitalize">{{ $rental->rental_type }}</div>
                                                    <div class="text-xs text-slate-500 font-medium mt-0.5">Rp {{ number_format($rental->price, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-2 opacity-0 group-hover/rental:opacity-100 focus-within:opacity-100 transition-all">
                                                <button type="button" @click="showEditModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-brand hover:bg-slate-50 transition-all">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.room-rentals.destroy', $rental->id) }}" method="POST" class="m-0">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="event.preventDefault(); window.dispatchEvent(new CustomEvent('open-confirm', { detail: { message: 'Hapus harga {{ $rental->rental_type }} untuk kamar ini?', form: this } }));" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Edit Modal -->
                                            <template x-teleport="body">
                                                <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0 text-left whitespace-normal">
                                                <div x-show="showEditModal" 
                                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                                     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showEditModal = false"></div>
                                                <div x-show="showEditModal"
                                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                     class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden text-left flex flex-col max-h-[90vh]">
                                                    <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0">
                                                        <h3 class="text-[17px] font-bold text-slate-800">Ubah Tipe Sewa</h3>
                                                    </div>
                                                    <form action="{{ route('admin.room-rentals.update', $rental->id) }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $rental->id }}">
                                                        
                                                        <div class="space-y-1.5">
                                                            <label class="block text-sm font-semibold text-slate-700">Kamar <span class="text-rose-500">*</span></label>
                                                            <select name="room_id" class="form-input w-full rounded-xl" required>
                                                                @php $currRoom = old('id') == $rental->id ? old('room_id') : $rental->room_id; @endphp
                                                                @foreach($allRooms as $allRoom)
                                                                    <option value="{{ $allRoom->id }}" {{ $currRoom == $allRoom->id ? 'selected' : '' }}>
                                                                        Kamar #{{ $allRoom->room_number }} - {{ $allRoom->property->name ?? 'KosKora' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="space-y-1.5">
                                                            <label class="block text-sm font-semibold text-slate-700">Tipe Sewa <span class="text-rose-500">*</span></label>
                                                            <select name="rental_type" class="form-input w-full rounded-xl" required>
                                                                @php $currType = old('id') == $rental->id ? old('rental_type') : $rental->rental_type; @endphp
                                                                <option value="harian" {{ $currType == 'harian' ? 'selected' : '' }}>Harian</option>
                                                                <option value="mingguan" {{ $currType == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                                                <option value="bulanan" {{ $currType == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                                <option value="tahunan" {{ $currType == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                                            </select>
                                                        </div>
                                                        <div class="space-y-1.5">
                                                            <label class="block text-sm font-semibold text-slate-700">Harga (Rp) <span class="text-rose-500">*</span></label>
                                                            <input type="number" name="price" value="{{ old('id') == $rental->id ? old('price') : (float)$rental->price }}" class="form-input w-full rounded-xl" required min="0" step="0.01">
                                                        </div>
                                                        
                                                        <div class="pt-2 pb-2 flex items-center justify-end gap-3">
                                                            <button type="button" @click="showEditModal = false" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5">Batal</button>
                                                            <button type="submit" class="btn bg-brand text-white hover:bg-brand-dark rounded-xl px-5">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </template>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">
                                <div class="empty-state text-center py-10">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-3">
                                        <i class="fas fa-tags text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada pengaturan sewa</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
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
             class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden text-left flex flex-col max-h-[90vh]">
            <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0">
                <h3 class="text-[17px] font-bold text-slate-800">Tambah Tipe Sewa Kamar</h3>
            </div>
            <form action="{{ route('admin.room-rentals.store') }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-slate-700">Kamar <span class="text-rose-500">*</span></label>
                    <select name="room_id" class="form-input w-full rounded-xl" required>
                        <option value="" disabled selected>Pilih Kamar</option>
                        @foreach($allRooms as $room)
                            <option value="{{ $room->id }}" {{ (!old('id') && old('room_id') == $room->id) ? 'selected' : '' }}>
                                Kamar #{{ $room->room_number }} - {{ $room->property->name ?? 'KosKora' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-slate-700">Tipe Sewa <span class="text-rose-500">*</span></label>
                    <select name="rental_type" class="form-input w-full rounded-xl" required>
                        <option value="" disabled selected>Pilih Tipe</option>
                        <option value="harian" {{ (!old('id') && old('rental_type') == 'harian') ? 'selected' : '' }}>Harian</option>
                        <option value="mingguan" {{ (!old('id') && old('rental_type') == 'mingguan') ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulanan" {{ (!old('id') && old('rental_type') == 'bulanan') ? 'selected' : '' }}>Bulanan</option>
                        <option value="tahunan" {{ (!old('id') && old('rental_type') == 'tahunan') ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-slate-700">Harga (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price" value="{{ !old('id') ? old('price') : '' }}" class="form-input w-full rounded-xl" required min="0" step="0.01">
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

