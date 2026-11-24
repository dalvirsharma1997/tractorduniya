@props(['tractor' => null, 'implement' => null, 'heading' => null])

@php
    $modalId = 'lead-modal-'.Str::random(8);
    $statesWithDistricts = \App\Models\State::with('districts:id,state_id,name', 'districts.tehsils:id,district_id,name')->orderBy('name')->get(['id', 'name']);
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
                class="mt-4 w-full rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">
            {{ __('Get Best Price') }}
        </button>
    @endif
</div>

@unless (session('lead_status'))
    <div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-8 shadow-xl">
            <button type="button" onclick="document.getElementById('{{ $modalId }}').classList.add('hidden'); document.getElementById('{{ $modalId }}').classList.remove('flex')"
                    aria-label="{{ __('Close') }}"
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-xl font-bold text-gray-900">{{ $heading ?? __('Get the best price') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('Share your details and a dealer near you will get in touch.') }}</p>

            <form method="POST" action="{{ localized_route('leads.store') }}" class="mt-6 space-y-5">
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

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Your name') }}</label>
                        <input type="text" name="name" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm placeholder:text-gray-400 focus:border-brand-500 focus:ring-brand-500">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Mobile number') }}</label>
                        <input type="tel" name="phone" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm placeholder:text-gray-400 focus:border-brand-500 focus:ring-brand-500">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Select State') }}</label>
                        <select name="state_id" id="{{ $modalId }}-state"
                                onchange="window.filterDistricts_{{ str_replace('-', '_', $modalId) }}(this.value)"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500">
                            <option value="">{{ __('Select State') }}</option>
                            @foreach($statesWithDistricts as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Select District') }}</label>
                        <select name="district_id" id="{{ $modalId }}-district"
                                onchange="window.filterTehsils_{{ str_replace('-', '_', $modalId) }}(this.value)"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500">
                            <option value="">{{ __('Select District') }}</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Select Tehsil') }}</label>
                    <select name="tehsil_id" id="{{ $modalId }}-tehsil"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">{{ __('Select Tehsil') }}</option>
                    </select>
                </div>

                <label class="flex items-start gap-2.5 text-xs leading-relaxed text-gray-500">
                    <input type="checkbox" required class="mt-0.5 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span>{{ __('By submitting, you agree to be contacted about this enquiry.') }}</span>
                </label>

                <button type="submit"
                        class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-semibold text-white hover:bg-brand-700">
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

            const tehsilsByDistrict = {!! $statesWithDistricts->flatMap(fn ($s) => $s->districts)->mapWithKeys(fn ($d) => [
                $d->id => $d->tehsils->map(fn ($t) => ['id' => $t->id, 'name' => $t->name])->values(),
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
                window.filterTehsils_{{ str_replace('-', '_', $modalId) }}('');
            };

            window.filterTehsils_{{ str_replace('-', '_', $modalId) }} = function (districtId) {
                const select = document.getElementById('{{ $modalId }}-tehsil');
                select.innerHTML = '<option value="">{{ __('Select Tehsil') }}</option>';
                (tehsilsByDistrict[districtId] || []).forEach(function (t) {
                    const opt = document.createElement('option');
                    opt.value = t.id;
                    opt.textContent = t.name;
                    select.appendChild(opt);
                });
            };
        })();
    </script>
@endunless
