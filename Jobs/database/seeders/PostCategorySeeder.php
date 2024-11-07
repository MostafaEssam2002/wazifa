<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostCategory;

class PostCategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // إدخال بيانات محددة إلى جدول post_categories
        $categories = [
            ['name' => 'Technology'],
            ['name' => 'Health'],
            ['name' => 'Finance'],
            ['name' => 'Education'],
            ['name' => 'Entertainment'],
            ['name' => 'Web Development'],
            ['name' => 'Network'],
            ['name' => 'Cyber security'],
            ['name' => 'Mobile'],
        ];

        foreach ($categories as $category) {
            PostCategory::create($category);
        }
    }
}
