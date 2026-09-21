<x-app-layout>
    @section('header_title', 'Tambah Properti')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.properties.index') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-brand hover:border-brand/30 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Tambah Properti Baru</h2>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 md:p-8">
            <form action="{{ route('admin.properties.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-1.5">
                    <label for="user_id" class="block text-sm font-medium text-slate-700">Pemilik (User)</label>
                    <select name="user_id" id="user_id" class="form-input w-full" required>
                        <option value="">Pilih Pemilik Properti...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Properti</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input w-full" placeholder="Contoh: KosKora Indah" required>
                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="address" class="block text-sm font-medium text-slate-700">Alamat Lengkap</label>
                    <textarea name="address" id="address" rows="3" class="form-input w-full h-auto py-3" placeholder="Contoh: Jl. Sudirman No.123..." required>{{ old('address') }}</textarea>
                    @error('address') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi Properti (Opsional)</label>
                    <textarea name="description" id="description" rows="4" class="form-input w-full h-auto py-3" placeholder="Informasi tambahan mengenai properti...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Properti</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
