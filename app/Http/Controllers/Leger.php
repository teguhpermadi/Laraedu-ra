<?php

namespace App\Http\Controllers;

use App\Models\TeacherSubject;
use Illuminate\Http\Request;

class Leger extends Controller
{
    public function index($id)
    {
        $teacher_subject = TeacherSubject::with('academic','teacher', 'grade', 'grade.studentGrade.student', 'subject', 'competencies', 'exam')->find($id);
        // return $teacher_subject;
        return view('leger.index', ['data' => $teacher_subject]);
    }
}
