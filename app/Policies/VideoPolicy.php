<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class VideoPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'content.manage';
}
