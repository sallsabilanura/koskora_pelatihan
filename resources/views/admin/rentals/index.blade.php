<x-app-layout>
    @section('header_title', 'Rentals Management')

    <div class="space-y-6 animate-fade-in" x-data="{
        showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        rentalType: '',
        basePrice: 0,
        startDate: '{{ old('start_date', date('Y-m-d')) }}',
        endDate: '{{ old('end_date') }}',
        price: '{{ old('rental_price') }}',
        updateRoom(e) {
            let opt = e.target.options[e.target.selectedIndex];
            if(!opt) return;
            this.rentalType = opt.dataset.type || '';
            this.basePrice = parseFloat(opt.dataset.price || 0);
            this.calc();
        },
        calc() {
            if (!this.startDate || !this.endDate || !this.basePrice) return;
            let s = new Date(this.startDate);
            let e = new Date(this.endDate);
            if (e < s) return;
            let diffDays = Math.ceil(Math.abs(e - s) / (1000 * 60 * 60 * 24));
            let days = diffDays > 0 ? diffDays : 1;
            let m = 1;
            if (this.rentalType === 'harian') m = days;
            else if (this.rentalType === 'mingguan') m = Math.round(days / 7);
            else if (this.rentalType === 'bulanan') m = Math.round(days / 30);
            else if (this.rentalType === 'tahunan') m = Math.round(days / 365);
            if (m === 0) m = 1;
            this.price = this.basePrice * m;
        }
    }">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Manajemen Sewa Aktif</span>
            </nav>
        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.rentals.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari ID Sewa atau nama penyewa..."
                                   style="padding-left:2.5rem; width:100%; margin:0;" class="form-input">
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                            <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search']))
                            <a href="{{ route('admin.rentals.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <button type="button" @click="showCreateModal = true" class="btn btn-primary flex-shrink-0">
                    <i class="fas fa-plus text-sm"></i>
                    Buat Kontrak Sewa
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto border-0 rounded-none shadow-none w-full">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Penyewa & Kamar</th>
                        <th class="px-6 py-4"><x-sortable column="start_date" label="Periode Sewa" /></th>
                        <th class="px-6 py-4"><x-sortable column="total_price" label="Total Harga" /></th>
                        <th class="px-6 py-4 text-center"><x-sortable column="status" label="Status" /></th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rentals as $item)
                        <tr class="hover:bg-slate-50 transition-colors" x-data="{ 
                            showEditModal: {{ $errors->any() && old('_method') == 'PUT' && old('id') == $item->id ? 'true' : 'false' }},
                            eRentalType: '{{ strtolower($item->roomRental->rental_type ?? '') }}',
                            eBasePrice: {{ (float)($item->roomRental->price ?? 0) }},
                            eStartDate: '{{ old('id') == $item->id ? old('start_date') : $item->start_date }}',
                            eEndDate: '{{ old('id') == $item->id ? old('end_date') : $item->end_date }}',
                            ePrice: '{{ old('id') == $item->id ? old('rental_price') : $item->rental_price }}',
                            eUpdateRoom(e) {
                                let opt = e.target.options[e.target.selectedIndex];
                                if(!opt) return;
                                this.eRentalType = opt.dataset.type || '';
                                this.eBasePrice = parseFloat(opt.dataset.price || 0);
                                this.eCalc();
                            },
                            eCalc() {
                                if (!this.eStartDate || !this.eEndDate || !this.eBasePrice) return;
                                let s = new Date(this.eStartDate);
                                let e = new Date(this.eEndDate);
                                if (e < s) return;
                                let diffDays = Math.ceil(Math.abs(e - s) / (1000 * 60 * 60 * 24));
                                let days = diffDays > 0 ? diffDays : 1;
                                let m = 1;
                                if (this.eRentalType === 'harian') m = days;
                                else if (this.eRentalType === 'mingguan') m = Math.round(days / 7);
                                else if (this.eRentalType === 'bulanan') m = Math.round(days / 30);
                                else if (this.eRentalType === 'tahunan') m = Math.round(days / 365);
                                if (m === 0) m = 1;
                                this.ePrice = this.eBasePrice * m;
                            }
                        }">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $item->tenant->user->name ?? 'User Terhapus' }}</div>
                                <div class="text-xs text-slate-500">Kamar #{{ $item->roomRental->room->room_number ?? '?' }} - {{ ucfirst($item->roomRental->rental_type ?? '') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</div>
                                <div class="text-xs text-slate-500">s/d {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-800">Rp {{ number_format($item->rental_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-gray">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="showEditModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-brand transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.rentals.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); window.dispatchEvent(new CustomEvent('open-confirm', { detail: { message: 'Apakah Anda yakin ingin menghapus data sewa ini?', form: this } }));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
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
                                            <h3 class="text-[17px] font-bold text-slate-800">Ubah Kontrak Sewa</h3>
                                        </div>
                                        <form action="{{ route('admin.rentals.update', $item->id) }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-1.5 md:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700">Penyewa <span class="text-rose-500">*</span></label>
                                                    <select name="tenants_id" class="form-input w-full rounded-xl" required>
                                                        @php $currTenant = old('id') == $item->id ? old('tenants_id') : $item->tenants_id; @endphp
                                                        @foreach($tenants as $tenant)
                                                            <option value="{{ $tenant->id }}" {{ $currTenant == $tenant->id ? 'selected' : '' }}>{{ $tenant->user->name ?? 'User' }} ({{ $tenant->phone_number }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="space-y-1.5 md:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700">Tipe Harga & Kamar <span class="text-rose-500">*</span></label>
                                                    <select name="room_rentals_id" class="form-input w-full rounded-xl" @change="eUpdateRoom" required>
                                                        @php $currRoom = old('id') == $item->id ? old('room_rentals_id') : $item->room_rentals_id; @endphp
                                                        @foreach($roomRentals as $rr)
                                                            <option value="{{ $rr->id }}" data-type="{{ strtolower($rr->rental_type) }}" data-price="{{ $rr->price }}" {{ $currRoom == $rr->id ? 'selected' : '' }}>Kamar #{{ $rr->room->room_number ?? '?' }} - {{ ucfirst($rr->rental_type) }} (Rp {{ number_format($rr->price, 0, ',', '.') }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Tanggal Mulai <span class="text-rose-500">*</span></label>
                                                    <input type="date" name="start_date" x-model="eStartDate" @change="eCalc" class="form-input w-full rounded-xl" required>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Tanggal Berakhir <span class="text-rose-500">*</span></label>
                                                    <input type="date" name="end_date" x-model="eEndDate" @change="eCalc" class="form-input w-full rounded-xl" required>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Harga Kesepakatan (Rp) <span class="text-rose-500">*</span></label>
                                                    <input type="number" name="rental_price" x-model="ePrice" class="form-input w-full rounded-xl" required min="0" step="0.01">
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Status <span class="text-rose-500">*</span></label>
                                                    <select name="status" class="form-input w-full rounded-xl" required>
                                                        @php $currStatus = old('id') == $item->id ? old('status') : $item->status; @endphp
                                                        <option value="active" {{ $currStatus == 'active' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="inactive" {{ $currStatus == 'inactive' ? 'selected' : '' }}>Selesai / Nonaktif</option>
                                                    </select>
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
                                        <i class="fas fa-file-signature text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada penyewaan kamar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        </div>
        
        <div class="flex justify-center p-4 border-t border-slate-100">
            {{ method_exists($rentals, 'links') ? $rentals->appends(request()->query())->links() : '' }}
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
                <h3 class="text-[17px] font-bold text-slate-800">Informasi Sewa Baru</h3>
            </div>
            <form action="{{ route('admin.rentals.store') }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Penyewa <span class="text-rose-500">*</span></label>
                        <select name="tenants_id" class="form-input w-full rounded-xl" required>
                            <option value="">Pilih Penyewa...</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ (!old('id') && old('tenants_id') == $tenant->id) ? 'selected' : '' }}>{{ $tenant->user->name ?? 'User' }} ({{ $tenant->phone_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Tipe Harga & Kamar <span class="text-rose-500">*</span></label>
                        <select name="room_rentals_id" class="form-input w-full rounded-xl" @change="updateRoom" required>
                            <option value="">Pilih Kamar & Harga...</option>
                            @foreach($roomRentals as $rr)
                                <option value="{{ $rr->id }}" data-type="{{ strtolower($rr->rental_type) }}" data-price="{{ $rr->price }}" {{ (!old('id') && old('room_rentals_id') == $rr->id) ? 'selected' : '' }}>Kamar #{{ $rr->room->room_number ?? '?' }} - {{ ucfirst($rr->rental_type) }} (Rp {{ number_format($rr->price, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Tanggal Mulai <span class="text-rose-500">*</span></label>
                        <input type="date" name="start_date" x-model="startDate" @change="calc" class="form-input w-full rounded-xl" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Tanggal Berakhir <span class="text-rose-500">*</span></label>
                        <input type="date" name="end_date" x-model="endDate" @change="calc" class="form-input w-full rounded-xl" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Harga Kesepakatan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="rental_price" x-model="price" class="form-input w-full rounded-xl" required min="0" step="0.01">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="form-input w-full rounded-xl" required>
                            <option value="active" {{ (!old('id') && old('status') == 'active') ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ (!old('id') && old('status') == 'inactive') ? 'selected' : '' }}>Selesai / Nonaktif</option>
                        </select>
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

