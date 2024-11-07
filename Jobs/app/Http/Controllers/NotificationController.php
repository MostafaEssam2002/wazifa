<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\posts;
use App\Notifications\PostStatusUpdated;
use App\Notifications\PostInteractionNotification;
class NotificationController extends Controller
{
    public function destroy($id)
    {
        $notification = DatabaseNotification::find($id);
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true], 200);
        }
        return response()->json(['error' => 'Notification not found'], 404);
    }
    public function markAsRead($id)
    {
        $notification = Auth::user()->unreadNotifications->find($id);
        if ($notification) {
            $notification->markAsRead();
            $notification->is_read = true;
            $notification->save();
            return response()->json(['success' => true], 200);
        }
        return response()->json(['error' => 'Notification not found'], 404);
    }
    public function updatePostStatus($postId, $status)
    {
        // تحقق من صحة حالة التحديث
        if (!in_array($status, ['approved', 'rejected'])) {
            return response()->json(['error' => 'Invalid status'], 400);
        }
        $post = posts::find($postId);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        // تحديث حالة البوست
        $post->status = $status;
        $post->save();
        return response()->json(['error' => 'User not found'], 404);
        // الحصول على البوست
    }
    public function sendPostInteractionNotification($postId, $interactionType)
    {
        $post = posts::find($postId);
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        $user = auth::user();
        $postOwner = $post->user;
        // إرسال الإشعار إلى صاحب المنشور
        $postOwner->notify(new PostInteractionNotification($post, $user->name, $interactionType));
        return redirect()->back()->with('success', 'Notification sent successfully');

    }
}
// اعمل صفحه اشعارات 