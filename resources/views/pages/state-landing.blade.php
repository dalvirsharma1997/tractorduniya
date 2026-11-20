@extends('layouts.app')

@section('title', 'Tractors in ' . $state->name . ' — Prices & Dealers')
@section('meta_description', 'Browse tractor prices, models, and dealers in ' . $state->name . '.')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900">Tractors in {{ $state->name }}</h1>
    <p class="mt-2 text-gray-600">Compare tractor prices and specs for {{ $state->name }}, and find authorised dealers near you.</p>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($tractors as $tractor)
            <a href="{{ route('tractors.show', [$tractor->brand, $tractor]) }}" class="rounded-lg border border-gray-200 p-4 hover:shadow-md">
                <p class="font-semibold text-gray-900">{{ $tractor->name }}</p>
                <p class="text-sm text-gray-600">{{ $tractor->brand->name }} · {{ $tractor->hp }} HP</p>
            </a>
        @endforeach
    </div>

    <div class="mt-10">
        <a href="{{ route('dealers.index', ['state' => $state->id]) }}" class="text-brand-700 font-medium hover:underline">
            View all dealers in {{ $state->name }} &rarr;
        </a>
    </div>
</div>
@endsection
