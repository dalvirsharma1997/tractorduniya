<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class UsedListingPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'used_listings.manage';
}
