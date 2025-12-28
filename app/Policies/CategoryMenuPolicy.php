<?php

namespace App\Policies;

use App\Models\CategoryMenu;
use App\Models\User;

/**
 * CategoryMenuPolicy
 * 
 * Handles authorization for CategoryMenu model operations
 * Only admin and super_admin can manage categories
 */
class CategoryMenuPolicy
{
    /**
     * View any categories - allowed for admin and super_admin
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    /**
     * View a category - allowed for admin and super_admin
     */
    public function view(User $user, CategoryMenu $category): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    /**
     * Create category - only super_admin
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Update category - only super_admin
     */
    public function update(User $user, CategoryMenu $category): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Delete category - only super_admin
     */
    public function delete(User $user, CategoryMenu $category): bool
    {
        return $user->hasRole('super_admin');
    }
}
