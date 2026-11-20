<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class LeadAssignmentRulePolicy
{
    use ChecksPermission;

    public const PERMISSION = 'leads.manage';
}
