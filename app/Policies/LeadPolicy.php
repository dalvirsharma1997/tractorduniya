<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('leads.manage') || $user->can('leads.view_own');
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->can('leads.manage')) {
            return true;
        }

        return $user->can('leads.view_own') && $user->dealer_id === $lead->assigned_dealer_id;
    }

    public function create(User $user): bool
    {
        return $user->can('leads.manage');
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->can('leads.manage')) {
            return true;
        }

        return $user->can('leads.view_own') && $user->dealer_id === $lead->assigned_dealer_id;
    }

    public function delete(User $user): bool
    {
        return $user->can('leads.manage');
    }
}
