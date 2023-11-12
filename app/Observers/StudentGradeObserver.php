<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\StudentGrade;

class StudentGradeObserver
{
    /**
     * Handle the StudentGrade "created" event.
     */
    public function created(StudentGrade $studentGrade): void
    {
        // buat attendance
        Attendance::insert([
            'academic_year_id' => $studentGrade['academic_year_id'],
            'grade_id' => $studentGrade['grade_id'],
            'student_id' => $studentGrade['student_id'],
            'sick'=> 0,
            'permission'=> 0,
            'absent'=> 0,
            'note' => '-',
            'achievement' => '-'
        ]);
    }

    /**
     * Handle the StudentGrade "updated" event.
     */
    public function updated(StudentGrade $studentGrade): void
    {
        //
    }

    /**
     * Handle the StudentGrade "deleted" event.
     */
    public function deleted(StudentGrade $studentGrade): void
    {
        //
    }

    /**
     * Handle the StudentGrade "restored" event.
     */
    public function restored(StudentGrade $studentGrade): void
    {
        //
    }

    /**
     * Handle the StudentGrade "force deleted" event.
     */
    public function forceDeleted(StudentGrade $studentGrade): void
    {
        //
    }
}
