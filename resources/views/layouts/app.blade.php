<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TractorDuniya.in — Tractors, Implements & Dealers Across India')</title>
    <meta name="description" content="@yield('meta_description', 'Compare tractor prices and specs, find implements, locate dealers near you, and buy or sell used tractors on TractorDuniya.in.')">
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
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
