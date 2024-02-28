<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('category-view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        if ($user->id !== $category->user_id && $user->parent_id !== $category->user_id) {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('category-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        if ($user->id !== $category->user_id && $user->parent_id !== $category->user_id) {
            return false;
        }
        return $user->hasPermissionTo('category-update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        if ($user->id !== $category->user_id && $user->parent_id !== $category->user_id) {
            return false;
        }
        return $user->hasPermissionTo('category-delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        if ($user->id !== $category->user_id && $user->parent_id !== $category->user_id) {
            return false;
        }
        return $user->hasPermissionTo('category-restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        if ($user->id !== $category->user_id && $user->parent_id !== $category->user_id) {
            return false;
        }
        return $user->hasPermissionTo('category-force-delete');
    }
}
