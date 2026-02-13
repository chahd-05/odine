<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class LinkForceDeleted
{
    use Dispatchable;

    public string $linkTitle;
    public int $linkId;
    public User $user;

    public function __construct(string $linkTitle, int $linkId, User $user)
    {
        $this->linkTitle = $linkTitle;
        $this->linkId = $linkId;
        $this->user = $user;
    }
}
