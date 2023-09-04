<?php

namespace Database\Factories;

use App\Models\TeacherSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competency>
 */
class CompetencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teacher_subject = TeacherSubject::pluck('id');

        return [
            'teacher_subject_id' => fake()->randomElement($teacher_subject),
            'description' => fake()->realTextBetween(50,100),
        ];
    }
}
