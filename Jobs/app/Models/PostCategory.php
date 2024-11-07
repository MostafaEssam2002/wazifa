<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = ['name'];

    // public function posts()
    // {
    //     return $this->belongsToMany(Posts::class, 'post_category_post', 'post_category_id', 'post_id'); // Update table name to 'post_category_post'
    // }
    public function posts()
    {
        return $this->hasMany(Posts::class, 'category_id');
    }
}
