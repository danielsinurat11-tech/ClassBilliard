<?php

namespace App\Policies;

use App\Models\meja_billiard;
use App\Models\User;

/**
 * TablePolicy
 * 
 * Handles authorization for meja_billiard (Table) model operations
 * admin and super_admin can view tables
 * Only super_admin can create/update/delete tables
 */
class TablePolicy
{
    /**
     * View any tables - allowed for admin and super_admin
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    /**
     * View a table - allowed for admin and super_admin
     */
    public function view(User $user, meja_billiard $table): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    /**
     * Create table - only super_admin
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Update table - only super_admin
     */
    public function update(User $user, meja_billiard $table): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Delete table - only super_admin
     */
    public function delete(User $user, meja_billiard $table): bool
    {
        return $user->hasRole('super_admin');
    }
}
