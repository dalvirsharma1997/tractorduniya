<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class BlogPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'content.manage';
}
