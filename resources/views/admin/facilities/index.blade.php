<x-app-layout>
    @section('header_title', 'Facilities Management')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== BREADCRUMB ===== --}}
        <nav class="flex text-sm text-slate-500 items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <span><i class="fas fa-chevron-right text-xs"></i></span>
            <span class="text-slate-900 font-medium">Manajemen Fasilitas</span>
        </nav>

        {{-- ===== MAIN CARD ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Header, Search, Add Button --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
                <form action="{{ route('admin.facilities.index') }}" method="GET" class="flex-1 w-full md:w-auto">
                    <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:180px; max-width: 300px;">
                            <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama fasilitas..."
                                   style="padding-left:2.5rem; width:100%; margin:0;" class="form-input">
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                            <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search']))
                            <a href="{{ route('admin.facilities.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                                <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary flex-shrink-0">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah Fasilitas
                </a>
            </div>

            {{-- Table --}}
            <div class="table-wrap border-0 rounded-none shadow-none">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Fasilitas</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Ikon</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($facilities as $item)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="font-semibold text-slate-900">{{ $item->name }}</td>
                            <td class="text-slate-500 text-sm">{{ $item->description ?? '-' }}</td>
                            <td class="text-center text-slate-400">
                                <i class="fas fa-check-circle text-brand/30"></i>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.facilities.edit', $item->id) }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand/30 transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.facilities.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus fasilitas ini?')" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state text-center py-10">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-3">
                                        <i class="fas fa-concierge-bell text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-2">Belum ada data fasilitas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        </div>
        
        <div class="flex justify-center p-4 border-t border-slate-100">
            {{ method_exists($facilities, 'links') ? $facilities->appends(request()->query())->links() : '' }}
        </div>
        </div>
    </div>
</x-app-layout>
