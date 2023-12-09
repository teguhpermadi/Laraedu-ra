<?php

namespace App\Observers;

use App\Models\Teacher;
use App\Models\TeacherExtracurricular;
use App\Models\User;

class TeacherExtracurricularObserver
{
    /**
     * Handle the TeacherExtracurricular "created" event.
     */
    public function created(TeacherExtracurricular $teacherExtracurricular): void
    {
        // $user = $teacherExtracurricular->teacher_id->userable->user;
        // $user->assignRole('teacher_extracurricular');
        Teacher::find($teacherExtracurricular->teacher_id)->userable->user->assignRole('teacher_extracurricular');
    }

    /**
     * Listen to the User updating event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updating(TeacherExtracurricular $teacherExtracurricular)
    {
    //   if($teacherExtracurricular->isDirty('teacher_id')){
    //     // teacher_id has changed
    //     $new_teacher_id = $teacherExtracurricular->teacher_id; 
    //     Teacher::find($new_teacher_id)->userable->user->assignRole('teacher_extracurricular');
        
    //     $old_teacher_id = $teacherExtracurricular->getOriginal('teacher_id');
    //     Teacher::find($old_teacher_id)->userable->user->removeRole('teacher_extracurricular');
    //   }
    }

    /**
     * Handle the TeacherExtracurricular "updated" event.
     */
    public function updated(TeacherExtracurricular $teacherExtracurricular): void
    {
        // 
    }

    /**
     * Handle the TeacherExtracurricular "deleted" event.
     */
    public function deleted(TeacherExtracurricular $teacherExtracurricular): void
    {
        // delete role dari teacher lama
        $teacher = $teacherExtracurricular->teacher_id;
        $user = Teacher::find($teacher)->userable->user;
        $user->removeRole('teacher_extracurricular');
    }

    /**
     * Handle the TeacherExtracurricular "restored" event.
     */
    public function restored(TeacherExtracurricular $teacherExtracurricular): void
    {
        //
    }

    /**
     * Handle the TeacherExtracurricular "force deleted" event.
     */
    public function forceDeleted(TeacherExtracurricular $teacherExtracurricular): void
    {
        //
    }
}
