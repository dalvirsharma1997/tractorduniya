<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\District;
use App\Models\Lead;
use App\Services\LeadAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LeadController extends Controller
{
    public function store(Request $request, LeadAssignmentService $assignment): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'city' => ['nullable', 'string', 'max:100'],
            'state_id' => ['nullable', 'exists:states,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'interested_tractor_id' => ['nullable', 'exists:tractors,id'],
            'interested_implement_id' => ['nullable', 'exists:implements,id'],
            'source' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            // Honeypot: a real visitor never fills this hidden field in.
            'website' => ['max:0'],
        ]);

        unset($data['website']);

        if ($assignment->isDuplicate($data['phone'], $data['interested_tractor_id'] ?? null)) {
            return back()->with('lead_status', 'duplicate')
                ->with('lead_message', "Thanks {$data['name']}, we already have your enquiry and a dealer will contact you shortly.");
        }

        $lead = Lead::create($data);

        $brandId = null;
        if ($lead->interested_tractor_id) {
            $brandId = $lead->tractor?->brand_id;
        } elseif ($lead->interested_implement_id) {
            $brandId = $lead->implement?->brand_id;
        }

        $dealerId = $assignment->resolveDealerId($lead, $brandId);
        if ($dealerId) {
            $lead->update(['assigned_dealer_id' => $dealerId]);
        }

        ActivityLog::record(null, 'lead.created', 'lead', $lead->id, ['dealer_id' => $dealerId]);

        return back()->with('lead_status', 'created')
            ->with('lead_message', "Thanks {$lead->name}! Our team will reach out to you shortly on {$lead->phone}.");
    }
}
