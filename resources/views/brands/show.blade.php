@extends('layouts.app')

@section('title', $brand->name . ' Tractors — All Models, Price & Specs')
@section('meta_description', 'Browse all ' . $brand->name . ' tractor models with specifications and prices.')

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
        <h1 class="text-2xl font-bold text-gray-900">{{ $brand->name }} Tractors</h1>
    </div>
    @if($brand->description)
        <p class="mt-2 max-w-3xl text-gray-600">{{ $brand->description }}</p>
    @endif

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($tractors as $tractor)
            @include('tractors.partials.card', ['tractor' => $tractor])
        @empty
            <p class="col-span-full text-gray-500">No {{ $brand->name }} models published yet.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $tractors->links() }}</div>
</div>
@endsection
