<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Project;
use App\Models\ProjectCoordinator;
use App\Models\ProjectStudent;
use App\Models\ProjectTarget;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\TeacherGrade;
use Illuminate\Http\Request;

class ReportProject extends Controller
{
    public function calculateReport($id)
    {
        $data = [];

        $academic = AcademicYear::with('teacher')->active()->first();
        $student = Student::find($id);        
        $grade = StudentGrade::with('grade')->where('student_id', $id)->first();
        $teacherGrade = TeacherGrade::with('teacher')->where('grade_id', $grade->grade_id)->first();
        
        // cari projectnya berdasarkan nilai capaian
        $projectCoordinator = ProjectCoordinator::where('grade_id', $grade->grade_id)->where('teacher_id', $teacherGrade->teacher_id)->get();

        $projects = Project::with([
            'projectTarget.dimention', 
            'projectTarget.element', 
            'projectTarget.subElement', 
            'projectTarget.value', 
            'projectTarget.subValue',
            'projectTarget.projectStudent' => function($q) use ($student){
                $q->where('student_id', $student->id);
            },
        ])->where('grade_id', $grade->grade_id)->where('teacher_id', $teacherGrade->teacher_id)->get();

        $scores = ProjectStudent::where('student_id', $student->id)->get();

        $data = [
                'student' => $student,
                'academic' => $academic,
                'grade' => $grade,
                'project' => $projects,
        ];

        return $scores;
    }
}
