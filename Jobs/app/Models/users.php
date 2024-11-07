<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class users extends Model
{
    // في ملف User.php
// public function posts()
// {
//     return $this->hasMany(posts::class);
// }
public function posts()
{
    return $this->hasMany(posts::class, 'user_id'); // تأكد من اسم العمود هنا
}
// في ملف Posts.php داخل مجلد Models
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function likes()
{
    return $this->belongsToMany(posts::class, 'likes')->withTimestamps();
}
public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }
public function hasLiked(posts $post)
{
    return $this->likes()->where('post_id', $post->id)->exists();
}
    protected $fillable = ['name','email','password','status','image'];
    // use HasFactory;
    use HasFactory, Notifiable;

}
