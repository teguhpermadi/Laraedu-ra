<?php

namespace Database\Factories;

use App\Models\Competency;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentCompetency>
 */
class StudentCompetencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $students = Student::active()->pluck('id');
        $competencies = Competency::pluck('id');

        return [
            'student_id' => fake()->randomElement($students),
            'competency_id' => fake()->randomElement($competencies),
            'score' => fake()->numberBetween(0,100),
        ];
    }
}
