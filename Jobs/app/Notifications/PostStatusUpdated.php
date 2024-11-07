<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Facades\Log;

class PostStatusUpdated extends Notification
{
    protected $post;
    protected $status;

    public function __construct($post, $status)
    {
        $this->post = $post;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // سجل رسالة للتصحيح
        Log::info('Creating notification for post ID: ' . $this->post->id);

        return [
            'post_id' => $this->post->id,
            'message' => "Your post titled '{$this->post->title}' has been {$this->status}.",
        ];
    }
}
