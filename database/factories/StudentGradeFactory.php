<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentGrade>
 */
class StudentGradeFactory extends Factory
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
        $student = Student::pluck('id');

        return [
            'academic_year_id' => fake()->randomElement($academic),
            'grade_id' => fake()->randomElement($grade),
            'student_id' => fake()->randomElement($student),
        ];
    }
}
