<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class ReviewPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'content.manage';
}
