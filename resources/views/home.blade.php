@extends('layouts.app')

@section('content')
    <section class="bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-5xl">Find the right tractor for your farm</h1>
            <p class="mx-auto mt-4 max-w-2xl text-gray-600">Compare specs and prices across every major brand, locate dealers near you, and buy or sell used tractors — all in one place.</p>

            <form action="{{ route('tractors.index') }}" method="GET"
                  class="mx-auto mt-8 flex max-w-3xl flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row">
                <select name="brand" class="flex-1 rounded-md border-gray-300 text-sm">
                    <option value="">Any brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->slug }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <select name="hp_max" class="flex-1 rounded-md border-gray-300 text-sm">
                    <option value="">Any HP</option>
                    <option value="30">Up to 30 HP</option>
                    <option value="45">Up to 45 HP</option>
                    <option value="60">Up to 60 HP</option>
                    <option value="999">60 HP+</option>
                </select>
                <select name="price_max" class="flex-1 rounded-md border-gray-300 text-sm">
                    <option value="">Any budget</option>
                    <option value="500000">Up to ₹5 lakh</option>
                    <option value="800000">Up to ₹8 lakh</option>
                    <option value="1200000">Up to ₹12 lakh</option>
                </select>
                <button type="submit" class="rounded-md bg-brand-600 px-6 py-2 text-sm font-semibold text-white hover:bg-brand-700">
                    Search
                </button>
            </form>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500">
                <span>{{ \App\Models\Tractor::count() }}+ tractor models</span>
                <span>{{ \App\Models\Dealer::where('is_verified', true)->count() }}+ verified dealers</span>
                <span>{{ \App\Models\Implement::count() }}+ implements</span>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Popular Tractors</h2>
            <a href="{{ route('tractors.index') }}" class="text-sm font-medium text-brand-700 hover:underline">View all &rarr;</a>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($popularTractors as $tractor)
                @include('tractors.partials.card', ['tractor' => $tractor])
            @empty
                <p class="col-span-full text-gray-500">No featured tractors yet.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-gray-50 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900">Latest Launches</h2>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($latestLaunches as $tractor)
                    @include('tractors.partials.card', ['tractor' => $tractor])
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">From the Blog</h2>
            <a href="{{ route('blog.index') }}" class="text-sm font-medium text-brand-700 hover:underline">View all &rarr;</a>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
            @forelse($latestPosts as $post)
                <a href="{{ route('blog.show', $post) }}" class="rounded-lg border border-gray-200 p-4 hover:shadow-md">
                    <p class="font-semibold text-gray-900">{{ $post->title }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}</p>
                </a>
            @empty
                <p class="text-gray-500">No posts published yet.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-brand-700 py-12 text-center text-white">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold">Are you a tractor dealer?</h2>
            <p class="mt-2 text-brand-100">List your dealership and get matched with buyers in your district.</p>
            <a href="{{ route('contact') }}" class="mt-4 inline-block rounded-md bg-white px-6 py-2 text-sm font-semibold text-brand-700 hover:bg-brand-50">
                Get in touch
            </a>
        </div>
    </section>
@endsection
