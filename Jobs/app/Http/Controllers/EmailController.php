<?php
// app/Http/Controllers/EmailController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class EmailController extends Controller
{
    public function showForm($email)
    {
        return view('send_email', ['receiverEmail' => $email]);
    }

    public function sendEmail(Request $request)
{
    $request->validate([
        'from_email' => 'required|email',
        'to_email' => 'required|email',
        'subject' => 'required|string',
        'message' => 'required|string',
    ]);

    // تحديث تكوينات البريد ديناميكيًا
    config([
        'mail.mailers.smtp.username' => $request->from_email,
        'mail.mailers.smtp.password' => 'YOUR_SMTP_PASSWORD', // يجب أن تستخدم كلمة المرور الخاصة بحساب SMTP
        'mail.from.address' => $request->from_email,
    ]);

    // إعداد بيانات البريد الإلكتروني
    $details = [
        'to' => $request->to_email,
        'from' => $request->from_email,
        'subject' => $request->subject,
        'message' => $request->message,
    ];

    // إرسال البريد الإلكتروني
    Mail::raw($details['message'], function ($message) use ($details) {
        $message->from($details['from'])
                ->to($details['to'])
                ->subject($details['subject']);
    });

    return back()->with('success', 'تم إرسال البريد الإلكتروني بنجاح!');
}
}
