@extends('layouts.app')

@php
    $primaryImage = $implement->images->firstWhere('is_primary', true) ?? $implement->images->first();
@endphp

@section('title', $implement->name . ' — Price & Specifications')
@section('meta_description', $implement->description ?? $implement->name . ' specifications and price.')
@section('seo_type', 'implement')
@section('seo_id', $implement->id)

@section('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": {!! json_encode($implement->name) !!},
    @if($implement->brand)
    "brand": { "@type": "Brand", "name": {!! json_encode($implement->brand->name) !!} },
    @endif
    @if($implement->price_min)
    "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "INR",
        "lowPrice": {{ $implement->price_min }},
        "highPrice": {{ $implement->price_max }}
    }
    @endif
}
</script>
@endsection

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <x-breadcrumbs :items="[
        ['name' => 'Implements', 'url' => route('implements.index')],
        ['name' => $implement->name, 'url' => null],
    ]" />

    <x-faq-schema :faqs="$faqs" />

    <div class="mt-4 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="aspect-[16/9] overflow-hidden rounded-xl bg-gray-100">
                @if($primaryImage)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($primaryImage->path) }}" alt="{{ $implement->name }}" class="h-full w-full object-cover">
                @endif
            </div>

            <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $implement->name }}</h1>
            <p class="text-gray-600">{{ $implement->type }} @if($implement->brand) · {{ $implement->brand->name }} @endif</p>
            @if($implement->compatible_hp_min)
                <p class="mt-1 text-sm text-gray-600">Compatible with tractors of {{ $implement->compatible_hp_min }}–{{ $implement->compatible_hp_max }} HP</p>
            @endif
            @if($implement->price_min)
                <p class="mt-2 text-xl font-bold text-brand-700">₹{{ number_format($implement->price_min) }} – ₹{{ number_format($implement->price_max) }}</p>
            @endif

            @if($implement->description)
                <p class="mt-4 text-gray-700">{{ $implement->description }}</p>
            @endif

            @if($implement->specs->isNotEmpty())
                <h2 class="mt-8 text-lg font-semibold text-gray-900">Specifications</h2>
                <table class="mt-2 w-full text-sm">
                    <tbody class="divide-y divide-gray-200">
                        @foreach($implement->specs as $spec)
                            <tr>
                                <td class="py-2 text-gray-500">{{ $spec->spec_key }}</td>
                                <td class="py-2 font-medium text-gray-900">{{ $spec->spec_value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>

        <div>
            <x-lead-form :implement="$implement" />
        </div>
    </div>

    @if($implement->show_comparison && $comparableTractors->count() >= 2)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Compare Tractors') }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ __('Tractors compatible with this implement.') }}</p>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                @foreach($comparableTractors->skip(1) as $item)
                    <x-compare-card :left="$comparableTractors->first()" :right="$item" />
                @endforeach
            </div>
        </div>
    @endif

    @if($implement->footer_content)
        <section class="mt-12 border-t border-gray-200 pt-8">
            <h2 class="text-xl font-bold text-gray-900">{{ __('About :implement', ['implement' => $implement->name]) }}</h2>
            <div class="prose prose-gray mt-4 max-w-none text-gray-600">
                {!! $implement->footer_content !!}
            </div>
        </section>
    @endif

    @if($similar->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900">Similar Implements</h2>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($similar as $item)
                    @include('implements.partials.card', ['implement' => $item])
                @endforeach
            </div>
        </div>
    @endif

    <x-faq-list :faqs="$faqs" :heading="__(':implement FAQs', ['implement' => $implement->name])" />
</div>
@endsection
