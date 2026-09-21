<x-app-layout>
    @section('header_title', 'Tambah Fasilitas')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.facilities.index') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-brand hover:border-brand/30 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Tambah Fasilitas Baru</h2>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 md:p-8">
            <form action="{{ route('admin.facilities.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-1.5">
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Fasilitas</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input w-full" placeholder="Contoh: WiFi, AC, Kamar Mandi Dalam" required>
                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="4" class="form-input w-full h-auto py-3" placeholder="Tambahkan keterangan lebih lanjut...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
