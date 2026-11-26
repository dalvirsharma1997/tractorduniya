@extends('layouts.app')

@section('title', $page?->title ?? 'Privacy Policy')
@section('seo_type', 'page')
@section('seo_id', $page?->id)

@section('content')
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ $page?->title ?? 'Privacy Policy' }}</h1>
    <div class="prose prose-gray mt-6 max-w-none">
        @if($page?->body)
            {!! $page->body !!}
        @else
            <p>We collect only the information you choose to share with us through enquiry and listing forms — your name, phone number, email, and location — in order to connect you with relevant dealers and respond to your requests.</p>
            <p>We do not sell your personal information to third parties. Your enquiry details are shared only with the dealer(s) relevant to your request.</p>
            <p>This policy may be updated from time to time; the latest version will always be available at this page.</p>
        @endif
    </div>
</div>
@endsection
