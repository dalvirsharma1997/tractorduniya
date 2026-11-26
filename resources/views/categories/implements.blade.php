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

    @if($category->show_comparison && $comparableTractors->count() >= 2)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Compare Tractors') }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ __('Tractors compatible with implements in this category.') }}</p>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                @foreach($comparableTractors->skip(1) as $item)
                    <x-compare-card :left="$comparableTractors->first()" :right="$item" />
                @endforeach
            </div>
        </div>
    @endif

    @if($category->footer_content)
        <section class="mt-12 border-t border-gray-200 pt-8">
            <h2 class="text-xl font-bold text-gray-900">{{ __('About :category', ['category' => $category->name]) }}</h2>
            <div class="prose prose-gray mt-4 max-w-none text-gray-600">
                {!! $category->footer_content !!}
            </div>
        </section>
    @endif
</div>
@endsection
