<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeacherSubject>
 */
class TeacherSubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $academic = AcademicYear::pluck('id');
        $grade = Grade::pluck('id');
        $teacher = Teacher::pluck('id');
        $subject = Subject::pluck('id');

        return [
            'academic_year_id' => fake()->randomElement($academic),
            'grade_id' => fake()->randomElement($grade),
            'teacher_id' => fake()->randomElement($teacher),
            'subject_id' => fake()->randomElement($teacher),
            'passing_grade' => fake()->randomElement([70,75,80]),
        ];
    }
}
