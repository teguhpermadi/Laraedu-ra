<?php

namespace App\Observers;

use App\Models\TeacherGrade;

class TeacherGradeObserver
{
    /**
     * Handle the TeacherGrade "created" event.
     */
    public function created(TeacherGrade $teacherGrade): void
    {
        $teacherGrade->teacher->userable->user->assignRole('teacher grade');
    }

    /**
     * Handle the TeacherGrade "updated" event.
     */
    public function updated(TeacherGrade $teacherGrade): void
    {
        //
    }

    /**
     * Handle the TeacherGrade "deleted" event.
     */
    public function deleted(TeacherGrade $teacherGrade): void
    {
        //
    }

    /**
     * Handle the TeacherGrade "restored" event.
     */
    public function restored(TeacherGrade $teacherGrade): void
    {
        //
    }

    /**
     * Handle the TeacherGrade "force deleted" event.
     */
    public function forceDeleted(TeacherGrade $teacherGrade): void
    {
        //
    }
}
