<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pen>
 */
class PenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = ucfirst($this->faker->words(3, true));
        $status = $this->faker->randomElement(['draft', 'published']);

        return [
            'user_id' => User::factory(),
            'team_id' => null,
            'parent_pen_id' => null,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'description' => $this->faker->sentence(),
            'html' => '<div class="demo-pen">' . $this->faker->words(2, true) . '</div>',
            'css' => ".demo-pen { color: {$this->faker->safeColorName()}; }",
            'js' => 'console.log("Hello from demo pen");',
            'settings' => [
                'resources' => [
                    'css' => ['https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css'],
                    'js' => ['https://unpkg.com/vue@3/dist/vue.global.js'],
                ],
            ],
            'visibility' => $this->faker->randomElement(['public', 'unlisted', 'private']),
            'status' => $status,
            'is_featured' => $this->faker->boolean(10),
            'preview_image_path' => null,
            'published_at' => $status === 'published' ? now()->subDays(rand(0, 30)) : null,
        ];
    }
}
