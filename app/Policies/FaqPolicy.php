<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksPermission;

class FaqPolicy
{
    use ChecksPermission;

    public const PERMISSION = 'content.manage';
}
