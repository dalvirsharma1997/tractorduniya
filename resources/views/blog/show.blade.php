@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt)

@section('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": {{ json_encode($post->title) }},
    "datePublished": {{ json_encode($post->published_at?->toIso8601String()) }}
}
</script>
@endsection

@section('content')
<article class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="text-sm text-gray-500"><a href="{{ route('blog.index') }}" class="hover:underline">Blog</a> / {{ $post->title }}</nav>
    <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
    <p class="mt-2 text-sm text-gray-400">{{ $post->published_at?->format('d M Y') }} @if($post->category) · {{ $post->category }} @endif</p>

    @if($post->cover_image)
        <div class="mt-6 aspect-[16/9] overflow-hidden rounded-xl bg-gray-100">
            <img src="{{ \Illuminate\Support\Facades\Storage::url($post->cover_image) }}" class="h-full w-full object-cover">
        </div>
    @endif

    <div class="prose prose-gray mt-6 max-w-none">
        {!! $post->body !!}
    </div>
</article>

@if($related->isNotEmpty())
    <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-900">More from the blog</h2>
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
            @foreach($related as $item)
                <a href="{{ route('blog.show', $item) }}" class="rounded-lg border border-gray-200 p-4 hover:shadow-md">
                    <p class="font-semibold text-gray-900">{{ $item->title }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection
