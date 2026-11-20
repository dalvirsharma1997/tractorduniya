<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait ChecksPermission
{
    public function viewAny(User $user): bool
    {
        return $user->can(static::PERMISSION);
    }

    public function view(User $user): bool
    {
        return $user->can(static::PERMISSION);
    }

    public function create(User $user): bool
    {
        return $user->can(static::PERMISSION);
    }

    public function update(User $user): bool
    {
        return $user->can(static::PERMISSION);
    }

    public function delete(User $user): bool
    {
        return $user->can(static::PERMISSION);
    }
}
