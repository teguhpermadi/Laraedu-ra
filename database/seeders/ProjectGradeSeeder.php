<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Project;
use App\Models\ProjectGrade;
use App\Models\TeacherGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = TeacherGrade::where('curriculum', 'merdeka')->get()->random();

        $data = [
            'project_id' => fake()->randomElement(Project::pluck('id')),
            'academic_year_id' => AcademicYear::active()->first()->id,
            'grade_id' => $grades->grade_id,
            'teacher_id' => $grades->teacher_id,
        ];

        ProjectGrade::create($data);
    }
}
