<?php

namespace App\Policies;

use App\Models\User;
use App\Models\OrderProduct;

class OrderProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super-admin', 'admin'], 'api') || $user->hasRole(['super-admin', 'admin'], 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrderProduct $model): bool
    {
        return $user->hasRole(['super-admin', 'admin'], 'api') || $user->hasRole(['super-admin', 'admin'], 'web');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super-admin'], 'api') || $user->hasRole(['super-admin'], 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrderProduct $model): bool
    {
        return $user->hasRole(['super-admin'], 'api') || $user->hasRole(['super-admin'], 'web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderProduct $model): bool
    {
        return $user->hasRole(['super-admin'], 'api') || $user->hasRole(['super-admin'], 'web');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrderProduct $model): bool
    {
        return $user->hasRole(['super-admin'], 'api') || $user->hasRole(['super-admin'], 'web');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrderProduct $model): bool
    {
        return $user->hasRole(['super-admin'], 'api') || $user->hasRole(['super-admin'], 'web');
    }
}
