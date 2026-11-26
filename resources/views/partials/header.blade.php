@php($headerMenu = \App\Models\MenuItem::topLevel('header')->get())
<header class="border-b border-gray-200 bg-white sticky top-0 z-40">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ localized_route('home') }}" class="flex items-center gap-2 text-xl font-bold text-brand-700">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-brand-600 text-white">T</span>
            TractorDuniya<span class="text-gray-900">.in</span>
        </a>

        <nav class="hidden items-center gap-6 text-sm font-medium text-gray-700 md:flex">
            @foreach($headerMenu as $item)
                @if($item->children->isNotEmpty())
                    <div class="group relative">
                        <button type="button" class="flex items-center gap-1 hover:text-brand-700">
                            {{ __($item->label) }}
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="invisible absolute left-0 top-full z-10 w-56 rounded-lg border border-gray-200 bg-white py-2 opacity-0 shadow-lg transition group-hover:visible group-hover:opacity-100">
                            @foreach($item->children as $child)
                                <a href="{{ $child->url }}" @if($child->opens_new_tab) target="_blank" rel="noopener" @endif
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-700">
                                    {{ __($child->label) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item->url }}" @if($item->opens_new_tab) target="_blank" rel="noopener" @endif class="hover:text-brand-700">{{ __($item->label) }}</a>
                @endif
            @endforeach
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

    <nav id="mobile-nav" class="hidden flex-col gap-1 border-t border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 md:hidden">
        @foreach($headerMenu as $item)
            @if($item->children->isNotEmpty())
                <p class="py-1 pt-2 text-xs font-semibold uppercase text-gray-400">{{ __($item->label) }}</p>
                @foreach($item->children as $child)
                    <a href="{{ $child->url }}" @if($child->opens_new_tab) target="_blank" rel="noopener" @endif class="py-1 pl-2">{{ __($child->label) }}</a>
                @endforeach
            @else
                <a href="{{ $item->url }}" @if($item->opens_new_tab) target="_blank" rel="noopener" @endif class="py-1">{{ __($item->label) }}</a>
            @endif
        @endforeach
        <a href="{{ localized_route('used-listings.create') }}" class="py-1 font-semibold text-brand-700">{{ __('Sell your tractor') }}</a>
        @if(isset($alternates['en']) || isset($alternates['hi']))
            <div class="mt-2 flex gap-2 border-t border-gray-100 pt-2">
                @if(isset($alternates['en']))<a href="{{ $alternates['en'] }}">EN</a>@endif
                @if(isset($alternates['hi']))<a href="{{ $alternates['hi'] }}">हिंदी</a>@endif
            </div>
        @endif
    </nav>
</header>
