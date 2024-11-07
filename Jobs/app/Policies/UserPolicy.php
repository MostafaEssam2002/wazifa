<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow only if the user is an admin
        return $user->status === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, User $user): bool
    {
        // Allow if the user is viewing their own profile or is an admin
        return $authUser->id === $user->id || $authUser->status === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // For now, allow creation for all users
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, User $user): bool
    {
        // Allow if the user is updating their own profile or is an admin
        return $authUser->id === $user->id || $authUser->status === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, User $user): bool
    {
        // Allow if the user is an admin (admins can delete users)
        return $authUser->status === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $authUser, User $user): bool
    {
        // Only allow admins to restore users
        return $authUser->status === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $authUser, User $user): bool
    {
        // Only allow admins to permanently delete users
        return $authUser->status === 'admin';
    }
}
