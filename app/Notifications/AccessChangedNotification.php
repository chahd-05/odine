<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Link;

class AccessChangedNotification extends Notification
{
    use Queueable;

    protected $link;
    protected $accessLevel;

    /**
     * Create a new notification instance.
     */
    public function __construct(Link $link, string $accessLevel)
    {
        $this->link = $link;
        $this->accessLevel = $accessLevel;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Changement de droits d\'accès')
            ->line('Vos droits sur le lien "' . $this->link->title . '" ont été modifiés.')
            ->line('Nouveau niveau d\'accès : ' . $this->accessLevel)
            ->action('Voir le lien', $this->link->url);
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
            'access_level' => $this->accessLevel,
            'message' => 'Vos droits sur le lien "' . $this->link->title . '" sont maintenant : ' . $this->accessLevel,
            'type' => 'access_changed'
        ];
    }
}
