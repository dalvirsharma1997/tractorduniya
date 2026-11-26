@extends('layouts.app')

@section('title', $tractors->pluck('name')->implode(' vs ') . ' — Compare Specs & Price')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <x-breadcrumbs :items="[
        ['name' => 'Tractors', 'url' => route('tractors.index')],
        ['name' => __('Compare'), 'url' => null],
    ]" />

    <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $tractors->pluck('name')->implode(' vs ') }}</h1>
    <p class="mt-1 text-sm text-gray-500">{{ __('Compare specifications and prices side by side, or swap in any tractor below.') }}</p>

    @php $totalSlots = $tractors->count() + ($tractors->count() < 3 ? 1 : 0); @endphp
    <style>
        @media (min-width: 1024px) {
            #compare-grid { grid-template-columns: repeat({{ $totalSlots }}, minmax(0, 1fr)); }
        }
    </style>
    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2" id="compare-grid">
        @foreach($tractors as $index => $tractor)
            @php $image = $tractor->images->firstWhere('is_primary', true) ?? $tractor->images->first(); @endphp
            <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white">
                @if($tractors->count() > 2)
                    <button type="button" class="compare-remove absolute right-2 top-2 z-10 flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-gray-600 shadow hover:bg-white hover:text-red-600"
                            data-index="{{ $index }}" aria-label="{{ __('Remove') }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                @endif
                <div class="aspect-[4/3] bg-gray-100">
                    @if($image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}" alt="{{ $tractor->name }}" loading="lazy" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-sm text-gray-400">{{ __('No image') }}</div>
                    @endif
                </div>
                <div class="p-4">
                    <a href="{{ localized_route('tractors.show', [$tractor->brand, $tractor]) }}" class="font-semibold text-gray-900 hover:text-brand-700">{{ $tractor->name }}</a>
                    <p class="text-sm text-gray-500">{{ $tractor->brand->name }} · {{ $tractor->hp }} HP · {{ $tractor->drive_type }}</p>
                    @php $price = $tractor->priceFor(null); @endphp
                    <p class="mt-1 text-sm font-semibold text-brand-700">
                        {{ $price ? '₹'.number_format($price->price_min / 100000, 2).' – '.number_format($price->price_max / 100000, 2).' '.__('Lakh*') : __('Check Price') }}
                    </p>

                    <label class="mt-4 block text-xs font-medium text-gray-500">{{ __('Swap this tractor') }}</label>
                    <select class="compare-slot mt-1 w-full rounded-md border border-gray-300 text-sm" data-index="{{ $index }}">
                        @foreach($allTractors->groupBy(fn ($t) => $t->brand->name) as $brandName => $brandTractors)
                            <optgroup label="{{ $brandName }}">
                                @foreach($brandTractors as $option)
                                    <option value="{{ $option->slug }}" @selected($option->slug === $tractor->slug)>{{ $option->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach

        @if($tractors->count() < 3)
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 p-6 text-center">
                <p class="text-sm font-medium text-gray-600">{{ __('Add another tractor') }}</p>
                <select id="add-tractor-slot" class="mt-3 w-full rounded-md border border-gray-300 text-sm">
                    <option value="">{{ __('Select a tractor') }}</option>
                    @foreach($allTractors->groupBy(fn ($t) => $t->brand->name) as $brandName => $brandTractors)
                        <optgroup label="{{ $brandName }}">
                            @foreach($brandTractors as $option)
                                @unless($tractors->contains('slug', $option->slug))
                                    <option value="{{ $option->slug }}">{{ $option->name }}</option>
                                @endunless
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <div class="mt-10 overflow-x-auto">
        <table class="w-full min-w-max border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="w-40 p-4 text-left"></th>
                    @foreach($tractors as $tractor)
                        <th class="p-4 text-left">
                            <a href="{{ localized_route('tractors.show', [$tractor->brand, $tractor]) }}" class="font-semibold text-brand-700 hover:underline">
                                {{ $tractor->name }}
                            </a>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach([
                    'Brand' => fn($t) => $t->brand->name,
                    'HP' => fn($t) => $t->hp,
                    'Drive Type' => fn($t) => $t->drive_type,
                    'Engine CC' => fn($t) => $t->engine_cc ?? '—',
                    'Cylinders' => fn($t) => $t->cylinders ?? '—',
                    'PTO HP' => fn($t) => $t->pto_hp ?? '—',
                    'Lifting Capacity (kg)' => fn($t) => $t->lifting_capacity_kg ?? '—',
                    'Fuel Tank (L)' => fn($t) => $t->fuel_tank_litres ?? '—',
                    'Transmission' => fn($t) => $t->transmission_type ?? '—',
                    'Warranty' => fn($t) => $t->warranty_text ?? '—',
                    'Price' => fn($t) => optional($t->priceFor(null), fn($p) => '₹' . number_format($p->price_min) . ' – ₹' . number_format($p->price_max)) ?? 'On request',
                ] as $label => $callback)
                    <tr class="odd:bg-white even:bg-gray-50">
                        <td class="p-4 font-medium text-gray-500">{{ __($label) }}</td>
                        @foreach($tractors as $tractor)
                            <td class="p-4 text-gray-900">{{ $callback($tractor) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    (function () {
        const currentSlugs = @json($tractors->pluck('slug')->values());

        function goTo(slugs) {
            window.location.href = '{{ localized_route('tractors.compare', ['slugs' => '__SLUGS__']) }}'.replace('__SLUGS__', slugs.join('-vs-'));
        }

        document.querySelectorAll('.compare-slot').forEach(function (select) {
            select.addEventListener('change', function () {
                const slugs = currentSlugs.slice();
                slugs[parseInt(select.dataset.index, 10)] = select.value;
                goTo(slugs);
            });
        });

        const addSlot = document.getElementById('add-tractor-slot');
        if (addSlot) {
            addSlot.addEventListener('change', function () {
                if (!addSlot.value) return;
                goTo(currentSlugs.concat([addSlot.value]));
            });
        }

        document.querySelectorAll('.compare-remove').forEach(function (button) {
            button.addEventListener('click', function () {
                const slugs = currentSlugs.slice();
                slugs.splice(parseInt(button.dataset.index, 10), 1);
                if (slugs.length >= 2) goTo(slugs);
            });
        });
    })();
</script>
@endsection
