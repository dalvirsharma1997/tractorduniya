<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Redirect;
use App\Models\State;
use App\Models\Tractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $page = Page::where('slug', 'about')->first();

        return view('pages.about', compact('page'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function privacy(): View
    {
        $page = Page::where('slug', 'privacy-policy')->first();

        return view('pages.privacy', compact('page'));
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

    public function handleUnmatched(\Illuminate\Http\Request $request): View|RedirectResponse
    {
        $path = '/'.ltrim($request->path(), '/');

        $redirect = Redirect::where('from_path', $path)->first();

        if ($redirect) {
            return redirect($redirect->to_path, (int) $redirect->type);
        }

        abort(404);
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
