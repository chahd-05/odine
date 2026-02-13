<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Link;
use App\Models\User;

class LinkSharedNotification extends Notification
{
    use Queueable;

    protected $link;
    protected $sharer;

    /**
     * Create a new notification instance.
     */
    public function __construct(Link $link, User $sharer)
    {
        $this->link = $link;
        $this->sharer = $sharer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Using database for in-app notifications as per brief implied context? Or mail? Brief says "Utiliser le système de Notifications Laravel". Database is good for "En tant qu'utilisateur, je reçois..." inside the app.
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau lien partagé')
            ->line($this->sharer->name . ' a partagé un lien avec vous : ' . $this->link->title)
            ->action('Voir le lien', $this->link->url)
            ->line('Merci d\'utiliser Odin !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'link_id' => $this->link->id,
            'title' => $this->link->title,
            'sharer_id' => $this->sharer->id,
            'sharer_name' => $this->sharer->name,
            'message' => $this->sharer->name . ' a partagé le lien "' . $this->link->title . '" avec vous.',
            'type' => 'link_shared'
        ];
    }
}
