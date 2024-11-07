<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    // باقي الدوال في Policy

    /**
     * Determine if the given post can be approved by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return bool
     */
    public function approve(User $user, Post $post)
    {
        // السماح فقط للمستخدم الذي يمتلك صلاحيات المدير بالموافقة على المشاركات
        return $user->is_admin; // يفترض أن لديك عمود في جدول المستخدمين لتحديد المستخدمين المدراء
    }
}

