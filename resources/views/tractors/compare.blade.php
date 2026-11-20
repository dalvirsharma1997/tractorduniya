@extends('layouts.app')

@section('title', $tractors->pluck('name')->implode(' vs ') . ' — Compare Specs & Price')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">Compare: {{ $tractors->pluck('name')->implode(' vs ') }}</h1>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full min-w-max border-collapse text-sm">
            <thead>
                <tr>
                    <th class="w-40 text-left"></th>
                    @foreach($tractors as $tractor)
                        <th class="p-4 text-left">
                            <a href="{{ route('tractors.show', [$tractor->brand, $tractor]) }}" class="font-semibold text-brand-700 hover:underline">
                                {{ $tractor->name }}
                            </a>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach([
                    'Brand' => fn($t) => $t->brand->name,
                    'HP' => fn($t) => $t->hp,
                    'Drive Type' => fn($t) => $t->drive_type,
                    'Engine CC' => fn($t) => $t->engine_cc ?? '—',
                    'Cylinders' => fn($t) => $t->cylinders ?? '—',
                    'PTO HP' => fn($t) => $t->pto_hp ?? '—',
                    'Lifting Capacity (kg)' => fn($t) => $t->lifting_capacity_kg ?? '—',
                    'Fuel Tank (L)' => fn($t) => $t->fuel_tank_litres ?? '—',
                    'Transmission' => fn($t) => $t->transmission_type ?? '—',
                    'Warranty' => fn($t) => $t->warranty_text ?? '—',
                    'Price' => fn($t) => optional($t->priceFor(null), fn($p) => '₹' . number_format($p->price_min) . ' – ₹' . number_format($p->price_max)) ?? 'On request',
                ] as $label => $callback)
                    <tr>
                        <td class="p-4 font-medium text-gray-500">{{ $label }}</td>
                        @foreach($tractors as $tractor)
                            <td class="p-4">{{ $callback($tractor) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
