@props(['faqs', 'heading' => null])

@if($faqs->isNotEmpty())
    <section class="mt-12 border-t border-gray-200 pt-8">
        <h2 class="text-xl font-bold text-gray-900">{{ $heading ?? __('Frequently Asked Questions') }}</h2>
        <div class="mt-4 divide-y divide-gray-100">
            @foreach($faqs as $faq)
                <details class="group py-3">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-medium text-gray-900">
                        {{ $faq->question }}
                        <svg class="h-4 w-4 shrink-0 text-gray-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="mt-2 text-sm text-gray-600">{{ $faq->answer }}</p>
                </details>
            @endforeach
        </div>
    </section>
@endif
