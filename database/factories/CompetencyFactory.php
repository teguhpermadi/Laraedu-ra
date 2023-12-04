<?php

namespace Database\Factories;

use App\Models\Competency;
use App\Models\TeacherSubject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
        $teacher_subject = TeacherSubject::where('teacher_id', 1)->get()->pluck('id');
        $random_id = fake()->randomElement(TeacherSubject::pluck('id'));
        $random = TeacherSubject::find($random_id);
        $sequenceNumber = fake()->unique()->numberBetween(1, 999);
        // $code = $random->id . $random->academic_year_id . $random->grade_id . $random->teacher_id . $random->subject_id . $sequenceNumber;
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        return [
            // 'teacher_subject_id' => fake()->randomElement($teacher_subject),
            'passing_grade' => fake()->randomElement([70,75,80]),
            'code' => $code,
            'description' => fake()->sentence(),
        ];
    }

    public function teacherSubject($id)
    {
        $teacher_subject = TeacherSubject::with('subject', 'grade.teacherGrade')->find($id);

        if($teacher_subject->grade->teacherGrade->curriculum == '2013'){
            $data = [
                'teacher_subject_id' => $id,
                'code_skill' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'description_skill' => fake()->sentence(),
            ];
        } else {
            $data = [
                'teacher_subject_id' => $id,
            ];
        }
        
        return $this->state(function (array $attributes) use($data) {
            return $data;
        });

        // return $data;
    }


}
