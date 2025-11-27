<?php

namespace Database\Factories;

use App\Models\Pen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mime = $this->faker->randomElement(['image/png', 'image/jpeg', 'text/css', 'application/javascript']);
        $extension = match ($mime) {
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'text/css' => 'css',
            default => 'js',
        };

        return [
            'user_id' => User::factory(),
            'team_id' => null,
            'pen_id' => Pen::factory(),
            'name' => $this->faker->words(2, true) . '.' . $extension,
            'path' => 'assets/' . $this->faker->uuid() . '.' . $extension,
            'type' => $mime,
            'size_in_bytes' => $this->faker->numberBetween(1_024, 2_000_000),
            'metadata' => ['mime' => $mime],
        ];
    }
}
