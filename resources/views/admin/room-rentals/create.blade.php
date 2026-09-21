<x-app-layout>
    @section('header_title', 'Tambah Tipe Sewa Kamar')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== BREADCRUMB ===== --}}
        <nav class="flex text-sm text-slate-500 items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <span><i class="fas fa-chevron-right text-xs"></i></span>
            <a href="{{ route('admin.room-rentals.index') }}" class="hover:text-brand transition-colors">
                Tipe Harga Sewa Kamar
            </a>
            <span><i class="fas fa-chevron-right text-xs"></i></span>
            <span class="text-slate-900 font-medium">Tambah</span>
        </nav>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 md:p-8">
                <form action="{{ route('admin.room-rentals.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="room_id" class="block text-sm font-medium text-slate-700">Kamar</label>
                            <select name="room_id" id="room_id" class="form-input w-full" required>
                                <option value="" disabled selected>Pilih Kamar</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        Kamar #{{ $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="rental_type" class="block text-sm font-medium text-slate-700">Tipe Sewa</label>
                            <select name="rental_type" id="rental_type" class="form-input w-full" required>
                                <option value="" disabled selected>Pilih Tipe</option>
                                <option value="harian" {{ old('rental_type') == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="mingguan" {{ old('rental_type') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                <option value="bulanan" {{ old('rental_type') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahunan" {{ old('rental_type') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                            @error('rental_type') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="space-y-1.5">
                            <label for="price" class="block text-sm font-medium text-slate-700">Harga (Rp)</label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" class="form-input w-full" placeholder="Contoh: 1500000" required min="0" step="0.01">
                            @error('price') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <a href="{{ route('admin.room-rentals.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Tipe Sewa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
