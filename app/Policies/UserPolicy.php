<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class UserPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'users.manage';
}
