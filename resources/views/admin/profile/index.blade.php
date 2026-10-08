<x-app-layout>
    @section('header_title', 'Profil Saya')

    <div class="animate-fade-in space-y-4">

        {{-- BREADCRUMB --}}
        <div class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 -mx-4 md:-mx-8 -mt-4 md:-mt-8 flex items-center">
            <nav class="flex text-sm text-slate-500 items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors"><i class="fas fa-home"></i> Dashboard</a>
                <span><i class="fas fa-chevron-right text-[10px]"></i></span>
                <span class="text-slate-900 font-medium">Profil Saya</span>
            </nav>
        </div>

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
        </div>
        @endif

        {{-- SINGLE MAIN CARD --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            
            {{-- PROFILE BANNER (Header section) --}}
            <div class="px-6 py-5 flex items-center gap-4 border-b border-slate-100">
                <div class="w-14 h-14 rounded-2xl bg-brand text-white flex items-center justify-center font-black text-2xl flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-slate-400 mt-0.5">{{ auth()->user()->email }}</p>
                </div>
                <div class="hidden sm:flex items-center gap-6 flex-shrink-0">
                    <div class="text-center">
                        <div class="text-sm font-bold text-slate-700">{{ auth()->user()->created_at->format('d M Y') }}</div>
                        <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Terdaftar</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-bold {{ auth()->user()->is_active ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                        </div>
                        <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Status</div>
                    </div>
                    <span class="text-sm font-bold text-brand uppercase tracking-wider">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>

            {{-- FORMS SIDE BY SIDE --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">

                {{-- Edit Profile --}}
                <div class="p-6">
                    <div class="mb-5 flex items-center gap-2">
                        <div class="text-brand text-lg mr-1 flex items-center justify-center">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700">Informasi Profil</h3>
                    </div>
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-slate-600">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-input w-full rounded-xl border-slate-200 text-sm py-2" placeholder="Nama lengkap" required>
                                @error('name') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-slate-600">Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-input w-full rounded-xl border-slate-200 text-sm py-2" placeholder="email@contoh.com" required>
                                @error('email') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-slate-600">Nomor HP</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" class="form-input w-full rounded-xl border-slate-200 text-sm py-2" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-slate-600">Alamat</label>
                                <input type="text" name="address" value="{{ old('address', auth()->user()->address) }}" class="form-input w-full rounded-xl border-slate-200 text-sm py-2" placeholder="Kota, Provinsi">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-brand text-white hover:bg-brand-dark px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                                <i class="fas fa-save text-xs"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Change Password --}}
                <div class="p-6">
                    <div class="mb-5 flex items-center gap-2">
                        <div class="text-slate-600 text-lg mr-1 flex items-center justify-center">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700">Keamanan & Password</h3>
                    </div>
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1 col-span-2" x-data="{ show: false }">
                                <label class="block text-xs font-semibold text-slate-600">Password Saat Ini <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="current_password" class="form-input w-full rounded-xl border-slate-200 pr-10 text-sm py-2 @error('current_password') border-rose-400 @enderror" placeholder="Masukkan password saat ini" required>
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                    </button>
                                </div>
                                @error('current_password') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1" x-data="{ show: false }">
                                <label class="block text-xs font-semibold text-slate-600">Password Baru <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" class="form-input w-full rounded-xl border-slate-200 pr-10 text-sm py-2 @error('password') border-rose-400 @enderror" placeholder="Min. 8 karakter" required>
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                    </button>
                                </div>
                                @error('password') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1" x-data="{ show: false }">
                                <label class="block text-xs font-semibold text-slate-600">Konfirmasi Password <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password_confirmation" class="form-input w-full rounded-xl border-slate-200 pr-10 text-sm py-2" placeholder="Ulangi password baru" required>
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-slate-800 text-white hover:bg-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                                <i class="fas fa-lock text-xs"></i> Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
