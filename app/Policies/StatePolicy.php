<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class StatePolicy
{
    use ChecksPermission;

    public const PERMISSION = 'tractors.manage';
}
