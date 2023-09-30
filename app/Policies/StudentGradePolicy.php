<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StudentGrade;
use App\Models\User;

class StudentGradePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any StudentGrade');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentGrade $studentgrade): bool
    {
        return $user->can('view StudentGrade');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create StudentGrade');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentGrade $studentgrade): bool
    {
        return $user->can('update StudentGrade');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentGrade $studentgrade): bool
    {
        return $user->can('delete StudentGrade');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentGrade $studentgrade): bool
    {
        return $user->can('restore StudentGrade');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentGrade $studentgrade): bool
    {
        return $user->can('force-delete StudentGrade');
    }
}
