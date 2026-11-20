<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class RolePolicy
{
    use ChecksPermission;

    public const PERMISSION = 'users.manage';
}
