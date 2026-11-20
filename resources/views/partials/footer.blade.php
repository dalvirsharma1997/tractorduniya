@php($siteName = \App\Models\Setting::get('site_name', 'TractorDuniya.in'))
<footer class="mt-16 border-t border-gray-200 bg-gray-50">
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 py-12 sm:px-6 md:grid-cols-4 lg:px-8">
        <div class="col-span-2 md:col-span-1">
            <p class="text-lg font-bold text-brand-700">{{ $siteName }}</p>
            <p class="mt-2 text-sm text-gray-600">India's tractor marketplace — compare models, find dealers, and buy or sell used equipment.</p>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Browse</p>
            <ul class="mt-3 space-y-2 text-sm text-gray-600">
                <li><a href="{{ route('tractors.index') }}" class="hover:text-brand-700">All Tractors</a></li>
                <li><a href="{{ route('implements.index') }}" class="hover:text-brand-700">Implements</a></li>
                <li><a href="{{ route('used-listings.index') }}" class="hover:text-brand-700">Used Tractors</a></li>
                <li><a href="{{ route('dealers.index') }}" class="hover:text-brand-700">Dealer Directory</a></li>
            </ul>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Resources</p>
            <ul class="mt-3 space-y-2 text-sm text-gray-600">
                <li><a href="{{ route('blog.index') }}" class="hover:text-brand-700">Blog</a></li>
                <li><a href="{{ route('news.index') }}" class="hover:text-brand-700">News</a></li>
                <li><a href="{{ route('emi-calculator') }}" class="hover:text-brand-700">EMI Calculator</a></li>
                <li><a href="{{ route('faqs') }}" class="hover:text-brand-700">FAQs</a></li>
            </ul>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Contact</p>
            <ul class="mt-3 space-y-2 text-sm text-gray-600">
                @if($phone = \App\Models\Setting::get('contact_phone'))
                    <li>{{ $phone }}</li>
                @endif
                @if($email = \App\Models\Setting::get('contact_email'))
                    <li>{{ $email }}</li>
                @endif
                <li><a href="{{ route('about') }}" class="hover:text-brand-700">About Us</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-brand-700">Contact</a></li>
                <li><a href="{{ route('privacy') }}" class="hover:text-brand-700">Privacy Policy</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gray-200 py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
    </div>
</footer>
