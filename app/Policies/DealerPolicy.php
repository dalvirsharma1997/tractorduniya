<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class DealerPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'dealers.manage';
}
