<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\Attitude;
use App\Models\DataStudent;
use App\Models\StudentCompetency;
use App\Models\StudentGrade;

class StudentGradeObserver
{
    /**
     * Handle the StudentGrade "created" event.
     */
    public function created(StudentGrade $studentGrade): void
    {
        // buat attendance
        Attendance::updateOrCreate([
            'academic_year_id' => $studentGrade['academic_year_id'],
            'grade_id' => $studentGrade['grade_id'],
            'student_id' => $studentGrade['student_id'],
            'sick'=> 0,
            'permission'=> 0,
            'absent'=> 0,
            'note' => '-',
            'achievement' => '-'
        ]);

        // buat attitude
        Attitude::updateOrCreate([
            'academic_year_id' => $studentGrade['academic_year_id'],
            'grade_id' => $studentGrade['grade_id'],
            'student_id' => $studentGrade['student_id'],
        ]);

        // buat tinggi badan dan berat badan
        DataStudent::updateOrCreate([
            'student_id' => $studentGrade['student_id'],
        ],[
            'height' => 0,
            'weight' => 0,
        ]);

        // buat student competency
        $subjects = $studentGrade['teacherSubject'];
        $teacherSubject = $studentGrade->teacherSubject;

        foreach ($subjects as $subject) {
            $comptencies = $subject['competencies'];
            foreach ($comptencies as $competency) {
                $data = [
                    'teacher_subject_id' => $subject['id'],
                    'student_id' => $studentGrade['student_id'],
                    'competency_id' => $competency['id'],
                ];

                StudentCompetency::updateOrCreate($data);
            }
        }
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
        Attendance::where('student_id', $studentGrade['student_id'])->delete();
        Attitude::where('student_id', $studentGrade['student_id'])->delete();
        DataStudent::where('student_id', $studentGrade['student_id'])->delete();
        StudentCompetency::where('student_id', $studentGrade['student_id'])->delete();
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
