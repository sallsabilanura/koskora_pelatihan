@props(['column', 'label'])

@php
    $currentSort = request('sort');
    $currentDirection = request('direction', 'asc');
    $isSorted = $currentSort === $column;
    $nextDirection = $isSorted && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp

<a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection]) }}" class="inline-flex items-center gap-1.5 group hover:text-brand-blue transition-colors">
    <span>{{ $label }}</span>
    <span class="flex flex-col text-[10px] leading-[8px] text-slate-300">
        <i class="fas fa-caret-up {{ $isSorted && $currentDirection === 'asc' ? 'text-brand-blue' : 'group-hover:text-slate-400' }}"></i>
        <i class="fas fa-caret-down {{ $isSorted && $currentDirection === 'desc' ? 'text-brand-blue' : 'group-hover:text-slate-400' }}"></i>
    </span>
</a>
