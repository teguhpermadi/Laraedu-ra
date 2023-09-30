<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\DataTeacher;
use App\Models\User;

class DataTeacherPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any DataTeacher');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataTeacher $datateacher): bool
    {
        return $user->can('view DataTeacher');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create DataTeacher');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataTeacher $datateacher): bool
    {
        return $user->can('update DataTeacher');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DataTeacher $datateacher): bool
    {
        return $user->can('delete DataTeacher');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DataTeacher $datateacher): bool
    {
        return $user->can('restore DataTeacher');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DataTeacher $datateacher): bool
    {
        return $user->can('force-delete DataTeacher');
    }
}
