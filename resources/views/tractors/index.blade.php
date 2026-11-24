@extends('layouts.app')

@section('title', 'Tractors in India — Compare Models, Specs & Prices')
@section('meta_description', 'Browse and compare tractor models by brand, HP, price, and state — all major brands sold in India.')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ __('All Tractors') }}</h1>

    <form method="GET" class="mt-6 grid grid-cols-2 gap-3 rounded-lg border border-gray-200 p-4 sm:grid-cols-3 lg:grid-cols-6">
        <select name="brand" class="rounded-md border border-gray-300 text-sm">
            <option value="">{{ __('Any brand') }}</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
            @endforeach
        </select>
        <input type="number" name="hp_min" placeholder="{{ __('Min HP') }}" value="{{ request('hp_min') }}" class="rounded-md border border-gray-300 text-sm">
        <input type="number" name="hp_max" placeholder="{{ __('Max HP') }}" value="{{ request('hp_max') }}" class="rounded-md border border-gray-300 text-sm">
        <input type="number" name="price_max" placeholder="{{ __('Max price (₹)') }}" value="{{ request('price_max') }}" class="rounded-md border border-gray-300 text-sm">
        <select name="drive_type" class="rounded-md border border-gray-300 text-sm">
            <option value="">{{ __('2WD / 4WD') }}</option>
            <option value="2WD" @selected(request('drive_type') === '2WD')>2WD</option>
            <option value="4WD" @selected(request('drive_type') === '4WD')>4WD</option>
        </select>
        <select name="state" class="rounded-md border border-gray-300 text-sm">
            <option value="">{{ __('Any state') }}</option>
            @foreach($states as $state)
                <option value="{{ $state->slug }}" @selected(request('state') === $state->slug)>{{ $state->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="col-span-2 rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700 sm:col-span-1">
            {{ __('Filter') }}
        </button>
    </form>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($tractors as $tractor)
            @include('tractors.partials.card', ['tractor' => $tractor])
        @empty
            <p class="col-span-full text-gray-500">{{ __('No tractors match these filters.') }}</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $tractors->links() }}
    </div>
</div>
@endsection
