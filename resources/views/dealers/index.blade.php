@extends('layouts.app')

@section('title', 'Tractor Dealers Near You — Directory by State & Brand')
@section('meta_description', 'Find authorised tractor dealers across India by state, district, and brand.')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">Dealer Directory</h1>

    <form method="GET" class="mt-6 grid grid-cols-2 gap-3 rounded-lg border border-gray-200 p-4 sm:grid-cols-3">
        <select name="state" class="rounded-md border border-gray-300 text-sm">
            <option value="">Any state</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}" @selected((string) request('state') === (string) $state->id)>{{ $state->name }}</option>
            @endforeach
        </select>
        <select name="brand" class="rounded-md border border-gray-300 text-sm">
            <option value="">Any brand</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Filter</button>
    </form>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($dealers as $dealer)
            <a href="{{ route('dealers.show', $dealer) }}" class="rounded-lg border border-gray-200 p-4 hover:shadow-md">
                <p class="font-semibold text-gray-900">{{ $dealer->name }} @if($dealer->is_verified) <span class="text-xs text-brand-700">✓ Verified</span> @endif</p>
                <p class="text-sm text-gray-600">{{ $dealer->city }}, {{ $dealer->state->name }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $dealer->brands->pluck('name')->implode(', ') }}</p>
            </a>
        @empty
            <p class="col-span-full text-gray-500">No dealers match these filters.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $dealers->links() }}</div>
</div>
@endsection
