@php
    $primaryImage = $tractor->images->firstWhere('is_primary', true) ?? $tractor->images->first();
    $price = $tractor->priceFor(null);
@endphp
<a href="{{ route('tractors.show', [$tractor->brand, $tractor]) }}"
   class="group rounded-lg border border-gray-200 p-4 transition hover:shadow-md">
    <div class="aspect-[4/3] overflow-hidden rounded-md bg-gray-100">
        @if($primaryImage)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($primaryImage->path) }}" alt="{{ $tractor->name }}" loading="lazy"
                 class="h-full w-full object-cover transition group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center text-gray-400">{{ __('No image') }}</div>
        @endif
    </div>
    <p class="mt-3 font-semibold text-gray-900">{{ $tractor->name }}</p>
    <p class="text-sm text-gray-600">{{ $tractor->brand->name }} · {{ $tractor->hp }} HP · {{ $tractor->drive_type }}</p>
    @if($price)
        <p class="mt-1 text-sm font-medium text-brand-700">
            ₹{{ number_format($price->price_min / 100000, 2) }}–{{ number_format($price->price_max / 100000, 2) }} Lakh*
        </p>
    @endif
</a>
