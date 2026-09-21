<x-app-layout>
    @section('header_title', 'Tambah Kamar')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.rooms.index') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-brand hover:border-brand/30 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Tambah Kamar</h2>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 md:p-8">
            <form action="{{ route('admin.rooms.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label for="room_number" class="block text-sm font-medium text-slate-700">Nomor Kamar</label>
                        <input type="text" name="room_number" id="room_number" value="{{ old('room_number') }}" class="form-input w-full" placeholder="Contoh: 101, A1" required>
                        @error('room_number') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="floor" class="block text-sm font-medium text-slate-700">Lantai</label>
                        <input type="text" name="floor" id="floor" value="{{ old('floor') }}" class="form-input w-full" placeholder="Contoh: 1, 2" required>
                        @error('floor') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-1.5">
                        <label for="properties_id" class="block text-sm font-medium text-slate-700">Properti</label>
                        <select name="properties_id" id="properties_id" class="form-input w-full" required>
                            <option value="" disabled selected>Pilih Properti</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" {{ old('properties_id') == $property->id ? 'selected' : '' }}>
                                    {{ $property->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('properties_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-1.5">
                        <label for="facilities_id" class="block text-sm font-medium text-slate-700">Fasilitas</label>
                        <select name="facilities_id" id="facilities_id" class="form-input w-full" required>
                            <option value="" disabled selected>Pilih Fasilitas</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ old('facilities_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('facilities_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="status" id="status" class="form-input w-full" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Kamar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
