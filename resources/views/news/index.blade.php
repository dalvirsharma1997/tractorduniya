@extends('layouts.app')

@section('title', 'News — Tractor Industry Updates')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">Blog</h1>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($posts as $post)
            <a href="{{ route('news.show', $post) }}" class="rounded-lg border border-gray-200 p-4 hover:shadow-md">
                @if($post->cover_image)
                    <div class="aspect-[16/9] overflow-hidden rounded-md bg-gray-100">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($post->cover_image) }}" loading="lazy" class="h-full w-full object-cover">
                    </div>
                @endif
                <p class="mt-3 font-semibold text-gray-900">{{ $post->title }}</p>
                <p class="mt-1 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}</p>
                <p class="mt-2 text-xs text-gray-400">{{ $post->published_at?->format('d M Y') }}</p>
            </a>
        @empty
            <p class="col-span-full text-gray-500">No posts published yet.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $posts->links() }}</div>
</div>
@endsection
