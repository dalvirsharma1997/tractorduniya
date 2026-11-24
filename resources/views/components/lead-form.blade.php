@props(['tractor' => null, 'implement' => null, 'heading' => null])

@php
    $modalId = 'lead-modal-'.Str::random(8);
    $statesWithDistricts = \App\Models\State::with('districts:id,state_id,name')->orderBy('name')->get(['id', 'name']);
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-gray-50 p-6 text-center']) }}>
    <h3 class="text-lg font-semibold text-gray-900">{{ $heading ?? __('Get the best price') }}</h3>
    <p class="mt-1 text-sm text-gray-600">{{ __('Share your details and a dealer near you will get in touch.') }}</p>

    @if (session('lead_status'))
        <div class="mt-4 rounded-md bg-brand-50 p-3 text-sm text-brand-800">
            {{ session('lead_message') }}
        </div>
    @else
        <button type="button" onclick="document.getElementById('{{ $modalId }}').classList.remove('hidden'); document.getElementById('{{ $modalId }}').classList.add('flex')"
                class="mt-4 w-full rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
            {{ __('Get Best Price') }}
        </button>
    @endif
</div>

@unless (session('lead_status'))
    <div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
            <button type="button" onclick="document.getElementById('{{ $modalId }}').classList.add('hidden'); document.getElementById('{{ $modalId }}').classList.remove('flex')"
                    aria-label="{{ __('Close') }}"
                    class="absolute right-4 top-4 text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-lg font-semibold text-gray-900">{{ $heading ?? __('Get the best price') }}</h3>
            <p class="mt-1 text-sm text-gray-600">{{ __('Share your details and a dealer near you will get in touch.') }}</p>

            <form method="POST" action="{{ localized_route('leads.store') }}" class="mt-4 space-y-3">
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
                    <input type="text" name="name" required placeholder="{{ __('Your name') }}"
                           class="w-full rounded-md border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <input type="tel" name="phone" required placeholder="{{ __('Mobile number') }}"
                           class="w-full rounded-md border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                    @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <select name="state_id" id="{{ $modalId }}-state"
                            onchange="window.filterDistricts_{{ str_replace('-', '_', $modalId) }}(this.value)"
                            class="w-full rounded-md border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">{{ __('Select State') }}</option>
                        @foreach($statesWithDistricts as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                    <select name="district_id" id="{{ $modalId }}-district"
                            class="w-full rounded-md border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">{{ __('Select District') }}</option>
                    </select>
                </div>

                <div>
                    <input type="text" name="tehsil" placeholder="{{ __('Tehsil / Taluka (optional)') }}"
                           class="w-full rounded-md border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                </div>

                <label class="flex items-start gap-2 text-xs text-gray-500">
                    <input type="checkbox" required class="mt-0.5 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span>{{ __('By submitting, you agree to be contacted about this enquiry.') }}</span>
                </label>

                <button type="submit"
                        class="w-full rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
                    {{ __('Get Best Price') }}
                </button>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const districtsByState = {!! $statesWithDistricts->mapWithKeys(fn ($s) => [
                $s->id => $s->districts->map(fn ($d) => ['id' => $d->id, 'name' => $d->name])->values(),
            ])->toJson() !!};

            window.filterDistricts_{{ str_replace('-', '_', $modalId) }} = function (stateId) {
                const select = document.getElementById('{{ $modalId }}-district');
                select.innerHTML = '<option value="">{{ __('Select District') }}</option>';
                (districtsByState[stateId] || []).forEach(function (d) {
                    const opt = document.createElement('option');
                    opt.value = d.id;
                    opt.textContent = d.name;
                    select.appendChild(opt);
                });
            };
        })();
    </script>
@endunless
