<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Userable;
use App\Models\User;

class UserablePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Userable');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Userable $userable): bool
    {
        return $user->can('view Userable');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create Userable');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Userable $userable): bool
    {
        return $user->can('update Userable');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Userable $userable): bool
    {
        return $user->can('delete Userable');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Userable $userable): bool
    {
        return $user->can('restore Userable');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Userable $userable): bool
    {
        return $user->can('force-delete Userable');
    }
}
