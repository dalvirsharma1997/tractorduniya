@extends('layouts.app')

@section('title', 'Buy Used Tractors & Implements')
@section('meta_description', 'Browse approved used tractor and implement listings from sellers across India.')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Used Tractors & Implements</h1>
        <a href="{{ route('used-listings.create') }}" class="rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
            Sell yours
        </a>
    </div>

    @if(session('listing_submitted'))
        <div class="mt-4 rounded-md bg-brand-50 p-3 text-sm text-brand-800">{{ session('listing_submitted') }}</div>
    @endif

    <form method="GET" class="mt-6 grid grid-cols-2 gap-3 rounded-lg border border-gray-200 p-4 sm:grid-cols-3">
        <select name="type" class="rounded-md border-gray-300 text-sm">
            <option value="">Tractor or Implement</option>
            <option value="tractor" @selected(request('type') === 'tractor')>Tractor</option>
            <option value="implement" @selected(request('type') === 'implement')>Implement</option>
        </select>
        <select name="state" class="rounded-md border-gray-300 text-sm">
            <option value="">Any state</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}" @selected((string) request('state') === (string) $state->id)>{{ $state->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Filter</button>
    </form>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($listings as $listing)
            @php $image = $listing->images->first(); @endphp
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="aspect-[4/3] overflow-hidden rounded-md bg-gray-100">
                    @if($image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}" loading="lazy" class="h-full w-full object-cover">
                    @endif
                </div>
                <p class="mt-3 font-semibold text-gray-900">
                    {{ $listing->tractor?->name ?? $listing->implement?->name ?? ucfirst($listing->listing_type) }}
                </p>
                <p class="text-sm text-gray-600">{{ $listing->city }}, {{ $listing->state?->name }} · {{ ucfirst($listing->condition) }}</p>
                <p class="mt-1 text-sm font-medium text-brand-700">₹{{ number_format($listing->asking_price) }}</p>
                <p class="mt-2 text-xs text-gray-500">Seller: {{ $listing->seller_name }} · <a href="tel:{{ $listing->seller_phone }}" class="hover:underline">{{ $listing->seller_phone }}</a></p>
            </div>
        @empty
            <p class="col-span-full text-gray-500">No approved listings yet. Be the first to <a href="{{ route('used-listings.create') }}" class="text-brand-700 hover:underline">sell yours</a>.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $listings->links() }}</div>
</div>
@endsection
