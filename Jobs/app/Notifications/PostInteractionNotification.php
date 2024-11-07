<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PostInteractionNotification extends Notification
{
    use Queueable;

    public $post;
    public $userName;
    public $interactionType;

    public function __construct($post, $userName, $interactionType)
    {
        $this->post = $post;
        $this->userName = $userName;
        $this->interactionType = $interactionType;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'message' => "{$this->userName} {$this->interactionType} on your post",
            'interaction_type' => $this->interactionType,
        ];
    }
}
