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
        // $teacher_subject = TeacherSubject::pluck('id');
        $teacher_subject = TeacherSubject::where('teacher_id', 1)->get()->pluck('id');
        $random_id = fake()->randomElement(TeacherSubject::pluck('id'));
        $random = TeacherSubject::find($random_id);
        $sequenceNumber = fake()->unique()->numberBetween(1, 999);
        $code = $random->id . $random->academic_year_id . $random->grade_id . $random->teacher_id . $random->subject_id . $sequenceNumber;
        return [
            'teacher_subject_id' => fake()->randomElement($teacher_subject),
            'passing_grade' => fake()->randomElement([70,75,80]),
            'code' => $code,
            'description' => fake()->sentence(),
        ];
    }
}
