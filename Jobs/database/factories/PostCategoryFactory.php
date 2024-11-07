<?php
// database/factories/PostCategoryFactory.php

namespace Database\Factories;

use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCategoryFactory extends Factory
{
    protected $model = PostCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            // Add other fields here
        ];
    }
}
