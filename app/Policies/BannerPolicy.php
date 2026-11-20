<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class BannerPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'content.manage';
}
