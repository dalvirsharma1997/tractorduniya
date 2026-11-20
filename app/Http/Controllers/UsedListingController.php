<?php

namespace App\Http\Controllers;

use App\Models\Implement;
use App\Models\State;
use App\Models\Tractor;
use App\Models\UsedListing;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UsedListingController extends Controller
{
    public function index(Request $request): View
    {
        $query = UsedListing::where('status', 'approved')->with(['tractor.brand', 'implement.brand', 'images', 'state']);

        if ($type = $request->get('type')) {
            $query->where('listing_type', $type);
        }

        if ($stateId = $request->get('state')) {
            $query->where('state_id', $stateId);
        }

        $listings = $query->latest()->paginate(12)->withQueryString();
        $states = State::orderBy('name')->get();

        return view('used_listings.index', compact('listings', 'states'));
    }

    public function create(): View
    {
        $tractors = Tractor::orderBy('name')->get();
        $implements = Implement::orderBy('name')->get();
        $states = State::orderBy('name')->get();

        return view('used_listings.create', compact('tractors', 'implements', 'states'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'listing_type' => ['required', 'in:tractor,implement'],
            'tractor_id' => ['required_if:listing_type,tractor', 'nullable', 'exists:tractors,id'],
            'implement_id' => ['required_if:listing_type,implement', 'nullable', 'exists:implements,id'],
            'seller_name' => ['required', 'string', 'max:100'],
            'seller_phone' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'state_id' => ['required', 'exists:states,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'year_of_purchase' => ['nullable', 'integer', 'min:1980', 'max:' . date('Y')],
            'hours_used' => ['nullable', 'integer', 'min:0'],
            'condition' => ['required', 'in:excellent,good,fair'],
            'asking_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:6'],
            'photos.*' => ['image', 'max:4096'],
            'website' => ['max:0'],
        ]);

        unset($data['website'], $data['photos']);
        $data['status'] = 'pending';

        $listing = UsedListing::create($data);

        foreach ($request->file('photos', []) as $i => $photo) {
            $path = $photo->store('used-listings', 'public');
            $listing->images()->create(['path' => $path, 'sort_order' => $i]);
        }

        return redirect()->route('used-listings.index')
            ->with('listing_submitted', 'Your listing has been submitted and will appear once our team approves it.');
    }
}
