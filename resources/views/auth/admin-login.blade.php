<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-brand-blue mb-2">Log <span class="text-brand-red">In</span></h2>
        <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Akses Layanan Premium KosKora</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 font-bold text-sm text-emerald-600 bg-emerald-50 p-4 rounded-xl border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/admin/login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="relative">
            <label for="email" class="absolute -top-2.5 left-3 z-10 bg-[#fdfdfe] px-1.5 text-sm font-semibold text-slate-500">Username / Email</label>
            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan username atau email" class="block w-full px-4 py-3.5 bg-transparent border border-slate-300 rounded-xl focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all text-slate-700 placeholder:text-slate-400">
            @error('email')
                <span class="mt-2 text-xs font-bold text-brand-red block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="relative mt-6">
            <label for="password" class="absolute -top-2.5 left-3 z-10 bg-[#fdfdfe] px-1.5 text-sm font-semibold text-slate-500">Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" required placeholder="Masukkan password" class="block w-full pl-4 pr-12 py-3.5 bg-transparent border border-slate-300 rounded-xl focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all text-slate-700 placeholder:text-slate-400">
                <button type="button" onclick="const p=document.getElementById('password'); const i=document.getElementById('eye-icon'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                    <i class="fa-solid fa-eye" id="eye-icon"></i>
                </button>
            </div>
            @if (Route::has('password.request'))
                <div class="flex justify-end mt-2">
                    <a class="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-brand-red transition-colors" href="{{ route('password.request') }}">
                        Lupa?
                    </a>
                </div>
            @endif
            @error('password')
                <span class="mt-2 text-xs font-bold text-brand-red block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-2 border-slate-200 text-brand-blue shadow-sm focus:ring-0 w-4 h-4 transition-all" name="remember">
                <span class="ms-3 text-xs font-bold text-slate-400 group-hover:text-brand-blue transition-colors">Ingat saya</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-brand-red text-white rounded-[0.875rem] font-black text-xs uppercase tracking-widest hover:bg-brand-blue transition-all transform active:scale-[0.98]">
                Masuk Sekarang
            </button>
        </div>
    </form>
</x-guest-layout>