@extends('layouts.app')

@section('title', 'Farm Implements — Rotavators, Ploughs, Cultivators & More')
@section('meta_description', 'Browse farm implements by type, brand, and compatible tractor HP.')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">Farm Implements</h1>

    <form method="GET" class="mt-6 grid grid-cols-2 gap-3 rounded-lg border border-gray-200 p-4 sm:grid-cols-4">
        <select name="type" class="rounded-md border-gray-300 text-sm">
            <option value="">Any type</option>
            @foreach($types as $type)
                <option value="{{ $type->name }}" @selected(request('type') === $type->name)>{{ $type->name }}</option>
            @endforeach
        </select>
        <select name="brand" class="rounded-md border-gray-300 text-sm">
            <option value="">Any brand</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
            @endforeach
        </select>
        <input type="number" name="hp" placeholder="Your tractor HP" value="{{ request('hp') }}" class="rounded-md border-gray-300 text-sm">
        <button type="submit" class="rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Filter</button>
    </form>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($implements as $implement)
            @include('implements.partials.card', ['implement' => $implement])
        @empty
            <p class="col-span-full text-gray-500">No implements match these filters.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $implements->links() }}</div>
</div>
@endsection
