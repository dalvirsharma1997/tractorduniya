<header class="border-b border-gray-200 bg-white sticky top-0 z-40">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ localized_route('home') }}" class="flex items-center gap-2 text-xl font-bold text-brand-700">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-brand-600 text-white">T</span>
            TractorDuniya<span class="text-gray-900">.in</span>
        </a>

        <nav class="hidden items-center gap-6 text-sm font-medium text-gray-700 md:flex">
            <a href="{{ localized_route('tractors.index') }}" class="hover:text-brand-700">{{ __('Tractors') }}</a>
            <a href="{{ localized_route('implements.index') }}" class="hover:text-brand-700">{{ __('Implements') }}</a>
            <a href="{{ localized_route('used-listings.index') }}" class="hover:text-brand-700">{{ __('Used Tractors') }}</a>
            <a href="{{ localized_route('dealers.index') }}" class="hover:text-brand-700">{{ __('Dealers') }}</a>
            <a href="{{ localized_route('blog.index') }}" class="hover:text-brand-700">{{ __('Blog') }}</a>
        </nav>

        <div class="flex items-center gap-3">
            @if(isset($alternates['en']) || isset($alternates['hi']))
                <div class="hidden items-center gap-1 text-xs font-medium text-gray-500 sm:flex">
                    @if(isset($alternates['en']))
                        <a href="{{ $alternates['en'] }}" class="{{ app()->getLocale() === 'en' ? 'text-brand-700' : 'hover:text-brand-700' }}">EN</a>
                    @endif
                    @if(isset($alternates['en']) && isset($alternates['hi']))
                        <span>|</span>
                    @endif
                    @if(isset($alternates['hi']))
                        <a href="{{ $alternates['hi'] }}" class="{{ app()->getLocale() === 'hi' ? 'text-brand-700' : 'hover:text-brand-700' }}">हिंदी</a>
                    @endif
                </div>
            @endif
            <a href="{{ localized_route('used-listings.create') }}"
               class="hidden rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700 sm:inline-block">
                {{ __('Sell your tractor') }}
            </a>
            <button type="button" onclick="document.getElementById('mobile-nav').classList.toggle('hidden'); document.getElementById('mobile-nav').classList.toggle('flex')" class="md:hidden" aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <nav id="mobile-nav" class="hidden flex-col gap-2 border-t border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 md:hidden">
        <a href="{{ localized_route('tractors.index') }}" class="py-1">{{ __('Tractors') }}</a>
        <a href="{{ localized_route('implements.index') }}" class="py-1">{{ __('Implements') }}</a>
        <a href="{{ localized_route('used-listings.index') }}" class="py-1">{{ __('Used Tractors') }}</a>
        <a href="{{ localized_route('dealers.index') }}" class="py-1">{{ __('Dealers') }}</a>
        <a href="{{ localized_route('blog.index') }}" class="py-1">{{ __('Blog') }}</a>
        <a href="{{ localized_route('used-listings.create') }}" class="py-1 font-semibold text-brand-700">{{ __('Sell your tractor') }}</a>
        @if(isset($alternates['en']) || isset($alternates['hi']))
            <div class="mt-2 flex gap-2 border-t border-gray-100 pt-2">
                @if(isset($alternates['en']))<a href="{{ $alternates['en'] }}">EN</a>@endif
                @if(isset($alternates['hi']))<a href="{{ $alternates['hi'] }}">हिंदी</a>@endif
            </div>
        @endif
    </nav>
</header>
