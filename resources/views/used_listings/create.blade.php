@extends('layouts.app')

@section('title', 'Sell Your Tractor or Implement')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">Sell Your Tractor or Implement</h1>
    <p class="mt-2 text-gray-600">Fill in the details below. Your listing will be reviewed by our team before it goes live.</p>

    @if ($errors->any())
        <div class="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('used-listings.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
        @csrf
        <input type="text" name="website" tabindex="-1" autocomplete="off" class="absolute left-[-9999px]" aria-hidden="true">

        <div>
            <label class="block text-sm font-medium text-gray-700">What are you selling?</label>
            <select name="listing_type" id="listing_type" required class="mt-1 w-full rounded-md border border-gray-300" onchange="toggleFields()">
                <option value="tractor">Tractor</option>
                <option value="implement">Implement</option>
            </select>
        </div>

        <div id="tractor-field">
            <label class="block text-sm font-medium text-gray-700">Tractor model</label>
            <select name="tractor_id" class="mt-1 w-full rounded-md border border-gray-300">
                <option value="">Select model</option>
                @foreach($tractors as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="implement-field" class="hidden">
            <label class="block text-sm font-medium text-gray-700">Implement</label>
            <select name="implement_id" class="mt-1 w-full rounded-md border border-gray-300">
                <option value="">Select implement</option>
                @foreach($implements as $i)
                    <option value="{{ $i->id }}">{{ $i->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Your name</label>
                <input type="text" name="seller_name" required class="mt-1 w-full rounded-md border border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" name="seller_phone" required class="mt-1 w-full rounded-md border border-gray-300">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">City</label>
                <input type="text" name="city" required class="mt-1 w-full rounded-md border border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">State</label>
                <select name="state_id" required class="mt-1 w-full rounded-md border border-gray-300">
                    <option value="">Select state</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Year of purchase</label>
                <input type="number" name="year_of_purchase" min="1980" max="{{ date('Y') }}" class="mt-1 w-full rounded-md border border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Hours used</label>
                <input type="number" name="hours_used" min="0" class="mt-1 w-full rounded-md border border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Condition</label>
                <select name="condition" required class="mt-1 w-full rounded-md border border-gray-300">
                    <option value="excellent">Excellent</option>
                    <option value="good" selected>Good</option>
                    <option value="fair">Fair</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Asking price (₹)</label>
            <input type="number" name="asking_price" required min="0" class="mt-1 w-full rounded-md border border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="mt-1 w-full rounded-md border border-gray-300"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Photos (up to 6)</label>
            <input type="file" name="photos[]" multiple accept="image/*" class="mt-1 w-full text-sm">
        </div>

        <button type="submit" class="w-full rounded-md bg-brand-600 px-4 py-3 text-sm font-semibold text-white hover:bg-brand-700">
            Submit Listing
        </button>
    </form>
</div>

<script>
    function toggleFields() {
        const type = document.getElementById('listing_type').value;
        document.getElementById('tractor-field').classList.toggle('hidden', type !== 'tractor');
        document.getElementById('implement-field').classList.toggle('hidden', type !== 'implement');
    }
</script>
@endsection
