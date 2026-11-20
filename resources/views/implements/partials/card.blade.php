@php
    $primaryImage = $implement->images->firstWhere('is_primary', true) ?? $implement->images->first();
@endphp
<a href="{{ route('implements.show', $implement) }}" class="group rounded-lg border border-gray-200 p-4 transition hover:shadow-md">
    <div class="aspect-[4/3] overflow-hidden rounded-md bg-gray-100">
        @if($primaryImage)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($primaryImage->path) }}" alt="{{ $implement->name }}" loading="lazy"
                 class="h-full w-full object-cover transition group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center text-gray-400">{{ __('No image') }}</div>
        @endif
    </div>
    <p class="mt-3 font-semibold text-gray-900">{{ $implement->name }}</p>
    <p class="text-sm text-gray-600">{{ $implement->type }} @if($implement->brand) · {{ $implement->brand->name }} @endif</p>
    @if($implement->compatible_hp_min)
        <p class="text-xs text-gray-500">Compatible: {{ $implement->compatible_hp_min }}–{{ $implement->compatible_hp_max }} HP</p>
    @endif
    @if($implement->price_min)
        <p class="mt-1 text-sm font-medium text-brand-700">₹{{ number_format($implement->price_min) }}–₹{{ number_format($implement->price_max) }}</p>
    @endif
</a>
