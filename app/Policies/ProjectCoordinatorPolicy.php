<?php

namespace App\Policies;

use App\Models\ProjectCoordinator;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectCoordinatorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_project::coordinator');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProjectCoordinator $projectCoordinator): bool
    {
        return $user->can('view_project::coordinator');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_project::coordinator');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectCoordinator $projectCoordinator): bool
    {
        return $user->can('update_project::coordinator');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectCoordinator $projectCoordinator): bool
    {
        return $user->can('delete_project::coordinator');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectCoordinator $projectCoordinator): bool
    {
        return $user->can('restore_project::coordinator');
        
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectCoordinator $projectCoordinator): bool
    {
        return $user->can('force_delete_project::coordinator');   
    }
}
