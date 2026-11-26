@extends('layouts.app')

@section('title', $page?->title ?? 'About TractorDuniya.in')
@section('seo_type', 'page')
@section('seo_id', $page?->id)

@section('content')
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ $page?->title ?? 'About TractorDuniya.in' }}</h1>
    <div class="prose prose-gray mt-6 max-w-none">
        @if($page?->body)
            {!! $page->body !!}
        @else
            <p>TractorDuniya.in is an independent information and marketplace platform for the Indian tractor industry. We help farmers and buyers research tractor models, compare specifications and prices, locate authorised dealers, and buy or sell used tractors and implements.</p>
            <p>Our listings cover every major brand sold in India, with state-wise pricing to account for RTO tax and registration charges that vary across the country.</p>
        @endif
    </div>
</div>
@endsection
