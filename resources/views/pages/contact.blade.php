@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900">Contact Us</h1>
    <div class="mt-6 space-y-2 text-gray-700">
        @if($phone = \App\Models\Setting::get('contact_phone'))
            <p>Phone: {{ $phone }}</p>
        @endif
        @if($email = \App\Models\Setting::get('contact_email'))
            <p>Email: {{ $email }}</p>
        @endif
        @if(!$phone && !$email)
            <p>Contact details will be published here shortly.</p>
        @endif
    </div>

    <div class="mt-10 max-w-md">
        <x-lead-form heading="Send us a message" />
    </div>
</div>
@endsection
