<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class RedirectPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'seo.manage';
}
