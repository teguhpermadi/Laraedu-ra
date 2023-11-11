<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TeacherExtracurricular;
use App\Models\User;

class TeacherExtracurricularPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any TeacherExtracurricular');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TeacherExtracurricular $teacherextracurricular): bool
    {
        return $user->can('view TeacherExtracurricular');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create TeacherExtracurricular');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TeacherExtracurricular $teacherextracurricular): bool
    {
        return $user->can('update TeacherExtracurricular');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TeacherExtracurricular $teacherextracurricular): bool
    {
        return $user->can('delete TeacherExtracurricular');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TeacherExtracurricular $teacherextracurricular): bool
    {
        return $user->can('restore TeacherExtracurricular');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TeacherExtracurricular $teacherextracurricular): bool
    {
        return $user->can('force-delete TeacherExtracurricular');
    }
}
