<?php

namespace App\Events;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class LinkShared
{
    use Dispatchable;

    public Link $link;
    public User $sharer;
    public User $recipient;
    public string $accessLevel;

    public function __construct(Link $link, User $sharer, User $recipient, string $accessLevel)
    {
        $this->link = $link;
        $this->sharer = $sharer;
        $this->recipient = $recipient;
        $this->accessLevel = $accessLevel;
    }
}
