<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class SeoMetaPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'seo.manage';
}
