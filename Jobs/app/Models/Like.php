<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Like extends Model
{
    protected $fillable = ['post_id', 'user_id'];
    // العلاقة مع نموذج Post
    public function post()
    {
        return $this->belongsTo(posts::class);
    }
    // العلاقة مع نموذج users
    public function user()
    {
        return $this->belongsTo(users::class);
    }
}
