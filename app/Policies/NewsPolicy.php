<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class NewsPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'content.manage';
}
