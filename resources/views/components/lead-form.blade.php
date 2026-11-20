@props(['tractor' => null, 'implement' => null, 'heading' => 'Get the best price'])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-gray-50 p-6']) }}>
    <h3 class="text-lg font-semibold text-gray-900">{{ $heading }}</h3>
    <p class="mt-1 text-sm text-gray-600">Share your details and a dealer near you will get in touch.</p>

    @if (session('lead_status'))
        <div class="mt-4 rounded-md bg-brand-50 p-3 text-sm text-brand-800">
            {{ session('lead_message') }}
        </div>
    @else
        <form method="POST" action="{{ route('leads.store') }}" class="mt-4 space-y-3">
            @csrf
            @if($tractor)
                <input type="hidden" name="interested_tractor_id" value="{{ $tractor->id }}">
            @endif
            @if($implement)
                <input type="hidden" name="interested_implement_id" value="{{ $implement->id }}">
            @endif
            <input type="hidden" name="source" value="{{ url()->current() }}">
            <input type="hidden" name="utm_campaign" value="{{ request('utm_campaign') }}">
            <input type="hidden" name="utm_medium" value="{{ request('utm_medium') }}">
            <input type="hidden" name="utm_source" value="{{ request('utm_source') }}">

            {{-- Honeypot field — hidden from real users, bots tend to fill every input. --}}
            <input type="text" name="website" tabindex="-1" autocomplete="off"
                   class="absolute left-[-9999px]" aria-hidden="true">

            <div>
                <input type="text" name="name" required placeholder="Your name"
                       class="w-full rounded-md border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <input type="tel" name="phone" required placeholder="Mobile number"
                       class="w-full rounded-md border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <input type="text" name="city" placeholder="City (optional)"
                       class="w-full rounded-md border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
            </div>
            <button type="submit"
                    class="w-full rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
                Get Best Price
            </button>
            <p class="text-center text-xs text-gray-500">By submitting, you agree to be contacted about this enquiry.</p>
        </form>
    @endif
</div>
