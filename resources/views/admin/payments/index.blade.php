<x-app-layout>
    @section('header_title', 'Manajemen Pembayaran')

    <div class="space-y-6 animate-fade-in" x-data="{ showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Manajemen Pembayaran</span>
            </nav>
        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.payments.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari ID Pembayaran, penyewa..."
                                   style="padding-left:2.5rem; width:100%; margin:0;" class="form-input">
                        </div>
                        <div style="position:relative;">
                            <select name="per_page" onchange="this.form.submit()" class="form-input rounded-xl text-sm" style="margin:0; height: 100%;">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 baris</option>
                                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 baris</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                            <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search']))
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('admin.reports.index') }}" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 shadow-sm">
                        <i class="fas fa-print text-sm text-brand"></i>
                        Cetak Laporan
                    </a>
                    @if(auth()->user()->role === 'superadmin')
                    <button type="button" @click="showCreateModal = true" class="btn btn-primary">
                        <i class="fas fa-plus text-sm"></i>
                        Tambah
                    </button>
                    @endif
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto border-0 rounded-none shadow-none w-full">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4"><x-sortable column="payment_date" label="ID & Tanggal" /></th>
                        <th class="px-6 py-4">Penyewa & Tagihan</th>
                        <th class="px-6 py-4"><x-sortable column="amount" label="Nominal" /></th>
                        <th class="px-6 py-4 text-center"><x-sortable column="status" label="Status" /></th>
                        @if(auth()->user()->role === 'superadmin')
                        <th class="px-6 py-4 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($payments as $item)
                        <tr class="hover:bg-slate-50 transition-colors" x-data="{ showEditModal: {{ $errors->any() && old('_method') == 'PUT' && old('id') == $item->id ? 'true' : 'false' }} }">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">#PAY-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($item->payment_date)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-700">{{ $item->rental->tenant->user->name ?? 'User Terhapus' }}</div>
                                <div class="text-xs text-slate-500">Kamar #{{ $item->rental->roomRental->room->room_number ?? '?' }} - {{ $item->payment_period }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-800">Rp {{ number_format($item->amount, 0, ',', '.') }}</div>
                                <div class="text-xs text-slate-500">{{ $item->payment_method }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'paid')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($item->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-error">Menunggak</span>
                                @endif
                            </td>
                            @if(auth()->user()->role === 'superadmin')
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="showEditModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-brand transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.payments.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); window.dispatchEvent(new CustomEvent('open-confirm', { detail: { message: 'Apakah Anda yakin ingin menghapus data pembayaran ini?', form: this } }));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                            @if(auth()->user()->role === 'superadmin')
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
                                            <h3 class="text-[17px] font-bold text-slate-800">Ubah Data Pembayaran</h3>
                                        </div>
                                        <form action="{{ route('admin.payments.update', $item->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden min-h-0">
                                            <div class="px-6 pt-4 pb-6 space-y-5 overflow-y-auto flex-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-1.5 md:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700">Pilih Kontrak Sewa <span class="text-rose-500">*</span></label>
                                                    <select name="rentals_id" class="form-input w-full rounded-xl" required>
                                                        @php $currRental = old('id') == $item->id ? old('rentals_id') : $item->rentals_id; @endphp
                                                        @foreach($rentals as $rental)
                                                            <option value="{{ $rental->id }}" {{ $currRental == $rental->id ? 'selected' : '' }}>
                                                                {{ $rental->tenant->user->name ?? 'User' }} - Kamar #{{ $rental->roomRental->room->room_number ?? '?' }} (Tarif: Rp {{ number_format($rental->rental_price, 0, ',', '.') }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Tanggal Bayar <span class="text-rose-500">*</span></label>
                                                    <input type="date" name="payment_date" value="{{ old('id') == $item->id ? old('payment_date') : $item->payment_date }}" class="form-input w-full rounded-xl" required>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Periode Bayar (Bulan/Tahun) <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="payment_period" value="{{ old('id') == $item->id ? old('payment_period') : $item->payment_period }}" class="form-input w-full rounded-xl" required>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Jumlah Bayar (Rp) <span class="text-rose-500">*</span></label>
                                                    <input type="number" name="amount" value="{{ old('id') == $item->id ? old('amount') : $item->amount }}" class="form-input w-full rounded-xl" required min="0" step="0.01">
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Metode Pembayaran <span class="text-rose-500">*</span></label>
                                                    <select name="payment_method" class="form-input w-full rounded-xl" required>
                                                        @php $currMethod = old('id') == $item->id ? old('payment_method') : $item->payment_method; @endphp
                                                        <option value="Transfer Bank" {{ $currMethod == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                                        <option value="Tunai" {{ $currMethod == 'Tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                                                        <option value="E-Wallet" {{ $currMethod == 'E-Wallet' ? 'selected' : '' }}>E-Wallet (OVO/Gopay/dll)</option>
                                                    </select>
                                                </div>
                                                <div class="space-y-1.5 md:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700">Status Pembayaran <span class="text-rose-500">*</span></label>
                                                    <select name="status" class="form-input w-full rounded-xl" required>
                                                        @php $currStatus = old('id') == $item->id ? old('status') : $item->status; @endphp
                                                        <option value="paid" {{ $currStatus == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                                                        <option value="pending" {{ $currStatus == 'pending' ? 'selected' : '' }}>Belum Lunas (Pending)</option>
                                                        <option value="overdue" {{ $currStatus == 'overdue' ? 'selected' : '' }}>Menunggak (Overdue)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white flex-shrink-0">
                                                <button type="button" @click="showEditModal = false" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5">Batal</button>
                                                <button type="submit" class="btn bg-brand text-white hover:bg-brand-dark rounded-xl px-5">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </template>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state text-center py-10">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-3">
                                        <i class="fas fa-file-invoice-dollar text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada riwayat pembayaran</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
        
            <div class="flex justify-center p-4 border-t border-slate-100">
                {{ method_exists($payments, 'links') ? $payments->appends(request()->query())->links() : '' }}
            </div>
        </div>

    @if(auth()->user()->role === 'superadmin')
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
                <h3 class="text-[17px] font-bold text-slate-800">Informasi Pembayaran</h3>
            </div>
            <form action="{{ route('admin.payments.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden min-h-0">
                <div class="px-6 pt-4 pb-6 space-y-5 overflow-y-auto flex-1">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Pilih Kontrak Sewa <span class="text-rose-500">*</span></label>
                        <select name="rentals_id" class="form-input w-full rounded-xl" required>
                            <option value="">Pilih Kontrak...</option>
                            @foreach($rentals as $rental)
                                <option value="{{ $rental->id }}" {{ (!old('id') && old('rentals_id') == $rental->id) ? 'selected' : '' }}>
                                    {{ $rental->tenant->user->name ?? 'User' }} - Kamar #{{ $rental->roomRental->room->room_number ?? '?' }} (Tarif: Rp {{ number_format($rental->rental_price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Tanggal Bayar <span class="text-rose-500">*</span></label>
                        <input type="date" name="payment_date" value="{{ !old('id') ? old('payment_date', date('Y-m-d')) : '' }}" class="form-input w-full rounded-xl" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Periode Bayar (Bulan/Tahun) <span class="text-rose-500">*</span></label>
                        <input type="text" name="payment_period" value="{{ !old('id') ? old('payment_period') : '' }}" class="form-input w-full rounded-xl" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Jumlah Bayar (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="amount" value="{{ !old('id') ? old('amount') : '' }}" class="form-input w-full rounded-xl" required min="0" step="0.01">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Metode Pembayaran <span class="text-rose-500">*</span></label>
                        <select name="payment_method" class="form-input w-full rounded-xl" required>
                            <option value="">Pilih Metode...</option>
                            <option value="Transfer Bank" {{ (!old('id') && old('payment_method') == 'Transfer Bank') ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="Tunai" {{ (!old('id') && old('payment_method') == 'Tunai') ? 'selected' : '' }}>Tunai / Cash</option>
                            <option value="E-Wallet" {{ (!old('id') && old('payment_method') == 'E-Wallet') ? 'selected' : '' }}>E-Wallet (OVO/Gopay/dll)</option>
                        </select>
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Status Pembayaran <span class="text-rose-500">*</span></label>
                        <select name="status" class="form-input w-full rounded-xl" required>
                            <option value="paid" {{ (!old('id') && old('status') == 'paid') ? 'selected' : '' }}>Lunas (Paid)</option>
                            <option value="pending" {{ (!old('id') && old('status') == 'pending') ? 'selected' : '' }}>Belum Lunas (Pending)</option>
                            <option value="overdue" {{ (!old('id') && old('status') == 'overdue') ? 'selected' : '' }}>Menunggak (Overdue)</option>
                        </select>
                    </div>
                </div>
            </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white flex-shrink-0">
                    <button type="button" @click="showCreateModal = false" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5">Batal</button>
                    <button type="submit" class="btn bg-brand text-white hover:bg-brand-dark rounded-xl px-5">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    </template>
    @endif
    </div>
</x-app-layout>

