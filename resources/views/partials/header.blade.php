<header class="border-b border-gray-200 bg-white sticky top-0 z-40">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold text-brand-700">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-brand-600 text-white">T</span>
            TractorDuniya<span class="text-gray-900">.in</span>
        </a>

        <nav class="hidden items-center gap-6 text-sm font-medium text-gray-700 md:flex">
            <a href="{{ route('tractors.index') }}" class="hover:text-brand-700">Tractors</a>
            <a href="{{ route('implements.index') }}" class="hover:text-brand-700">Implements</a>
            <a href="{{ route('used-listings.index') }}" class="hover:text-brand-700">Used Tractors</a>
            <a href="{{ route('dealers.index') }}" class="hover:text-brand-700">Dealers</a>
            <a href="{{ route('blog.index') }}" class="hover:text-brand-700">Blog</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('used-listings.create') }}"
               class="hidden rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700 sm:inline-block">
                Sell your tractor
            </a>
            <button type="button" onclick="document.getElementById('mobile-nav').classList.toggle('hidden'); document.getElementById('mobile-nav').classList.toggle('flex')" class="md:hidden" aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <nav id="mobile-nav" class="hidden flex-col gap-2 border-t border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 md:hidden">
        <a href="{{ route('tractors.index') }}" class="py-1">Tractors</a>
        <a href="{{ route('implements.index') }}" class="py-1">Implements</a>
        <a href="{{ route('used-listings.index') }}" class="py-1">Used Tractors</a>
        <a href="{{ route('dealers.index') }}" class="py-1">Dealers</a>
        <a href="{{ route('blog.index') }}" class="py-1">Blog</a>
        <a href="{{ route('used-listings.create') }}" class="py-1 font-semibold text-brand-700">Sell your tractor</a>
    </nav>
</header>
