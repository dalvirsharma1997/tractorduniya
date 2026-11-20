<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class CategoryPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'tractors.manage';
}
