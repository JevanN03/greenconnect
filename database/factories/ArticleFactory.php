<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'   => $this->faker->sentence(6),
            'excerpt' => $this->faker->sentence(12),
            'content' => $this->faker->paragraph(6),
            'user_id' => null,
        ];
    }
}
