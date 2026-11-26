@extends('layouts.app')

@php
    $primaryImage = $tractor->images->firstWhere('is_primary', true) ?? $tractor->images->first();
    $nationalPrice = $tractor->priceFor(null);
@endphp

@section('title', $tractor->name . ' Price, Specs & Mileage — ' . $tractor->brand->name)
@section('meta_description', $tractor->name . ' (' . $tractor->hp . ' HP) price, full specifications, features, and dealer contacts in India.')
@section('seo_type', 'tractor')
@section('seo_id', $tractor->id)

@section('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": {!! json_encode($tractor->name) !!},
    "brand": { "@type": "Brand", "name": {!! json_encode($tractor->brand->name) !!} },
    @if($nationalPrice)
    "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "INR",
        "lowPrice": {{ $nationalPrice->price_min }},
        "highPrice": {{ $nationalPrice->price_max }}
    },
    @endif
    "image": {!! json_encode($tractor->images->map(fn($i) => asset('storage/'.$i->path))->values()) !!}
}
</script>
@endsection

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <x-breadcrumbs :items="[
        ['name' => 'Tractors', 'url' => route('tractors.index')],
        ['name' => $tractor->brand->name, 'url' => route('brands.show', $tractor->brand)],
        ['name' => $tractor->name, 'url' => null],
    ]" />

    <x-faq-schema :faqs="$faqs" />

    <div class="mt-4 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="aspect-[16/9] overflow-hidden rounded-xl bg-gray-100">
                @if($primaryImage)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($primaryImage->path) }}" alt="{{ $tractor->name }}" class="h-full w-full object-cover">
                @endif
            </div>

            <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $tractor->name }}</h1>
            <p class="text-gray-600">{{ $tractor->brand->name }} · {{ $tractor->hp }} HP · {{ $tractor->drive_type }} · {{ $tractor->status === 'upcoming' ? 'Upcoming' : ($tractor->launch_year ? 'Launched ' . $tractor->launch_year : '') }}</p>

            @if($averageRating > 0)
                <p class="mt-1 text-sm text-amber-600">★ {{ number_format($averageRating, 1) }} ({{ $reviews->count() }} reviews)</p>
            @endif

            {{-- Tabs --}}
            <div class="mt-6 border-b border-gray-200">
                <nav class="-mb-px flex flex-wrap gap-4 text-sm font-medium" id="tractor-tabs">
                    @foreach(['overview' => __('Overview'), 'price' => __('Price'), 'specs' => __('Full Specs'), 'gallery' => __('Gallery'), 'reviews' => __('Reviews'), 'faqs' => __('FAQs')] as $key => $label)
                        <button type="button" onclick="showTab('{{ $key }}')" id="tab-btn-{{ $key }}"
                                class="tab-btn border-b-2 border-transparent px-1 py-2 text-gray-600 hover:text-brand-700">
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <div id="tab-overview" class="tab-panel py-6">
                @if($tractor->description)
                    <p class="mb-4 text-gray-700">{{ $tractor->description }}</p>
                @endif
                <p class="text-gray-700">{{ $tractor->warranty_text ? __('Warranty: :text', ['text' => $tractor->warranty_text]) : '' }}</p>
                <dl class="mt-4 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                    <div><dt class="text-gray-500">{{ __('HP') }}</dt><dd class="font-medium">{{ $tractor->hp }}</dd></div>
                    <div><dt class="text-gray-500">{{ __('Drive Type') }}</dt><dd class="font-medium">{{ $tractor->drive_type }}</dd></div>
                    <div><dt class="text-gray-500">{{ __('PTO HP') }}</dt><dd class="font-medium">{{ $tractor->pto_hp ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">{{ __('Lifting Capacity') }}</dt><dd class="font-medium">{{ $tractor->lifting_capacity_kg ? $tractor->lifting_capacity_kg . ' kg' : '—' }}</dd></div>
                    <div><dt class="text-gray-500">{{ __('Fuel Tank') }}</dt><dd class="font-medium">{{ $tractor->fuel_tank_litres ? $tractor->fuel_tank_litres . ' L' : '—' }}</dd></div>
                    <div><dt class="text-gray-500">{{ __('Transmission') }}</dt><dd class="font-medium">{{ $tractor->transmission_type ?? '—' }}</dd></div>
                </dl>
            </div>

            <div id="tab-price" class="tab-panel hidden py-6">
                <label class="block text-sm font-medium text-gray-700">{{ __('Select your state for an accurate on-road price') }}</label>
                <select id="state-selector" class="mt-2 rounded-md border border-gray-300 text-sm" onchange="updatePrice(this.value)">
                    <option value="">{{ __('National (ex-showroom)') }}</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
                <div id="price-display" class="mt-4 text-xl font-bold text-brand-700">
                    @if($nationalPrice)
                        ₹{{ number_format($nationalPrice->price_min) }} – ₹{{ number_format($nationalPrice->price_max) }}
                    @else
                        {{ __('Price on request') }}
                    @endif
                </div>
                <p class="mt-1 text-xs text-gray-500">{{ __('*Ex-showroom price. On-road price varies by state due to RTO tax and registration.') }}</p>

                <script>
                    const tractorPrices = {{ Illuminate\Support\Js::from($tractor->prices->map(fn($p) => ['state_id' => $p->state_id, 'min' => $p->price_min, 'max' => $p->price_max])) }};
                    function updatePrice(stateId) {
                        const match = tractorPrices.find(p => String(p.state_id) === String(stateId));
                        const national = tractorPrices.find(p => p.state_id === null);
                        const price = match || national;
                        const el = document.getElementById('price-display');
                        el.textContent = price ? `₹${Number(price.min).toLocaleString('en-IN')} – ₹${Number(price.max).toLocaleString('en-IN')}` : 'Price on request';
                    }
                </script>
            </div>

            <div id="tab-specs" class="tab-panel hidden py-6">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-200">
                        @foreach($tractor->specs as $spec)
                            <tr>
                                <td class="py-2 text-gray-500">{{ $spec->spec_key }}</td>
                                <td class="py-2 font-medium text-gray-900">{{ $spec->spec_value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="tab-gallery" class="tab-panel hidden py-6">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach($tractor->images as $image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}" loading="lazy" class="aspect-square rounded-md object-cover">
                    @endforeach
                </div>
            </div>

            <div id="tab-reviews" class="tab-panel hidden py-6">
                @forelse($reviews as $review)
                    <div class="border-b border-gray-100 py-4">
                        <p class="font-medium text-gray-900">{{ $review->reviewer_name }} — ★{{ $review->rating }}</p>
                        @if($review->title)<p class="font-medium">{{ $review->title }}</p>@endif
                        <p class="text-sm text-gray-600">{{ $review->body }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">{{ __("No reviews yet.") }}</p>
                @endforelse
            </div>

            <div id="tab-faqs" class="tab-panel hidden py-6">
                @forelse($faqs as $faq)
                    <details class="border-b border-gray-100 py-3">
                        <summary class="cursor-pointer font-medium text-gray-900">{{ $faq->question }}</summary>
                        <p class="mt-2 text-sm text-gray-600">{{ $faq->answer }}</p>
                    </details>
                @empty
                    <p class="text-gray-500">{{ __("No FAQs for this model yet.") }}</p>
                @endforelse
            </div>

            <script>
                function showTab(key) {
                    document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('border-brand-600', 'text-brand-700'));
                    document.getElementById('tab-' + key).classList.remove('hidden');
                    document.getElementById('tab-btn-' + key).classList.add('border-brand-600', 'text-brand-700');
                }
                showTab('overview');
            </script>
        </div>

        <div>
            <x-lead-form :tractor="$tractor" />
        </div>
    </div>

    @if($tractor->footer_content)
        <section class="mt-12 border-t border-gray-200 pt-8">
            <h2 class="text-xl font-bold text-gray-900">{{ __('About :tractor', ['tractor' => $tractor->name]) }}</h2>
            <div class="prose prose-gray mt-4 max-w-none text-gray-600">
                {!! $tractor->footer_content !!}
            </div>
        </section>
    @endif

    @if($similar->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900">{{ __("Similar Tractors") }}</h2>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($similar as $item)
                    @include('tractors.partials.card', ['tractor' => $item])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
