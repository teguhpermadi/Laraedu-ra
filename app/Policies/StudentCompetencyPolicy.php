<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StudentCompetency;
use App\Models\User;

class StudentCompetencyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any StudentCompetency');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentCompetency $studentcompetency): bool
    {
        return $user->can('view StudentCompetency');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create StudentCompetency');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentCompetency $studentcompetency): bool
    {
        return $user->can('update StudentCompetency');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentCompetency $studentcompetency): bool
    {
        return $user->can('delete StudentCompetency');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentCompetency $studentcompetency): bool
    {
        return $user->can('restore StudentCompetency');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentCompetency $studentcompetency): bool
    {
        return $user->can('force-delete StudentCompetency');
    }
}
