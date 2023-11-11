<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StudentExtracurricular;
use App\Models\User;

class StudentExtracurricularPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any StudentExtracurricular');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentExtracurricular $studentextracurricular): bool
    {
        return $user->can('view StudentExtracurricular');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create StudentExtracurricular');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentExtracurricular $studentextracurricular): bool
    {
        return $user->can('update StudentExtracurricular');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentExtracurricular $studentextracurricular): bool
    {
        return $user->can('delete StudentExtracurricular');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentExtracurricular $studentextracurricular): bool
    {
        return $user->can('restore StudentExtracurricular');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentExtracurricular $studentextracurricular): bool
    {
        return $user->can('force-delete StudentExtracurricular');
    }
}
