<x-app-layout>
    @section('header_title', 'Manajemen Properti')

    <div class="space-y-6 animate-fade-in" x-data="{ showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Manajemen Properti</span>
            </nav>
        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.properties.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama properti atau alamat..."
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
                            <a href="{{ route('admin.properties.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <button @click="showCreateModal = true" class="btn btn-primary flex-shrink-0">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto border-0 rounded-none shadow-none w-full">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4"><x-sortable column="name" label="Info Properti" /></th>
                        <th class="px-6 py-4"><x-sortable column="address" label="Alamat Lengkap" /></th>
                        <th class="px-6 py-4 text-center"><x-sortable column="rooms_count" label="Total Kamar" /></th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($properties as $item)
                        <tr class="hover:bg-slate-50 transition-colors" x-data="{ showEditModal: {{ $errors->any() && old('_method') == 'PUT' && old('id') == $item->id ? 'true' : 'false' }}, showRoomsModal: false }">
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                <div>{{ $item->name }}</div>
                                <div class="text-[11px] font-medium text-slate-400 mt-0.5">Milik: {{ $item->user->name ?? 'Unknown' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm max-w-xs truncate" title="{{ $item->address }}">{{ $item->address }}</td>
                            <td class="px-6 py-4 text-center">
                                <button type="button" @click="if({{ $item->rooms_count }} > 0) showRoomsModal = true" class="badge {{ $item->rooms_count > 0 ? 'badge-primary hover:bg-brand hover:text-white transition-colors cursor-pointer' : 'badge-gray cursor-default' }}">
                                    {{ $item->rooms_count }} Kamar
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="showEditModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-brand hover:bg-slate-50 transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.properties.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="event.preventDefault(); window.dispatchEvent(new CustomEvent('open-confirm', { detail: { message: 'Hapus properti ini?', form: this } }));" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <!-- Edit Modal -->
                            <td class="p-0 border-0">
                                <template x-teleport="body">
                                    <div x-show="showRoomsModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
                                        <div x-show="showRoomsModal" 
                                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showRoomsModal = false"></div>
                                        
                                        <div x-show="showRoomsModal"
                                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                             class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden text-left flex flex-col max-h-[80vh]">
                                            
                                            <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center">
                                                <div>
                                                    <h3 class="text-[17px] font-bold text-slate-800">Daftar Kamar</h3>
                                                    <p class="text-xs text-slate-500 mt-1">{{ $item->name }}</p>
                                                </div>
                                                <button @click="showRoomsModal = false" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-50">
                                                    <i class="fas fa-times text-lg"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="p-6 overflow-y-auto space-y-3">
                                                @if($item->rooms->count() > 0)
                                                    @foreach($item->rooms as $room)
                                                        <div class="flex items-center justify-between p-3 border border-slate-100 rounded-xl bg-slate-50/50">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-bold">
                                                                    {{ $room->room_number }}
                                                                </div>
                                                                <div>
                                                                    <div class="text-sm font-semibold text-slate-800">Kamar {{ $room->room_number }}</div>
                                                                    <div class="text-[11px] font-medium {{ $room->status == 'available' ? 'text-emerald-500' : ($room->status == 'occupied' ? 'text-rose-500' : 'text-amber-500') }}">
                                                                        {{ ucfirst($room->status) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-center py-6">
                                                        <p class="text-sm text-slate-500">Belum ada kamar.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
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
                                            <h3 class="text-[17px] font-bold text-slate-800">Ubah Properti</h3>
                                        </div>

                                        <form action="{{ route('admin.properties.update', $item->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden min-h-0">
                                            <div class="px-6 pt-4 pb-6 space-y-5 overflow-y-auto flex-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            
                                            <div class="space-y-1.5">
                                                <label class="block text-sm font-semibold text-slate-700">Pemilik (User) <span class="text-rose-500">*</span></label>
                                                <select name="user_id" class="form-input w-full rounded-xl" required>
                                                    <option value="">Pilih Pemilik Properti...</option>
                                                    @php $currentUserId = old('id') == $item->id ? old('user_id') : $item->user_id; @endphp
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" {{ $currentUserId == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                                    @endforeach
                                                </select>
                                                @if(old('id') == $item->id)
                                                    @error('user_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                                @endif
                                            </div>

                                            <div class="space-y-1.5">
                                                <label class="block text-sm font-semibold text-slate-700">Nama Properti <span class="text-rose-500">*</span></label>
                                                <input type="text" name="name" value="{{ old('id') == $item->id ? old('name') : $item->name }}" class="form-input w-full rounded-xl" placeholder="Contoh: KosKora Indah" required>
                                                @if(old('id') == $item->id)
                                                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                                @endif
                                            </div>

                                            <div class="space-y-1.5">
                                                <label class="block text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-rose-500">*</span></label>
                                                <textarea name="address" rows="3" class="form-input w-full h-auto py-3 rounded-xl" placeholder="Contoh: Jl. Sudirman No.123..." required>{{ old('id') == $item->id ? old('address') : $item->address }}</textarea>
                                                @if(old('id') == $item->id)
                                                    @error('address') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                                @endif
                                            </div>

                                            <div class="space-y-1.5">
                                                <label class="block text-sm font-semibold text-slate-700">Deskripsi Properti (Opsional)</label>
                                                <textarea name="description" rows="3" class="form-input w-full h-auto py-3 rounded-xl" placeholder="Informasi tambahan mengenai properti...">{{ old('id') == $item->id ? old('description') : $item->description }}</textarea>
                                                @if(old('id') == $item->id)
                                                    @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                                @endif
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state text-center py-10">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-3">
                                        <i class="fas fa-building text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada properti terdaftar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            <div class="flex justify-center p-4 border-t border-slate-100">
                {{ method_exists($properties, 'links') ? $properties->appends(request()->query())->links() : '' }}
            </div>
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
                    <h3 class="text-[17px] font-bold text-slate-800">Tambah Properti</h3>
                </div>

                <form action="{{ route('admin.properties.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden min-h-0">
                    <div class="px-6 pt-4 pb-6 space-y-5 overflow-y-auto flex-1">
                    @csrf
                    
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Pemilik (User) <span class="text-rose-500">*</span></label>
                        <select name="user_id" class="form-input w-full rounded-xl" required>
                            <option value="">Pilih Pemilik Properti...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ !old('id') && old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @if(!old('id'))
                            @error('user_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Nama Properti <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ !old('id') ? old('name') : '' }}" class="form-input w-full rounded-xl" placeholder="Contoh: KosKora Indah" required>
                        @if(!old('id'))
                            @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="address" rows="3" class="form-input w-full h-auto py-3 rounded-xl" placeholder="Contoh: Jl. Sudirman No.123..." required>{{ !old('id') ? old('address') : '' }}</textarea>
                        @if(!old('id'))
                            @error('address') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Deskripsi Properti (Opsional)</label>
                        <textarea name="description" rows="3" class="form-input w-full h-auto py-3 rounded-xl" placeholder="Informasi tambahan mengenai properti...">{{ !old('id') ? old('description') : '' }}</textarea>
                        @if(!old('id'))
                            @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        @endif
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
    </div>
</x-app-layout>
