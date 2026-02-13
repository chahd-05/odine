<?php

namespace App\Listeners;

use App\Models\Link;
use App\Models\ActivityLog;
use App\Events\LinkCreated;
use App\Events\LinkUpdated;
use App\Events\LinkDeleted;
use App\Events\LinkRestored;
use App\Events\LinkForceDeleted;
use App\Events\LinkShared;
use Illuminate\Events\Attribute\AsListener;

class LogActivity
{
    /**
     * Enregistre la création d'un lien.
     */
    public function handleCreated(LinkCreated $event): void
    {
        ActivityLog::create([
            'user_id'      => $event->user->id,
            'action'       => 'création',
            'description'  => $event->user->name . ' a créé le lien ' . $event->link->title,
            'subject_type' => Link::class,
            'subject_id'   => $event->link->id,
        ]);
    }

    /**
     * Enregistre la modification d'un lien.
     */
    public function handleUpdated(LinkUpdated $event): void
    {
        ActivityLog::create([
            'user_id'      => $event->user->id,
            'action'       => 'modification',
            'description'  => $event->user->name . ' a modifié le lien ' . $event->link->title,
            'subject_type' => Link::class,
            'subject_id'   => $event->link->id,
        ]);
    }

    /**
     * Enregistre la suppression (soft) d'un lien.
     */
    public function handleDeleted(LinkDeleted $event): void
    {
        ActivityLog::create([
            'user_id'      => $event->user->id,
            'action'       => 'suppression',
            'description'  => $event->user->name . ' a supprimé le lien ' . $event->link->title,
            'subject_type' => Link::class,
            'subject_id'   => $event->link->id,
        ]);
    }

    /**
     * Enregistre la restauration d'un lien.
     */
    public function handleRestored(LinkRestored $event): void
    {
        ActivityLog::create([
            'user_id'      => $event->user->id,
            'action'       => 'restauration',
            'description'  => $event->user->name . ' a restauré le lien ' . $event->link->title,
            'subject_type' => Link::class,
            'subject_id'   => $event->link->id,
        ]);
    }

    /**
     * Enregistre la suppression définitive d'un lien.
     */
    public function handleForceDeleted(LinkForceDeleted $event): void
    {
        ActivityLog::create([
            'user_id'      => $event->user->id,
            'action'       => 'suppression définitive',
            'description'  => $event->user->name . ' a supprimé définitivement le lien ' . $event->linkTitle,
            'subject_type' => Link::class,
            'subject_id'   => $event->linkId,
        ]);
    }

    /**
     * Enregistre le partage d'un lien.
     */
    public function handleShared(LinkShared $event): void
    {
        ActivityLog::create([
            'user_id'      => $event->sharer->id,
            'action'       => 'partage',
            'description'  => $event->sharer->name . ' a partagé le lien ' . $event->link->title . ' avec ' . $event->recipient->name,
            'subject_type' => Link::class,
            'subject_id'   => $event->link->id,
        ]);
    }
}
