@extends('layouts.app')

@section('title', $brand->name . ' Tractors — All Models, Price & Specs')
@section('meta_description', 'Browse all ' . $brand->name . ' tractor models with specifications and prices.')
@section('seo_type', 'brand')
@section('seo_id', $brand->id)

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <x-breadcrumbs :items="[
        ['name' => 'Tractors', 'url' => route('tractors.index')],
        ['name' => $brand->name, 'url' => null],
    ]" />

    <div class="mt-4 flex items-center gap-4">
        @if($brand->logo_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($brand->logo_path) }}" alt="{{ $brand->name }}" class="h-12 w-12 rounded object-contain">
        @endif
        <h1 class="text-2xl font-bold text-gray-900">{{ $brand->name }} {{ __('Tractors') }}</h1>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-4">
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($tractors as $tractor)
                    @include('tractors.partials.card', ['tractor' => $tractor])
                @empty
                    <p class="col-span-full text-gray-500">{{ __('No :brand models published yet.', ['brand' => $brand->name]) }}</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $tractors->links() }}</div>
        </div>

        @if($relatedBrands->isNotEmpty())
            <aside class="lg:col-span-1">
                <div class="rounded-xl border border-gray-200 p-5">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">{{ __('Related Brands') }}</p>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach($relatedBrands as $related)
                            <a href="{{ localized_route('brands.show', $related) }}"
                               class="group flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-3 text-center transition hover:border-brand-200 hover:shadow-sm">
                                <div class="flex h-10 w-full items-center justify-center">
                                    @if($related->logo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($related->logo_path) }}" alt="{{ $related->name }}"
                                             loading="lazy" class="max-h-10 max-w-full object-contain">
                                    @else
                                        <span class="text-xs font-semibold text-gray-400">{{ $related->name }}</span>
                                    @endif
                                </div>
                                <span class="text-xs font-medium text-gray-700 group-hover:text-brand-700">{{ $related->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        @endif
    </div>

    @if($brandImplements->isNotEmpty())
        <div class="mt-12">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">{{ __('Implements') }}</h2>
                <a href="{{ localized_route('implements.index') }}" class="text-sm font-medium text-brand-700 hover:underline">{{ __('View all') }} &rarr;</a>
            </div>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($brandImplements as $implement)
                    @include('implements.partials.card', ['implement' => $implement])
                @endforeach
            </div>
        </div>
    @endif

    @if($brand->show_comparison && $comparableTractors->count() >= 2)
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

    <section class="mt-12 border-t border-gray-200 pt-8">
        <h2 class="text-xl font-bold text-gray-900">{{ __('About :brand', ['brand' => $brand->name]) }}</h2>
        <div class="prose prose-gray mt-4 max-w-none text-gray-600">
            @if($brand->footer_content)
                {!! $brand->footer_content !!}
            @elseif($brand->description)
                <p>{{ $brand->description }}</p>
            @else
                <p>{{ __(':brand is one of the tractor brands listed on TractorDuniya.in, with models spanning a range of horsepower and drive-type options to suit different farm sizes and jobs. Every model listed here shows horsepower, engine capacity, drive type, and the price range reported for it, so you can compare it directly against other brands before deciding.', ['brand' => $brand->name]) }}</p>
            @endif
        </div>
    </section>
</div>
@endsection
