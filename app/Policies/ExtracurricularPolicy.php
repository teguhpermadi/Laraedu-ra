<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Extracurricular;
use App\Models\User;

class ExtracurricularPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Extracurricular');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Extracurricular $extracurricular): bool
    {
        return $user->can('view Extracurricular');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create Extracurricular');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Extracurricular $extracurricular): bool
    {
        return $user->can('update Extracurricular');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Extracurricular $extracurricular): bool
    {
        return $user->can('delete Extracurricular');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Extracurricular $extracurricular): bool
    {
        return $user->can('restore Extracurricular');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Extracurricular $extracurricular): bool
    {
        return $user->can('force-delete Extracurricular');
    }
}
