<?php

namespace App\Events;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class LinkDeleted
{
    use Dispatchable;

    public Link $link;
    public User $user;

    public function __construct(Link $link, User $user)
    {
        $this->link = $link;
        $this->user = $user;
    }
}
