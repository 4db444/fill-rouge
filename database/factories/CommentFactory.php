<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        $user_id = User::inRandomOrder()->first()->id;

        return [
            "content" => fake()->paragraph(10, true),
            "user_id" => $user_id,
            "post_id" => Post::where("user_id", "!=", $user_id)->inRandomOrder()->first()->id
        ];
    }
}
