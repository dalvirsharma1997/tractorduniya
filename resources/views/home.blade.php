@extends('layouts.app')

@section('content')
    @if($banners->isNotEmpty())
        <section class="relative overflow-hidden" id="banner-slider">
            <div class="relative aspect-[8/3] w-full sm:aspect-[16/5]">
                @foreach($banners as $i => $banner)
                    <div class="banner-slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
                         data-index="{{ $i }}">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($banner->image_path) }}" alt="{{ $banner->title }}"
                             class="h-full w-full object-cover">
                        <div class="absolute inset-0 bg-black/30"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                                <div class="max-w-xl text-white">
                                    <h2 class="text-xl font-bold sm:text-3xl">{{ $banner->title }}</h2>
                                    @if($banner->subtitle)
                                        <p class="mt-2 text-sm text-white/90 sm:text-base">{{ $banner->subtitle }}</p>
                                    @endif
                                    @if($banner->link_url)
                                        <a href="{{ $banner->link_url }}"
                                           class="mt-4 inline-block rounded-md bg-white px-5 py-2 text-sm font-semibold text-brand-700 hover:bg-brand-50">
                                            {{ $banner->button_text ?: 'Learn More' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($banners->count() > 1)
                <button type="button" onclick="bannerGo(-1)" aria-label="Previous slide"
                        class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-black/30 p-2 text-white hover:bg-black/50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button type="button" onclick="bannerGo(1)" aria-label="Next slide"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-black/30 p-2 text-white hover:bg-black/50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
                <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-2">
                    @foreach($banners as $i => $banner)
                        <button type="button" onclick="bannerShow({{ $i }})" class="banner-dot h-2 w-2 rounded-full {{ $i === 0 ? 'bg-white' : 'bg-white/50' }}" aria-label="Go to slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>

                <script>
                    (function () {
                        let current = 0;
                        const slides = document.querySelectorAll('#banner-slider .banner-slide');
                        const dots = document.querySelectorAll('#banner-slider .banner-dot');
                        let timer;

                        window.bannerShow = function (index) {
                            slides[current].classList.add('opacity-0', 'pointer-events-none');
                            slides[current].classList.remove('opacity-100');
                            dots[current].classList.replace('bg-white', 'bg-white/50');
                            current = (index + slides.length) % slides.length;
                            slides[current].classList.remove('opacity-0', 'pointer-events-none');
                            slides[current].classList.add('opacity-100');
                            dots[current].classList.replace('bg-white/50', 'bg-white');
                            resetTimer();
                        };
                        window.bannerGo = function (delta) { bannerShow(current + delta); };

                        function resetTimer() {
                            clearInterval(timer);
                            timer = setInterval(() => bannerGo(1), 5000);
                        }
                        resetTimer();
                    })();
                </script>
            @endif
        </section>
    @endif

    <section class="bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-5xl">{{ __('Find the right tractor for your farm') }}</h1>
            <p class="mx-auto mt-4 max-w-2xl text-gray-600">{{ __('Compare specs and prices across every major brand, locate dealers near you, and buy or sell used tractors — all in one place.') }}</p>

            <form action="{{ localized_route('tractors.index') }}" method="GET"
                  class="mx-auto mt-8 flex max-w-3xl flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row">
                <select name="brand" class="flex-1 rounded-md border border-gray-300 text-sm">
                    <option value="">{{ __('Any brand') }}</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->slug }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <select name="hp_max" class="flex-1 rounded-md border border-gray-300 text-sm">
                    <option value="">{{ __('Any HP') }}</option>
                    <option value="30">{{ __('Up to :hp HP', ['hp' => 30]) }}</option>
                    <option value="45">{{ __('Up to :hp HP', ['hp' => 45]) }}</option>
                    <option value="60">{{ __('Up to :hp HP', ['hp' => 60]) }}</option>
                    <option value="999">{{ __(':hp HP+', ['hp' => 60]) }}</option>
                </select>
                <select name="price_max" class="flex-1 rounded-md border border-gray-300 text-sm">
                    <option value="">{{ __('Any budget') }}</option>
                    <option value="500000">{{ __('Up to ₹:amount Lakh', ['amount' => 5]) }}</option>
                    <option value="800000">{{ __('Up to ₹:amount Lakh', ['amount' => 8]) }}</option>
                    <option value="1200000">{{ __('Up to ₹:amount Lakh', ['amount' => 12]) }}</option>
                </select>
                <button type="submit" class="rounded-md bg-brand-600 px-6 py-2 text-sm font-semibold text-white hover:bg-brand-700">
                    {{ __('Search') }}
                </button>
            </form>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500">
                <span>{{ __(':count+ tractor models', ['count' => \App\Models\Tractor::count()]) }}</span>
                <span>{{ __(':count+ verified dealers', ['count' => \App\Models\Dealer::where('is_verified', true)->count()]) }}</span>
                <span>{{ __(':count+ implements', ['count' => \App\Models\Implement::count()]) }}</span>
            </div>
        </div>
    </section>

    @if($brands->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">{{ __('Shop by Brand') }}</h2>
                <a href="{{ localized_route('tractors.index') }}" class="text-sm font-medium text-brand-700 hover:underline">{{ __('View all') }} &rarr;</a>
            </div>
            <div class="mt-6 grid grid-cols-3 gap-4 sm:grid-cols-4 lg:grid-cols-6">
                @foreach($brands as $brand)
                    <a href="{{ localized_route('brands.show', $brand) }}"
                       class="group flex flex-col items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 text-center transition hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-md">
                        <div class="flex h-14 w-full items-center justify-center">
                            @if($brand->logo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($brand->logo_path) }}" alt="{{ $brand->name }}"
                                     loading="lazy" class="max-h-14 max-w-full object-contain">
                            @else
                                <span class="text-lg font-bold text-gray-300">{{ $brand->name }}</span>
                            @endif
                        </div>
                        <span class="text-xs font-medium text-gray-700 group-hover:text-brand-700">{{ $brand->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Popular Tractors') }}</h2>
            <a href="{{ localized_route('tractors.index') }}" class="text-sm font-medium text-brand-700 hover:underline">{{ __('View all') }} &rarr;</a>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($popularTractors as $tractor)
                @include('tractors.partials.card', ['tractor' => $tractor])
            @empty
                <p class="col-span-full text-gray-500">{{ __('No featured tractors yet.') }}</p>
            @endforelse
        </div>
    </section>

    <section class="bg-gray-50 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Tractors by Budget') }}</h2>
            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach($budgetRanges as [$label, $priceMin, $priceMax])
                    <a href="{{ localized_route('tractors.index', array_filter(['price_min' => $priceMin, 'price_max' => $priceMax])) }}"
                       class="rounded-lg border border-gray-200 bg-white px-4 py-5 text-center text-sm font-medium text-gray-700 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700">
                        {{ __($label) }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-900">{{ __('Tractors by HP') }}</h2>
        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($hpRanges as [$label, $hpMin, $hpMax])
                <a href="{{ localized_route('tractors.index', array_filter(['hp_min' => $hpMin, 'hp_max' => $hpMax])) }}"
                   class="rounded-lg border border-gray-200 bg-white px-4 py-5 text-center text-sm font-medium text-gray-700 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700">
                    {{ __($label) }}
                </a>
            @endforeach
        </div>
    </section>

    @if($miniTractors->isNotEmpty())
        <section class="bg-gray-50 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Mini Tractors') }}</h2>
                    <a href="{{ localized_route('categories.show', $miniTractorsCategory) }}" class="text-sm font-medium text-brand-700 hover:underline">{{ __('View all') }} &rarr;</a>
                </div>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($miniTractors as $tractor)
                        @include('tractors.partials.card', ['tractor' => $tractor])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-900">{{ __('Latest Launches') }}</h2>
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($latestLaunches as $tractor)
                @include('tractors.partials.card', ['tractor' => $tractor])
            @endforeach
        </div>
    </section>

    @if($popularImplements->isNotEmpty())
        <section class="bg-gray-50 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Tractor Implements') }}</h2>
                    <a href="{{ localized_route('implements.index') }}" class="text-sm font-medium text-brand-700 hover:underline">{{ __('View all') }} &rarr;</a>
                </div>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($popularImplements as $implement)
                        @include('implements.partials.card', ['implement' => $implement])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">{{ __('From the Blog') }}</h2>
            <a href="{{ localized_route('blog.index') }}" class="text-sm font-medium text-brand-700 hover:underline">{{ __('View all') }} &rarr;</a>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
            @forelse($latestPosts as $post)
                <a href="{{ localized_route('blog.show', $post) }}" class="rounded-lg border border-gray-200 p-4 hover:shadow-md">
                    <p class="font-semibold text-gray-900">{{ $post->title }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}</p>
                </a>
            @empty
                <p class="text-gray-500">{{ __('No posts published yet.') }}</p>
            @endforelse
        </div>
    </section>

    <section class="bg-brand-700 py-12 text-center text-white">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold">{{ __('Are you a tractor dealer?') }}</h2>
            <p class="mt-2 text-brand-100">{{ __('List your dealership and get matched with buyers in your district.') }}</p>
            <a href="{{ localized_route('contact') }}" class="mt-4 inline-block rounded-md bg-white px-6 py-2 text-sm font-semibold text-brand-700 hover:bg-brand-50">
                {{ __('Get in touch') }}
            </a>
        </div>
    </section>

    <section class="bg-gray-50 py-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900">{{ __('About TractorDuniya.in') }}</h2>
            <div class="mt-4 space-y-4 text-sm leading-relaxed text-gray-600">
                <p>{{ __('TractorDuniya.in is an online destination built for Indian farmers who want clear, up-to-date information before buying a tractor or implement. Alongside specifications and on-road prices, the platform connects you with verified dealers in your own district, lets you compare models side by side, and gives you a place to buy or sell used tractors directly.') }}</p>

                <p>{{ __('Our tractor listings cover every major brand sold in India — Mahindra, Swaraj, Eicher, Sonalika, Farmtrac, Powertrac, New Holland, John Deere, Massey Ferguson, Kubota and more — with details on horsepower, drive type, engine capacity, and lifting capacity for each model, so you can shortlist by the numbers that matter to your farm.') }}</p>

                <p>{{ __('Looking by category is often the fastest way to narrow things down. Browse tractors by horsepower range, by budget, or jump straight into focused categories such as mini tractors, compact tractors, utility tractors, orchard tractors, and 4WD tractors. Each category page lists only models that fit, with current prices and specs.') }}</p>

                <p>{{ __('Mini tractors in particular suit orchards, vineyards, polyhouses, and smaller plots where a full-size tractor is impractical. They remain capable enough for routine jobs like rotavation, spraying, and light haulage, while staying easy to manoeuvre in tight spaces. We list mini tractor models from leading manufacturers with the same depth of detail as our full tractor catalogue.') }}</p>

                <p>{{ __('Beyond tractors, TractorDuniya.in also covers farm implements — rotavators, cultivators, seed drills, ploughs, and more — along with an EMI calculator to help plan financing and a growing library of articles on subsidy schemes, buying guides, and farm mechanisation.') }}</p>
            </div>
        </div>
    </section>
@endsection
