<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        $currentUserId = auth()->id();
        $users = User::where('id', '!=', $currentUserId)->get();
        $toUserId = $request->query('to_user_id');
        $toUser = User::findOrFail($toUserId);
        return view('messages.create', compact('users', 'toUser'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        Message::create([
            'from_user_id' => auth::id(),
            'to_user_id' => $request->to_user_id,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);
        return redirect()->back()->with('success', 'تم إرسال الرسالة بنجاح!');
    }
    public function index()
    {
        $receivedMessages = Message::where('to_user_id', auth::id())->orderBy('updated_at', 'desc')->get();
        $sentMessages = Message::where('from_user_id', auth::id())->orderBy('updated_at', 'desc');
        return view('messages.index', compact('receivedMessages', 'sentMessages'));
    }
    public function show($messageId)
{
    $message = Message::find($messageId);
    if (!$message || ($message->to_user_id != auth()->id() && $message->from_user_id != auth()->id())) {
        abort(404);
    }
    if ($message->to_user_id == auth()->id() && !$message->is_read) {
        $message->is_read = 1;
        $message->save();
    }
    $messages = Message::where(function ($query) use ($message) {
        $query->where('from_user_id', $message->from_user_id)->where('to_user_id', $message->to_user_id);
    })->orWhere(function ($query) use ($message) {
        $query->where('from_user_id', $message->to_user_id)->where('to_user_id', $message->from_user_id);
    })->orderBy('created_at', 'asc') // ترتيب الرسائل بناءً على تاريخ الإنشاء
    ->get();
    $user = User::find($message->from_user_id == auth()->id() ? $message->to_user_id : $message->from_user_id);
    return view('messages.show', compact('messages', 'user', 'message'));
}
    public function reply(Request $request, $messageId)
    {
        $request->validate([
            'body' => 'required|string',
        ]);
    
        $originalMessage = Message::find($messageId);
    
        if (!$originalMessage || $originalMessage->to_user_id != auth::id()) {
            abort(404);
        }
    
        Message::create([
            'from_user_id' => auth::id(),
            'to_user_id' => $originalMessage->from_user_id,
            'subject' => 'Re: ' . $originalMessage->subject,
            'body' => $request->body,
        ]);
    
        return redirect()->route('messages.show', $messageId)->with('success', '');
    }
    
    public function markAsRead($messageId)
    {
        $message = Message::find($messageId);

        if ($message && $message->to_user_id == auth::id()) {
            $message->update(['is_read' => true]);
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'fail'], 400);
    }
    public function unreadCount()
    {
        $unreadCount = auth::user()->receivedMessages->where('is_read', 0)->count();
        return response()->json(['unreadCount' => $unreadCount]);
    }
    
    public function fetchMessages($userId)
    {
        $messages = Message::where('to_user_id', $userId)
            ->orWhere('from_user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

}
