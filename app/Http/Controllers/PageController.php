<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\State;
use App\Models\Tractor;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function faqs(): View
    {
        $faqs = Faq::where('faqable_type', 'page')->orderBy('sort_order')->get();

        return view('pages.faqs', compact('faqs'));
    }

    public function emiCalculator(): View
    {
        $defaultRate = \App\Models\Setting::get('emi_default_interest_rate', '10.5');

        return view('pages.emi-calculator', compact('defaultRate'));
    }

    public function stateLanding(State $state): View
    {
        $tractors = Tractor::where('status', 'active')
            ->whereHas('prices', fn ($q) => $q->where('state_id', $state->id)->orWhereNull('state_id'))
            ->with(['brand', 'images'])
            ->limit(12)
            ->get();

        return view('pages.state-landing', compact('state', 'tractors'));
    }
}
