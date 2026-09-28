<x-app-layout>
    @section('header_title', 'Facilities Management')

    <div class="space-y-6 animate-fade-in" x-data="{ showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Manajemen Fasilitas</span>
            </nav>
        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.facilities.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama fasilitas..."
                                   style="padding-left:2.5rem; width:100%; margin:0;" class="form-input">
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                            <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search']))
                            <a href="{{ route('admin.facilities.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <button @click="showCreateModal = true" class="btn btn-primary flex-shrink-0">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah Fasilitas
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto border-0 rounded-none shadow-none w-full">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 w-1/4"><x-sortable column="name" label="Nama Fasilitas" /></th>
                        <th class="px-6 py-4 w-1/2"><x-sortable column="description" label="Deskripsi" /></th>
                        <th class="px-6 py-4 w-1/12 text-center">Ikon</th>
                        <th class="px-6 py-4 w-1/6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($facilities as $item)
                        <tr class="hover:bg-slate-50 transition-colors" x-data="{ showEditModal: {{ $errors->any() && old('_method') == 'PUT' && old('id') == $item->id ? 'true' : 'false' }} }">
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $item->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-center text-brand">
                                <i class="{{ $item->icon ?? 'fas fa-check-circle' }} text-lg"></i>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="showEditModal = true" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand/30 transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <form action="{{ route('admin.facilities.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus fasilitas ini?')" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <!-- Edit Modal untuk Item ini -->
                            <td class="p-0 border-0">
                                <template x-teleport="body">
                                    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
                                    <!-- Backdrop -->
                                    <div x-show="showEditModal" 
                                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showEditModal = false"></div>

                                    <!-- Modal Panel -->
                                    <div x-show="showEditModal"
                                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden text-left flex flex-col max-h-[90vh]">
                                        
                                        <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0">
                                            <h3 class="text-[17px] font-bold text-slate-800">Ubah Fasilitas</h3>
                                        </div>

                                        <form action="{{ route('admin.facilities.update', $item->id) }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Nama Fasilitas <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="name" value="{{ old('id') == $item->id ? old('name') : $item->name }}" class="form-input w-full rounded-xl" placeholder="Contoh: WiFi, AC" required>
                                                    @if(old('id') == $item->id)
                                                        @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                                    @endif
                                                </div>

                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Pilih Ikon</label>
                                                    <select name="icon" class="form-input w-full rounded-xl">
                                                        @php $currentIcon = old('id') == $item->id ? old('icon') : $item->icon; @endphp
                                                        <option value="fas fa-check" {{ $currentIcon == 'fas fa-check' ? 'selected' : '' }}>Default (Centang)</option>
                                                        <option value="fas fa-snowflake" {{ $currentIcon == 'fas fa-snowflake' ? 'selected' : '' }}>AC</option>
                                                        <option value="fas fa-wifi" {{ $currentIcon == 'fas fa-wifi' ? 'selected' : '' }}>WiFi / Internet</option>
                                                        <option value="fas fa-bed" {{ $currentIcon == 'fas fa-bed' ? 'selected' : '' }}>Kasur / Tempat Tidur</option>
                                                        <option value="fas fa-bath" {{ $currentIcon == 'fas fa-bath' ? 'selected' : '' }}>Kamar Mandi Dalam</option>
                                                        <option value="fas fa-tv" {{ $currentIcon == 'fas fa-tv' ? 'selected' : '' }}>Televisi (TV)</option>
                                                        <option value="fas fa-door-closed" {{ $currentIcon == 'fas fa-door-closed' ? 'selected' : '' }}>Lemari Pakaian</option>
                                                        <option value="fas fa-chair" {{ $currentIcon == 'fas fa-chair' ? 'selected' : '' }}>Meja & Kursi</option>
                                                        <option value="fas fa-utensils" {{ $currentIcon == 'fas fa-utensils' ? 'selected' : '' }}>Dapur / Pantry</option>
                                                        <option value="fas fa-parking" {{ $currentIcon == 'fas fa-parking' ? 'selected' : '' }}>Area Parkir</option>
                                                        <option value="fas fa-video" {{ $currentIcon == 'fas fa-video' ? 'selected' : '' }}>Kamera CCTV</option>
                                                        <option value="fas fa-bolt" {{ $currentIcon == 'fas fa-bolt' ? 'selected' : '' }}>Termasuk Listrik</option>
                                                        <option value="fas fa-tint" {{ $currentIcon == 'fas fa-tint' ? 'selected' : '' }}>Termasuk Air</option>
                                                        <option value="fas fa-broom" {{ $currentIcon == 'fas fa-broom' ? 'selected' : '' }}>Cleaning Service</option>
                                                    </select>
                                                    @if(old('id') == $item->id)
                                                        @error('icon') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="space-y-1.5">
                                                <label class="block text-sm font-semibold text-slate-700">Deskripsi (Opsional)</label>
                                                <textarea name="description" rows="3" class="form-input w-full h-auto py-3 rounded-xl" placeholder="Tambahkan keterangan...">{{ old('id') == $item->id ? old('description') : $item->description }}</textarea>
                                                @if(old('id') == $item->id)
                                                    @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                                @endif
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
                            <td colspan="4">
                                <div class="empty-state text-center py-10">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-3">
                                        <i class="fas fa-concierge-bell text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada data fasilitas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        
        <div class="flex justify-center p-4 border-t border-slate-100">
            {{ method_exists($facilities, 'links') ? $facilities->appends(request()->query())->links() : '' }}
        </div>

        <!-- Create Modal -->
        <template x-teleport="body">
            <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
            <!-- Backdrop -->
            <div x-show="showCreateModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showCreateModal = false"></div>

            <!-- Modal Panel -->
            <div x-show="showCreateModal"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden text-left flex flex-col max-h-[90vh]">
                
                <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0">
                    <h3 class="text-[17px] font-bold text-slate-800">Tambah Fasilitas Baru</h3>
                </div>

                <form action="{{ route('admin.facilities.store') }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-sm font-semibold text-slate-700">Nama Fasilitas <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ !old('id') ? old('name') : '' }}" class="form-input w-full rounded-xl" placeholder="Contoh: WiFi, AC" required>
                            @if(!old('id'))
                                @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div class="space-y-1.5">
                            <label for="icon" class="block text-sm font-semibold text-slate-700">Pilih Ikon</label>
                            <select name="icon" id="icon" class="form-input w-full rounded-xl">
                                <option value="fas fa-check">Default (Centang)</option>
                                <option value="fas fa-snowflake" {{ !old('id') && old('icon') == 'fas fa-snowflake' ? 'selected' : '' }}>AC</option>
                                <option value="fas fa-wifi" {{ !old('id') && old('icon') == 'fas fa-wifi' ? 'selected' : '' }}>WiFi / Internet</option>
                                <option value="fas fa-bed" {{ !old('id') && old('icon') == 'fas fa-bed' ? 'selected' : '' }}>Kasur / Tempat Tidur</option>
                                <option value="fas fa-bath" {{ !old('id') && old('icon') == 'fas fa-bath' ? 'selected' : '' }}>Kamar Mandi Dalam</option>
                                <option value="fas fa-tv" {{ !old('id') && old('icon') == 'fas fa-tv' ? 'selected' : '' }}>Televisi (TV)</option>
                                <option value="fas fa-door-closed" {{ !old('id') && old('icon') == 'fas fa-door-closed' ? 'selected' : '' }}>Lemari Pakaian</option>
                                <option value="fas fa-chair" {{ !old('id') && old('icon') == 'fas fa-chair' ? 'selected' : '' }}>Meja & Kursi</option>
                                <option value="fas fa-utensils" {{ !old('id') && old('icon') == 'fas fa-utensils' ? 'selected' : '' }}>Dapur / Pantry</option>
                                <option value="fas fa-parking" {{ !old('id') && old('icon') == 'fas fa-parking' ? 'selected' : '' }}>Area Parkir</option>
                                <option value="fas fa-video" {{ !old('id') && old('icon') == 'fas fa-video' ? 'selected' : '' }}>Kamera CCTV</option>
                                <option value="fas fa-bolt" {{ !old('id') && old('icon') == 'fas fa-bolt' ? 'selected' : '' }}>Termasuk Listrik</option>
                                <option value="fas fa-tint" {{ !old('id') && old('icon') == 'fas fa-tint' ? 'selected' : '' }}>Termasuk Air</option>
                                <option value="fas fa-broom" {{ !old('id') && old('icon') == 'fas fa-broom' ? 'selected' : '' }}>Cleaning Service</option>
                            </select>
                            @if(!old('id'))
                                @error('icon') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="description" class="block text-sm font-semibold text-slate-700">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3" class="form-input w-full h-auto py-3 rounded-xl" placeholder="Tambahkan keterangan lebih lanjut...">{{ !old('id') ? old('description') : '' }}</textarea>
                        @if(!old('id'))
                            @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        @endif
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
