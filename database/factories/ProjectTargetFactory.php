<?php

namespace Database\Factories;

use App\Models\ProjectGrade;
use App\Models\Target;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectTarget>
 */
class ProjectTargetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_grade_id' => fake()->randomElement(ProjectGrade::pluck('id')),
            'target_id' => fake()->randomElement(Target::pluck('id')),
        ];
    }
}
