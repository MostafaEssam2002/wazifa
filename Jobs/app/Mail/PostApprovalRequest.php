<?php 
namespace App\Mail;

use App\Models\Posts;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostApprovalRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    public function __construct(Posts $post)
    {
        $this->post = $post;
    }

    public function build()
    {
        return $this->subject('New Post Approval Request')
                    ->view('emails.post_approval')
                    ->with(['post' => $this->post]);
    }
}
