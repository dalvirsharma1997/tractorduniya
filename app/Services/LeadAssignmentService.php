<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadAssignmentRule;

class LeadAssignmentService
{
    /**
     * Resolve a dealer for the given lead using district -> state -> brand fallback,
     * ordered by rule priority within each tier.
     */
    public function resolveDealerId(Lead $lead, ?int $brandId = null): ?int
    {
        if ($lead->district_id) {
            $rule = LeadAssignmentRule::where('district_id', $lead->district_id)
                ->when($brandId, fn ($q) => $q->where(function ($q2) use ($brandId) {
                    $q2->where('brand_id', $brandId)->orWhereNull('brand_id');
                }))
                ->orderByDesc('priority')
                ->first();

            if ($rule) {
                return $rule->dealer_id;
            }
        }

        if ($lead->state_id) {
            $rule = LeadAssignmentRule::whereNull('district_id')
                ->where('state_id', $lead->state_id)
                ->when($brandId, fn ($q) => $q->where(function ($q2) use ($brandId) {
                    $q2->where('brand_id', $brandId)->orWhereNull('brand_id');
                }))
                ->orderByDesc('priority')
                ->first();

            if ($rule) {
                return $rule->dealer_id;
            }
        }

        if ($brandId) {
            $rule = LeadAssignmentRule::whereNull('district_id')
                ->whereNull('state_id')
                ->where('brand_id', $brandId)
                ->orderByDesc('priority')
                ->first();

            if ($rule) {
                return $rule->dealer_id;
            }
        }

        return null;
    }

    /**
     * Returns true if a lead with the same phone + tractor already exists within $days.
     */
    public function isDuplicate(string $phone, ?int $tractorId, int $days = 7): bool
    {
        return Lead::where('phone', $phone)
            ->when($tractorId, fn ($q) => $q->where('interested_tractor_id', $tractorId))
            ->where('created_at', '>=', now()->subDays($days))
            ->exists();
    }
}
