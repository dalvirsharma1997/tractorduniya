<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class BrandPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'tractors.manage';
}
