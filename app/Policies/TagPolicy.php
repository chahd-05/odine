<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Tag $tag): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }
}
