<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            "title" => fake()->sentence(5, true),
            "content" => fake()->paragraph(30, true),
            "address" => fake()->address(),
            "user_id" => User::inRandomOrder()->first()->id,
            "expire_at" => fake()->date()
        ];
    }
}
