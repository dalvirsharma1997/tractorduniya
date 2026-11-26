@extends('layouts.app')

@section('title', $dealer->name . ' — ' . $dealer->city . ', ' . $dealer->state->name)
@section('seo_type', 'dealer')
@section('seo_id', $dealer->id)

@section('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": {!! json_encode($dealer->name) !!},
    "address": {
        "@type": "PostalAddress",
        "streetAddress": {!! json_encode($dealer->address) !!},
        "addressLocality": {!! json_encode($dealer->city) !!},
        "addressRegion": {!! json_encode($dealer->state->name) !!}
    },
    "telephone": {!! json_encode($dealer->phone) !!}
}
</script>
@endsection

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <x-breadcrumbs :items="[
        ['name' => 'Dealers', 'url' => route('dealers.index')],
        ['name' => $dealer->name, 'url' => null],
    ]" />

    <h1 class="mt-4 text-2xl font-bold text-gray-900">
        {{ $dealer->name }}
        @if($dealer->is_verified)
            <span class="ml-2 rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-800">Verified Dealer</span>
        @endif
    </h1>
    <p class="mt-1 text-gray-600">{{ $dealer->address }}, {{ $dealer->city }}, {{ $dealer->district->name }}, {{ $dealer->state->name }}</p>

    <dl class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div><dt class="text-sm text-gray-500">Phone</dt><dd class="font-medium"><a href="tel:{{ $dealer->phone }}" class="hover:underline">{{ $dealer->phone }}</a></dd></div>
        @if($dealer->whatsapp)
            <div><dt class="text-sm text-gray-500">WhatsApp</dt><dd class="font-medium">{{ $dealer->whatsapp }}</dd></div>
        @endif
        <div><dt class="text-sm text-gray-500">Brands sold</dt><dd class="font-medium">{{ $dealer->brands->pluck('name')->implode(', ') }}</dd></div>
    </dl>

    <div class="mt-6 aspect-video overflow-hidden rounded-lg border border-gray-200">
        <iframe
            width="100%" height="100%" frameborder="0" style="border:0"
            src="https://maps.google.com/maps?q={{ urlencode($dealer->address . ', ' . $dealer->city . ', ' . $dealer->state->name) }}&output=embed"
            allowfullscreen loading="lazy">
        </iframe>
    </div>

    <x-faq-schema :faqs="$faqs" />
    <x-faq-list :faqs="$faqs" :heading="__('FAQs about :dealer', ['dealer' => $dealer->name])" />
</div>
@endsection
