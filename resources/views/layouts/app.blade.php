@php
    $seoOverride = seo_meta_override(View::yieldContent('seo_type') ?: null, View::yieldContent('seo_id') ? (int) View::yieldContent('seo_id') : null);
    $pageTitle = $seoOverride->meta_title ?? View::yieldContent('title') ?: 'TractorDuniya.in — Tractors, Implements & Dealers Across India';
    $pageDescription = $seoOverride->meta_description ?? View::yieldContent('meta_description') ?: 'Compare tractor prices and specs, find implements, locate dealers near you, and buy or sell used tractors on TractorDuniya.in.';
    $canonicalUrl = $seoOverride->canonical_url ?? (View::hasSection('canonical') ? View::yieldContent('canonical') : url()->current());
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @if($seoOverride?->og_image)
        <meta property="og:image" content="{{ \Illuminate\Support\Facades\Storage::url($seoOverride->og_image) }}">
    @endif
    @yield('json_ld')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-white text-gray-900 antialiased">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
