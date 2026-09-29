<x-app-layout>
    @section('header_title', 'Tenants Management')

    <div class="space-y-6 animate-fade-in" x-data="{ showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Manajemen Penyewa</span>
            </nav>
        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.tenants.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama, KTP, atau no HP..."
                                   style="padding-left:2.5rem; width:100%; margin:0;" class="form-input">
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                            <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search']))
                            <a href="{{ route('admin.tenants.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <button type="button" @click="showCreateModal = true" class="btn btn-primary flex-shrink-0">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah Penyewa
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto border-0 rounded-none shadow-none w-full">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Profil Penyewa</th>
                        <th class="px-6 py-4"><x-sortable column="phone_number" label="Kontak" /></th>
                        <th class="px-6 py-4"><x-sortable column="address" label="Alamat" /></th>
                        <th class="px-6 py-4 text-center"><x-sortable column="status" label="Status Akun" /></th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tenants as $item)
                        <tr class="hover:bg-slate-50 transition-colors" x-data="{ showEditModal: {{ $errors->any() && old('_method') == 'PUT' && old('id') == $item->id ? 'true' : 'false' }} }">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($item->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $item->user->name ?? 'User Terhapus' }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-700">{{ $item->phone_number }}</div>
                                <div class="text-xs text-slate-500">Darurat: {{ $item->emergency_contact }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 truncate max-w-[200px]" title="{{ $item->address }}">
                                    {{ $item->address }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-gray">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="showEditModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-brand transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.tenants.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); window.dispatchEvent(new CustomEvent('open-confirm', { detail: { message: 'Apakah Anda yakin ingin menghapus penyewa ini?', form: this } }));">
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
                                            <h3 class="text-[17px] font-bold text-slate-800">Ubah Data Penyewa</h3>
                                        </div>
                                        <form action="{{ route('admin.tenants.update', $item->id) }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-1.5 md:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700">Akun Pengguna (Tenant) <span class="text-rose-500">*</span></label>
                                                    <select name="user_id" class="form-input w-full rounded-xl" required>
                                                        @php $currUser = old('id') == $item->id ? old('user_id') : $item->user_id; @endphp
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}" {{ $currUser == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Nomor HP <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="phone_number" value="{{ old('id') == $item->id ? old('phone_number') : $item->phone_number }}" class="form-input w-full rounded-xl" required>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Kontak Darurat <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="emergency_contact" value="{{ old('id') == $item->id ? old('emergency_contact') : $item->emergency_contact }}" class="form-input w-full rounded-xl" required>
                                                </div>
                                                <div class="space-y-1.5 md:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700">Alamat Asal <span class="text-rose-500">*</span></label>
                                                    <textarea name="address" rows="3" class="form-input w-full h-auto py-3 rounded-xl" required>{{ old('id') == $item->id ? old('address') : $item->address }}</textarea>
                                                </div>
                                                <div class="space-y-1.5 md:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700">Status <span class="text-rose-500">*</span></label>
                                                    <select name="status" class="form-input w-full rounded-xl" required>
                                                        @php $currStatus = old('id') == $item->id ? old('status') : $item->status; @endphp
                                                        <option value="active" {{ $currStatus == 'active' ? 'selected' : '' }}>Aktif (Active)</option>
                                                        <option value="inactive" {{ $currStatus == 'inactive' ? 'selected' : '' }}>Tidak Aktif (Inactive)</option>
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
                                        <i class="fas fa-users text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada penyewa terdaftar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        </div>
        
        <div class="flex justify-center p-4 border-t border-slate-100">
            {{ method_exists($tenants, 'links') ? $tenants->appends(request()->query())->links() : '' }}
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
                <h3 class="text-[17px] font-bold text-slate-800">Informasi Penyewa Baru</h3>
            </div>
            <form action="{{ route('admin.tenants.store') }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Akun Pengguna (Tenant) <span class="text-rose-500">*</span></label>
                        <select name="user_id" class="form-input w-full rounded-xl" required>
                            <option value="">Pilih Akun Pengguna...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (!old('id') && old('user_id') == $user->id) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Hanya pengguna dengan peran "tenant" yang dapat dipilih.</p>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Nomor HP <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone_number" value="{{ !old('id') ? old('phone_number') : '' }}" class="form-input w-full rounded-xl" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Kontak Darurat <span class="text-rose-500">*</span></label>
                        <input type="text" name="emergency_contact" value="{{ !old('id') ? old('emergency_contact') : '' }}" class="form-input w-full rounded-xl" required>
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Alamat Asal <span class="text-rose-500">*</span></label>
                        <textarea name="address" rows="3" class="form-input w-full h-auto py-3 rounded-xl" required>{{ !old('id') ? old('address') : '' }}</textarea>
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="form-input w-full rounded-xl" required>
                            <option value="active" {{ (!old('id') && old('status') == 'active') ? 'selected' : '' }}>Aktif (Active)</option>
                            <option value="inactive" {{ (!old('id') && old('status') == 'inactive') ? 'selected' : '' }}>Tidak Aktif (Inactive)</option>
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
