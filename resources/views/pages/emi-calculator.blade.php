@extends('layouts.app')

@section('title', 'Tractor EMI Calculator')

@section('content')
<div class="mx-auto max-w-xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900">EMI Calculator</h1>
    <p class="mt-2 text-sm text-gray-600">Estimate your monthly instalment for a tractor loan. This is an estimate only — actual EMI depends on your lender's terms.</p>

    <form id="emi-form" class="mt-8 space-y-4" onsubmit="return false;">
        <div>
            <label class="block text-sm font-medium text-gray-700">Loan amount (₹)</label>
            <input type="number" id="principal" value="500000" min="0"
                   class="mt-1 w-full rounded-md border border-gray-300 focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Interest rate (% per annum)</label>
            <input type="number" id="rate" value="{{ $defaultRate }}" step="0.1" min="0"
                   class="mt-1 w-full rounded-md border border-gray-300 focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tenure (months)</label>
            <input type="number" id="tenure" value="36" min="1"
                   class="mt-1 w-full rounded-md border border-gray-300 focus:border-brand-500 focus:ring-brand-500">
        </div>
        <button type="button" onclick="calculateEmi()"
                class="w-full rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
            Calculate EMI
        </button>
    </form>

    <div id="emi-result" class="mt-6 hidden rounded-md bg-brand-50 p-4 text-brand-800"></div>
</div>

<script>
    function calculateEmi() {
        const p = parseFloat(document.getElementById('principal').value) || 0;
        const annualRate = parseFloat(document.getElementById('rate').value) || 0;
        const n = parseInt(document.getElementById('tenure').value) || 1;
        const r = annualRate / 12 / 100;

        let emi;
        if (r === 0) {
            emi = p / n;
        } else {
            emi = (p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        }

        const result = document.getElementById('emi-result');
        result.classList.remove('hidden');
        result.innerHTML = `Estimated monthly EMI: <strong>₹${emi.toLocaleString('en-IN', { maximumFractionDigits: 0 })}</strong>` +
            `<br>Total payable: ₹${(emi * n).toLocaleString('en-IN', { maximumFractionDigits: 0 })}`;
    }
</script>
@endsection
