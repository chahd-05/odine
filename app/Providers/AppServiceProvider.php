<?php

namespace App\Providers;

use App\Models\Link;
use App\Models\User;
use App\Models\Tag;
use App\Models\Category;
use App\Policies\LinkPolicy;
use App\Policies\UserPolicy;
use App\Policies\TagPolicy;
use App\Policies\CategoryPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use App\Events\LinkCreated;
use App\Events\LinkUpdated;
use App\Events\LinkDeleted;
use App\Events\LinkRestored;
use App\Events\LinkForceDeleted;
use App\Events\LinkShared;
use App\Listeners\LogActivity;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Enregistrer les Policies
        Gate::policy(Link::class, LinkPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);

        // Enregistrer les Events → Listeners (US-09)
        Event::listen(LinkCreated::class, [LogActivity::class, 'handleCreated']);
        Event::listen(LinkUpdated::class, [LogActivity::class, 'handleUpdated']);
        Event::listen(LinkDeleted::class, [LogActivity::class, 'handleDeleted']);
        Event::listen(LinkRestored::class, [LogActivity::class, 'handleRestored']);
        Event::listen(LinkForceDeleted::class, [LogActivity::class, 'handleForceDeleted']);
        Event::listen(LinkShared::class, [LogActivity::class, 'handleShared']);
    }
}
