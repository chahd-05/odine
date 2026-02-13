<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LinkPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor() || $user->isViewer();
    }

    public function view(User $user, Link $link): bool
    {
        return $user->isAdmin()
            || $user->id === $link->user_id
            || $link->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function update(User $user, Link $link)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $link->user_id) {
            return true;
        }

        return $link->users()
            ->where('user_id', $user->id)
            ->where('access_level', 'edit')
            ->exists();
    }

    public function delete(User $user, Link $link): bool
    {
        return $this->update($user, $link);
    }

    public function restore(User $user, Link $link): bool
    {
        return $this->update($user, $link);
    }

    public function forceDelete(User $user, Link $link): bool
    {
        return $user->isAdmin();
    }
}
