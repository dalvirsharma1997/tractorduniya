<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class TractorPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'tractors.manage';
}
