@php($siteName = \App\Models\Setting::get('site_name', 'TractorDuniya.in'))
<footer class="mt-16 border-t border-gray-200 bg-gray-50">
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 py-12 sm:px-6 md:grid-cols-4 lg:px-8">
        <div class="col-span-2 md:col-span-1">
            <p class="text-lg font-bold text-brand-700">{{ $siteName }}</p>
            <p class="mt-2 text-sm text-gray-600">{{ __("India's tractor marketplace — compare models, find dealers, and buy or sell used equipment.") }}</p>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900">{{ __('Browse') }}</p>
            <ul class="mt-3 space-y-2 text-sm text-gray-600">
                <li><a href="{{ localized_route('tractors.index') }}" class="hover:text-brand-700">{{ __('All Tractors') }}</a></li>
                <li><a href="{{ localized_route('implements.index') }}" class="hover:text-brand-700">{{ __('Implements') }}</a></li>
                <li><a href="{{ localized_route('used-listings.index') }}" class="hover:text-brand-700">{{ __('Used Tractors') }}</a></li>
                <li><a href="{{ localized_route('dealers.index') }}" class="hover:text-brand-700">{{ __('Dealer Directory') }}</a></li>
            </ul>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900">{{ __('Resources') }}</p>
            <ul class="mt-3 space-y-2 text-sm text-gray-600">
                <li><a href="{{ localized_route('blog.index') }}" class="hover:text-brand-700">{{ __('Blog') }}</a></li>
                <li><a href="{{ localized_route('news.index') }}" class="hover:text-brand-700">{{ __('News') }}</a></li>
                <li><a href="{{ localized_route('emi-calculator') }}" class="hover:text-brand-700">{{ __('EMI Calculator') }}</a></li>
                <li><a href="{{ localized_route('faqs') }}" class="hover:text-brand-700">{{ __('FAQs') }}</a></li>
            </ul>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900">{{ __('Contact') }}</p>
            <ul class="mt-3 space-y-2 text-sm text-gray-600">
                @if($phone = \App\Models\Setting::get('contact_phone'))
                    <li>{{ $phone }}</li>
                @endif
                @if($email = \App\Models\Setting::get('contact_email'))
                    <li>{{ $email }}</li>
                @endif
                <li><a href="{{ localized_route('about') }}" class="hover:text-brand-700">{{ __('About Us') }}</a></li>
                <li><a href="{{ localized_route('contact') }}" class="hover:text-brand-700">{{ __('Contact') }}</a></li>
                <li><a href="{{ localized_route('privacy') }}" class="hover:text-brand-700">{{ __('Privacy Policy') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gray-200 py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} {{ $siteName }}. {{ __('All rights reserved.') }}
    </div>
</footer>
