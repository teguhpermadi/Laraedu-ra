<?php

namespace Database\Seeders;

use App\Models\Competency;
use App\Models\TeacherSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompetencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Competency::factory(20)->create();
        $teacher_subjects = TeacherSubject::with('subject', 'grade')->get();

        foreach ($teacher_subjects as $teacher_subject) {
            Competency::factory(1)->teacherSubject($teacher_subject->id)->create();
        }
    }
}
