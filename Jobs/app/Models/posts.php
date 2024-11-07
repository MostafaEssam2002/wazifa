<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class posts extends Model
{
    protected $fillable = ['title', 'content', 'image', 'user_id',"category_id"];
    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // public function categories()
    // {
    //     return $this->belongsToMany(PostCategory::class, 'post_category_post', 'post_id', 'post_category_id'); // Update table name to 'post_category_post'
    // }
    public function likedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
    public function unlike($id)
{
    $post = posts::findOrFail($id);
    $like = Like::where('user_id', auth()->user()->id)->where('post_id', $post->id)->first();
    if ($like) {
        $like->delete();
        return response()->json(['message' => 'Post unliked successfully!']);
    }
    return response()->json(['message' => 'You have not liked this post.'], 400);
}
    public function category()
        {
            return $this->belongsTo(PostCategory::class, 'category_id');
        }
}