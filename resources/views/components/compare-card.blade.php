@props(['left', 'right'])

@php
    $leftImage = $left->images->firstWhere('is_primary', true) ?? $left->images->first();
    $rightImage = $right->images->firstWhere('is_primary', true) ?? $right->images->first();
    $leftPrice = $left->priceFor(null);
    $rightPrice = $right->priceFor(null);
@endphp

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
    <div class="relative flex h-36">
        <div class="relative w-1/2 overflow-hidden bg-gray-100">
            @if($leftImage)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($leftImage->path) }}" alt="{{ $left->name }}" loading="lazy" class="h-full w-full object-cover">
            @endif
            <span class="absolute left-2 top-2 rounded bg-black/70 px-1.5 py-0.5 text-xs font-semibold text-white">{{ $left->hp }} HP</span>
        </div>
        <div class="relative w-1/2 overflow-hidden bg-gray-100">
            @if($rightImage)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($rightImage->path) }}" alt="{{ $right->name }}" loading="lazy" class="h-full w-full object-cover">
            @endif
            <span class="absolute right-2 top-2 rounded bg-black/70 px-1.5 py-0.5 text-xs font-semibold text-white">{{ $right->hp }} HP</span>
        </div>
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-600 text-xs font-bold text-white shadow">{{ __('VS') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2 p-4 text-sm">
        <div>
            <p class="font-semibold text-gray-900">{{ $left->brand->name }}</p>
            <p class="text-gray-600">{{ $left->name }}</p>
            <p class="mt-0.5 text-xs font-medium text-brand-700">
                {{ $leftPrice ? '₹'.number_format($leftPrice->price_min / 100000, 2).' – '.number_format($leftPrice->price_max / 100000, 2).' '.__('Lakh*') : __('Check Price') }}
            </p>
        </div>
        <div class="text-right">
            <p class="font-semibold text-gray-900">{{ $right->brand->name }}</p>
            <p class="text-gray-600">{{ $right->name }}</p>
            <p class="mt-0.5 text-xs font-medium text-brand-700">
                {{ $rightPrice ? '₹'.number_format($rightPrice->price_min / 100000, 2).' – '.number_format($rightPrice->price_max / 100000, 2).' '.__('Lakh*') : __('Check Price') }}
            </p>
        </div>
    </div>

    <a href="{{ localized_route('tractors.compare', ['slugs' => $left->slug.'-vs-'.$right->slug]) }}"
       class="block border-t border-gray-200 py-2.5 text-center text-sm font-semibold text-brand-700 hover:bg-brand-50">
        {{ $left->name }} {{ __('vs') }} {{ $right->name }}
    </a>
</div>
