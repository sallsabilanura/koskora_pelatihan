<x-guest-layout>
    <div class="mb-10">
        <div class="mb-6">
            <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-10 w-auto">
        </div>
        <h2 class="text-3xl font-extrabold text-slate-900 mb-3 tracking-tight">Ganti Password</h2>
        <p class="text-slate-500 text-sm leading-relaxed">Demi keamanan akun Anda, silakan ubah password default Anda sekarang.</p>
    </div>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 font-bold text-sm text-emerald-600 bg-emerald-50 p-4 rounded-xl border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf

        <!-- Current Password -->
        <div class="relative mt-4">
            <label for="current_password" class="absolute -top-2.5 left-3 z-10 bg-[#fdfdfe] px-2 text-xs font-semibold text-slate-500 tracking-wide">Password Saat Ini <span class="text-rose-500">*</span></label>
            <div class="relative">
                <input id="current_password" type="password" name="current_password" required autofocus placeholder="Masukkan password saat ini (default: 12345678)" class="block w-full pl-4 pr-12 py-3 bg-white border border-slate-300 rounded-md focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all text-slate-800 text-sm placeholder:text-slate-400">
                <button type="button" onclick="const p=document.getElementById('current_password'); const i=document.getElementById('eye-icon-current'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                    <i class="fa-solid fa-eye-slash" id="eye-icon-current"></i>
                </button>
            </div>
            @error('current_password')
                <span class="mt-2 text-xs font-bold text-red-500 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- New Password -->
        <div class="relative mt-8">
            <label for="password" class="absolute -top-2.5 left-3 z-10 bg-[#fdfdfe] px-2 text-xs font-semibold text-slate-500 tracking-wide">Password Baru <span class="text-rose-500">*</span></label>
            <div class="relative">
                <input id="password" type="password" name="password" required placeholder="Masukkan password baru" class="block w-full pl-4 pr-12 py-3 bg-white border border-slate-300 rounded-md focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all text-slate-800 text-sm placeholder:text-slate-400">
                <button type="button" onclick="const p=document.getElementById('password'); const i=document.getElementById('eye-icon-new'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                    <i class="fa-solid fa-eye-slash" id="eye-icon-new"></i>
                </button>
            </div>
            @error('password')
                <span class="mt-2 text-xs font-bold text-red-500 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="relative mt-8">
            <label for="password_confirmation" class="absolute -top-2.5 left-3 z-10 bg-[#fdfdfe] px-2 text-xs font-semibold text-slate-500 tracking-wide">Konfirmasi Password <span class="text-rose-500">*</span></label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Masukkan ulang password baru" class="block w-full pl-4 pr-12 py-3 bg-white border border-slate-300 rounded-md focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all text-slate-800 text-sm placeholder:text-slate-400">
                <button type="button" onclick="const p=document.getElementById('password_confirmation'); const i=document.getElementById('eye-icon-confirm'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                    <i class="fa-solid fa-eye-slash" id="eye-icon-confirm"></i>
                </button>
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full py-3 bg-brand-blue text-white rounded-md font-semibold text-sm hover:bg-[#151375] transition-all flex items-center justify-center">
                Simpan & Lanjutkan
            </button>
        </div>
    </form>
    
    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-500 hover:text-red-500 font-medium transition-colors">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
