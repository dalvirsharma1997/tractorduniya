@extends('layouts.app')

@section('title', $category->name . ' — Browse & Compare')
@section('seo_type', 'category')
@section('seo_id', $category->id)

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
    @if($category->description)
        <p class="mt-2 text-gray-600">{{ $category->description }}</p>
    @endif

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($implements as $implement)
            @include('implements.partials.card', ['implement' => $implement])
        @empty
            <p class="col-span-full text-gray-500">No implements in this category yet.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $implements->links() }}</div>
</div>
@endsection
