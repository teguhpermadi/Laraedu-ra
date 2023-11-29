<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Student;
use App\Models\TeacherGrade;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;

class Leger extends Controller
{
    public function subject($id)
    {
        $data = TeacherSubject::with('academic','teacher', 'grade', 'grade.studentGrade.student', 'subject', 'competencies', 'exam')->find($id);
        // return $teacher_subject;
        return view('leger.subject', ['data' => $data]);
    }

    public function attendance()
    {
        $grade = TeacherGrade::with('grade', 'academic')->where('teacher_id', auth()->user()->userable->userable_id)->first();
        $students = Student::with('attendance')->myStudentGrade()->get();
        // return $grade;
        // return $students;
        return view('leger.attendance', ['students' => $students, 'grade' => $grade]);
    }
}
