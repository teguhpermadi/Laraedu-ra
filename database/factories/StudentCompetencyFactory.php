<?php

namespace Database\Factories;

use App\Models\Competency;
use App\Models\Student;
use App\Models\TeacherSubject;
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
        $teacher_subject = fake()->randomElement(TeacherSubject::pluck('id'));

        $random_teacher_subject = TeacherSubject::with('competencies')->find($teacher_subject);
        $competencies = $random_teacher_subject->competencies->pluck('id');
        // $competency_id = Competency::pluck('id');
        return [
            'teacher_subject_id' => $teacher_subject,
            'competency_id' => fake()->randomElement($competencies),
            'student_id' => fake()->randomElement($students),
            'score' => fake()->numberBetween(0,100),
        ];
    }
}
