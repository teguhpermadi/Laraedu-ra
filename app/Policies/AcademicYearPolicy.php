<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AcademicYear;
use App\Models\User;

class AcademicYearPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any AcademicYear');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AcademicYear $academicyear): bool
    {
        return $user->can('view AcademicYear');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create AcademicYear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AcademicYear $academicyear): bool
    {
        return $user->can('update AcademicYear');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AcademicYear $academicyear): bool
    {
        return $user->can('delete AcademicYear');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AcademicYear $academicyear): bool
    {
        return $user->can('restore AcademicYear');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AcademicYear $academicyear): bool
    {
        return $user->can('force-delete AcademicYear');
    }
}
