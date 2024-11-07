<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Technology'],
            ['name' => 'Health'],
            ['name' => 'Education'],
            ['name' => 'Finance'],
            ['name' => 'Travel'],
        ];

        DB::table('post_categories')->insert($categories);
    }
}
