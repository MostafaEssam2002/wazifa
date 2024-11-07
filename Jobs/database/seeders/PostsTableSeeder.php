<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Posts;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        // قم بإنشاء مثيل من Faker
        $faker = Faker::create();

        // مسار الصورة التي تم تحميلها
        $imagePath = 'images/posts/pexels-goumbik-590016.jpg';

        // قم بإضافة 20 مشاركة
        for ($i = 0; $i < 20; $i++) {
            Posts::create([
                'title' => $faker->sentence(6, true),
                'content' => $faker->paragraph(3, true),
                'user_id' => 1, // افترض أن هناك مستخدم لديه ID = 1
                'category_id' => PostCategory::inRandomOrder()->first()->id, // اختر فئة عشوائية
                'image' => $imagePath, // استخدم مسار الصورة الثابت
                'status' => 'approved', // ضع الحالة كـ approved
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
