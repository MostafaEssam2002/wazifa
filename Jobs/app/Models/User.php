<?php
namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'image'
    ];
    
    protected $casts = [
        'status' => 'string',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // في نموذج User

// app/Models/User.php

public function likes()
{
    return $this->belongsToMany(posts::class, 'likes')->withTimestamps();
}

public function hasLiked(posts $post)
{
    return $this->likes()->where('post_id', $post->id)->exists();
}
public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }
}
