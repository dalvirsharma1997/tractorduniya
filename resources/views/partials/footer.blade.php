@php
    $siteName = \App\Models\Setting::get('site_name', 'TractorDuniya.in');
    $footerTractors = \App\Models\Tractor::where('status', 'active')
        ->with('brand')
        ->orderByDesc('is_featured')
        ->orderByDesc('launch_year')
        ->limit(12)
        ->get();
    $footerCategories = \App\Models\Category::where('type', 'tractor_category')->orderBy('name')->get();
    $footerMenu = \App\Models\MenuItem::topLevel('footer')->get();
    $facebookUrl = \App\Models\Setting::get('facebook_url');
    $youtubeUrl = \App\Models\Setting::get('youtube_url');
    $instagramUrl = \App\Models\Setting::get('instagram_url');
    $whatsappNumber = \App\Models\Setting::get('whatsapp_number');
@endphp
<footer class="mt-16 border-t border-gray-200 bg-gray-50">
    @if($footerTractors->isNotEmpty())
        <div class="border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-gray-900">{{ __('Top Tractors') }}</p>
                <div class="mt-4 grid grid-cols-2 gap-x-6 gap-y-2 text-sm text-gray-600 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($footerTractors as $tractor)
                        <a href="{{ localized_route('tractors.show', [$tractor->brand, $tractor]) }}" class="truncate hover:text-brand-700">
                            {{ $tractor->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 py-12 sm:px-6 md:grid-cols-3 lg:grid-cols-6 lg:px-8">
        <div class="col-span-2 md:col-span-1 lg:col-span-2">
            <p class="text-lg font-bold text-brand-700">{{ $siteName }}</p>
            <p class="mt-2 text-sm text-gray-600">{{ __("India's tractor marketplace — compare models, find dealers, and buy or sell used equipment.") }}</p>

            @if($facebookUrl || $youtubeUrl || $instagramUrl)
                <div class="mt-4 flex items-center gap-3">
                    @if($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" aria-label="Facebook"
                           class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg>
                        </a>
                    @endif
                    @if($instagramUrl)
                        <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" aria-label="Instagram"
                           class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.58.01 4.85.07 1.17.05 1.97.24 2.43.4a4.9 4.9 0 0 1 1.77 1.15 4.9 4.9 0 0 1 1.15 1.77c.16.46.35 1.26.4 2.43.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.24 1.97-.4 2.43a4.9 4.9 0 0 1-1.15 1.77 4.9 4.9 0 0 1-1.77 1.15c-.46.16-1.26.35-2.43.4-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.97-.24-2.43-.4a4.9 4.9 0 0 1-1.77-1.15 4.9 4.9 0 0 1-1.15-1.77c-.16-.46-.35-1.26-.4-2.43C2.21 15.58 2.2 15.2 2.2 12s.01-3.58.07-4.85c.05-1.17.24-1.97.4-2.43a4.9 4.9 0 0 1 1.15-1.77A4.9 4.9 0 0 1 5.59 1.8c.46-.16 1.26-.35 2.43-.4C9.29 1.34 9.67 1.33 12 1.33Zm0 1.98c-3.15 0-3.5.01-4.74.07-1.03.05-1.6.22-1.97.36-.5.19-.85.42-1.22.8a3.3 3.3 0 0 0-.8 1.22c-.14.37-.3.94-.36 1.97-.06 1.24-.07 1.6-.07 4.75s.01 3.5.07 4.75c.05 1.03.22 1.6.36 1.97.19.5.42.85.8 1.22.37.38.72.6 1.22.8.37.14.94.3 1.97.36 1.24.06 1.6.07 4.74.07s3.5-.01 4.74-.07c1.03-.05 1.6-.22 1.97-.36a3.3 3.3 0 0 0 1.22-.8c.38-.37.6-.72.8-1.22.14-.37.3-.94.36-1.97.06-1.24.07-1.6.07-4.75s-.01-3.5-.07-4.75c-.05-1.03-.22-1.6-.36-1.97a3.3 3.3 0 0 0-.8-1.22 3.3 3.3 0 0 0-1.22-.8c-.37-.14-.94-.3-1.97-.36-1.24-.06-1.6-.07-4.74-.07Zm0 3.38a5.44 5.44 0 1 1 0 10.88 5.44 5.44 0 0 1 0-10.88Zm0 8.98a3.54 3.54 0 1 0 0-7.08 3.54 3.54 0 0 0 0 7.08Zm6.94-9.2a1.27 1.27 0 1 1-2.54 0 1.27 1.27 0 0 1 2.54 0Z"/></svg>
                        </a>
                    @endif
                    @if($youtubeUrl)
                        <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" aria-label="YouTube"
                           class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.5 7.2a3.02 3.02 0 0 0-2.12-2.14C19.5 4.6 12 4.6 12 4.6s-7.5 0-9.38.46A3.02 3.02 0 0 0 .5 7.2 31.6 31.6 0 0 0 .04 12a31.6 31.6 0 0 0 .46 4.8 3.02 3.02 0 0 0 2.12 2.14C4.5 19.4 12 19.4 12 19.4s7.5 0 9.38-.46a3.02 3.02 0 0 0 2.12-2.14A31.6 31.6 0 0 0 23.96 12a31.6 31.6 0 0 0-.46-4.8ZM9.7 15.1V8.9l6.2 3.1-6.2 3.1Z"/></svg>
                        </a>
                    @endif
                </div>
            @endif

            @if($whatsappNumber)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $whatsappNumber) }}" target="_blank" rel="noopener"
                   class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-green-700 hover:underline">
                    {{ __('Chat on WhatsApp') }}
                </a>
            @endif
        </div>

        @foreach($footerMenu as $column)
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ __($column->label) }}</p>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    @foreach($column->children as $link)
                        <li><a href="{{ $link->url }}" @if($link->opens_new_tab) target="_blank" rel="noopener" @endif class="hover:text-brand-700">{{ __($link->label) }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        @if($footerCategories->isNotEmpty())
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ __('Tractors by Category') }}</p>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    @foreach($footerCategories as $category)
                        <li><a href="{{ localized_route('categories.show', $category) }}" class="hover:text-brand-700">{{ $category->name }}</a></li>
                    @endforeach
                    @if($phone = \App\Models\Setting::get('contact_phone'))
                        <li class="pt-1 text-gray-500">{{ $phone }}</li>
                    @endif
                    @if($email = \App\Models\Setting::get('contact_email'))
                        <li class="text-gray-500">{{ $email }}</li>
                    @endif
                </ul>
            </div>
        @endif
    </div>

    <div class="border-t border-gray-200 py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} {{ $siteName }}. {{ __('All rights reserved.') }}
    </div>
</footer>
