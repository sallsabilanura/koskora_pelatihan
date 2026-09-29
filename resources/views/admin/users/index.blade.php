<x-app-layout>
    @section('header_title', 'Manajemen Pengguna')

    <div class="space-y-6 animate-fade-in" x-data="{ showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        {{-- ===== BREADCRUMB ===== --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Manajemen Pengguna</span>
            </nav>
        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama atau email..."
                                   style="padding-left:2.5rem; width:100%; margin:0;" class="form-input rounded-lg border-slate-200">
                        </div>
                        <button type="submit" class="bg-slate-100 text-slate-600 hover:bg-slate-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" style="flex-shrink:0; white-space:nowrap;">
                            <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search']))
                            <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-slate-600 p-2" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <button @click="showCreateModal = true" type="button" class="bg-brand text-white hover:bg-brand-dark px-4 py-2 rounded-lg text-sm font-medium transition-colors flex-shrink-0 flex items-center gap-2">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah Pengguna
                </button>
            </div>

            @if(session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 border-b border-emerald-100 text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 text-rose-600 p-4 border-b border-rose-100 text-sm font-medium flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Table --}}
            <div class="overflow-x-auto border-0 rounded-none shadow-none">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4"><x-sortable column="name" label="Pengguna" /></th>
                            <th class="px-6 py-4"><x-sortable column="role" label="Peran" /></th>
                            <th class="px-6 py-4"><x-sortable column="created_at" label="Terdaftar" /></th>
                            <th class="px-6 py-4 text-center"><x-sortable column="is_active" label="Status" /></th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors" x-data="{ showEditModal: {{ $errors->any() && old('_method') == 'PUT' && old('id') == $user->id ? 'true' : 'false' }} }">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Admin
                                        </span>
                                    @elseif($user->role === 'owner')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Owner
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="m-0 inline-block">
                                        @csrf
                                        <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $user->is_active ? 'bg-brand' : 'bg-slate-200' }}" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $user->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="showEditModal = true" type="button" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-brand hover:bg-slate-50 transition-all" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" class="inline-block m-0" onsubmit="event.preventDefault(); window.dispatchEvent(new CustomEvent('open-confirm', { detail: { message: 'Reset password ke default 12345678?', form: this } }));">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all" title="Reset Password">
                                                <i class="fas fa-key"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block m-0" onsubmit="event.preventDefault(); window.dispatchEvent(new CustomEvent('open-confirm', { detail: { message: 'Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?', form: this } }));">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @else
                                            <span class="text-xs text-slate-400 ml-2">Akun Anda</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Edit Modal -->
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
                                             class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden text-left flex flex-col max-h-[90vh]">
                                            
                                            <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0">
                                                <h3 class="text-[17px] font-bold text-slate-800">Ubah Pengguna</h3>
                                            </div>

                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                                
                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="name" value="{{ old('id') == $user->id ? old('name') : $user->name }}" class="form-input w-full rounded-xl border-slate-200" required>
                                                    @if(old('id') == $user->id) @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror @endif
                                                </div>

                                                <div class="space-y-1.5">
                                                    <label class="block text-sm font-semibold text-slate-700">Email <span class="text-rose-500">*</span></label>
                                                    <input type="email" name="email" value="{{ old('id') == $user->id ? old('email') : $user->email }}" class="form-input w-full rounded-xl border-slate-200" required>
                                                    @if(old('id') == $user->id) @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror @endif
                                                </div>



                                                <div class="pt-2 pb-2 flex items-center justify-end gap-3">
                                                    <button type="button" @click="showEditModal = false" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5 py-2 text-sm font-medium transition-colors">Batal</button>
                                                    <button type="submit" class="btn bg-brand text-white hover:bg-brand-dark rounded-xl px-5 py-2 text-sm font-medium transition-colors">Simpan</button>
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
                                    <div class="text-center py-10">
                                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-3">
                                            <i class="fas fa-users text-2xl"></i>
                                        </div>
                                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada pengguna ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-center p-4 border-t border-slate-100">
                {{ method_exists($users, 'links') ? $users->appends(request()->query())->links() : '' }}
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
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden text-left flex flex-col max-h-[90vh]">
                
                <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center flex-shrink-0">
                    <h3 class="text-[17px] font-bold text-slate-800">Tambah Pengguna Baru</h3>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" class="px-6 pt-2 pb-6 space-y-5 overflow-y-auto">
                    @csrf
                    
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ !old('id') ? old('name') : '' }}" class="form-input w-full rounded-xl border-slate-200" required>
                        @if(!old('id')) @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ !old('id') ? old('email') : '' }}" class="form-input w-full rounded-xl border-slate-200" required>
                        @if(!old('id')) @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="bg-blue-50 border border-blue-100 text-blue-700 p-4 rounded-xl text-sm flex gap-3">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <div>Password akan secara otomatis diatur menjadi <strong>12345678</strong> untuk pengguna baru.</div>
                    </div>

                    <div class="pt-2 pb-2 flex items-center justify-end gap-3">
                        <button type="button" @click="showCreateModal = false" class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-5 py-2 text-sm font-medium transition-colors">Batal</button>
                        <button type="submit" class="btn bg-brand text-white hover:bg-brand-dark rounded-xl px-5 py-2 text-sm font-medium transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        </template>
    </div>
</x-app-layout>
