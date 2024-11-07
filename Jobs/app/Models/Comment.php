<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'content','file_path',];
    // protected $fillable = ['user_id', 'content', 'file'];

    // العلاقة مع نموذج Post
    public function post()
    {
        return $this->belongsTo(posts::class);
    }
    public function user()
    {
        return $this->belongsTo(users::class);
    }
}
