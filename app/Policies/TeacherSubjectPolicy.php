<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TeacherSubject;
use App\Models\User;

class TeacherSubjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any TeacherSubject');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TeacherSubject $teachersubject): bool
    {
        return $user->can('view TeacherSubject');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create TeacherSubject');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TeacherSubject $teachersubject): bool
    {
        return $user->can('update TeacherSubject');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TeacherSubject $teachersubject): bool
    {
        return $user->can('delete TeacherSubject');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TeacherSubject $teachersubject): bool
    {
        return $user->can('restore TeacherSubject');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TeacherSubject $teachersubject): bool
    {
        return $user->can('force-delete TeacherSubject');
    }
}
