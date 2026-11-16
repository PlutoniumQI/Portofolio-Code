<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'tags' => fake()->randomElements(['Laravel', 'Next.js', 'Tailwind CSS', 'Figma'], 2),
            'url' => fake()->url(),
            'image_path' => null,
            'image_url' => null,
            'status' => Project::STATUS_PUBLISHED,
            'sort_order' => 0,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => Project::STATUS_DRAFT,
        ]);
    }
}
