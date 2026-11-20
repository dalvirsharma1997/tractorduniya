<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class TranslationPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'content.manage';
}
