@extends('layouts.app')

@section('title', $category->name . ' — Tractor Models & Prices')
@section('seo_type', 'category')
@section('seo_id', $category->id)

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
    @if($category->description)
        <p class="mt-2 text-gray-600">{{ $category->description }}</p>
    @endif

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($tractors as $tractor)
            @include('tractors.partials.card', ['tractor' => $tractor])
        @empty
            <p class="col-span-full text-gray-500">No models in this category yet.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $tractors->links() }}</div>

    @if($categoryImplements->isNotEmpty())
        <div class="mt-12">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">{{ __('Implements') }}</h2>
                <a href="{{ localized_route('implements.index') }}" class="text-sm font-medium text-brand-700 hover:underline">{{ __('View all') }} &rarr;</a>
            </div>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($categoryImplements as $implement)
                    @include('implements.partials.card', ['implement' => $implement])
                @endforeach
            </div>
        </div>
    @endif

    @if($category->show_comparison && $comparableTractors->count() >= 2)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Compare Tractors') }}</h2>
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                @foreach($comparableTractors->skip(1) as $item)
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4">
                        <span class="text-sm font-medium text-gray-900">{{ $comparableTractors->first()->name }} {{ __('vs') }} {{ $item->name }}</span>
                        <a href="{{ localized_route('tractors.compare', ['slugs' => $comparableTractors->first()->slug.'-vs-'.$item->slug]) }}"
                           class="ml-3 shrink-0 rounded-md bg-brand-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-700">
                            {{ __('Compare') }}
                        </a>
                    </div>
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
