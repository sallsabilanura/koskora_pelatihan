<x-guest-layout>
    <div class="mb-10">
        <div class="mb-6">
            <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-10 w-auto">
        </div>
        <h2 class="text-3xl font-extrabold text-slate-900 mb-3 tracking-tight">Login Admin</h2>
        <p class="text-slate-500 text-sm leading-relaxed">Masuk untuk mengelola data operasional, penyewa, dan<br>transaksi layanan kos.</p>
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
            <label for="email" class="absolute -top-2.5 left-3 z-10 bg-[#fdfdfe] px-2 text-xs font-semibold text-brand-blue tracking-wide">Username / Email <span class="text-rose-500">*</span></label>
            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan username atau email" class="block w-full px-4 py-3 bg-white border border-brand-blue rounded-md focus:outline-none focus:ring-1 focus:ring-brand-blue transition-all text-slate-800 text-sm placeholder:text-slate-400">
            @error('email')
                <span class="mt-2 text-xs font-bold text-red-500 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="relative mt-8">
            <label for="password" class="absolute -top-2.5 left-3 z-10 bg-[#fdfdfe] px-2 text-xs font-semibold text-slate-500 tracking-wide">Password <span class="text-rose-500">*</span></label>
            <div class="relative">
                <input id="password" type="password" name="password" required placeholder="Masukkan password" class="block w-full pl-4 pr-12 py-3 bg-white border border-slate-300 rounded-md focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all text-slate-800 text-sm placeholder:text-slate-400">
                <button type="button" onclick="const p=document.getElementById('password'); const i=document.getElementById('eye-icon'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                    <i class="fa-solid fa-eye-slash" id="eye-icon"></i>
                </button>
            </div>
            @error('password')
                <span class="mt-2 text-xs font-bold text-red-500 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border border-slate-300 text-brand-blue shadow-sm focus:ring-0 w-4 h-4 transition-all" name="remember">
                <span class="ms-2 text-sm font-medium text-slate-500 group-hover:text-slate-800 transition-colors">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-slate-500 hover:text-brand-blue transition-colors" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-3 bg-brand-blue text-white rounded-md font-semibold text-sm hover:bg-[#151375] transition-all flex items-center justify-center">
                Login to Dashboard
            </button>
        </div>
    </form>
</x-guest-layout>