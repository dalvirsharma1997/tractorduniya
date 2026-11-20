@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
<x-faq-schema :faqs="$faqs" />
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900">Frequently Asked Questions</h1>
    <div class="mt-6 divide-y divide-gray-200">
        @forelse($faqs as $faq)
            <details class="group py-4">
                <summary class="cursor-pointer list-none font-medium text-gray-900">{{ $faq->question }}</summary>
                <p class="mt-2 text-sm text-gray-600">{{ $faq->answer }}</p>
            </details>
        @empty
            <p class="py-4 text-gray-600">No FAQs published yet.</p>
        @endforelse
    </div>
</div>
@endsection
