<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class ImplementPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'implements.manage';
}
