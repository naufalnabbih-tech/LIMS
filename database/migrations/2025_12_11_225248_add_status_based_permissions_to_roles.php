<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add status-based permissions to admin and operator roles
        $statusPermissions = [
            'view_pending_samples',
            'view_in_progress_samples',
            'view_completed_samples',
            'view_reviewed_samples',
            'view_approved_samples',
        ];

        $rolesToUpdate = ['admin', 'operator'];

        foreach ($rolesToUpdate as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $permissions = $role->permissions ?? [];

                // Add status permissions if not exists
                foreach ($statusPermissions as $permission) {
                    if (!in_array($permission, $permissions)) {
                        $permissions[] = $permission;
                    }
                }

                $role->update(['permissions' => $permissions]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove status-based permissions from roles
        $statusPermissions = [
            'view_pending_samples',
            'view_in_progress_samples',
            'view_completed_samples',
            'view_reviewed_samples',
            'view_approved_samples',
        ];

        $rolesToUpdate = ['admin', 'operator'];

        foreach ($rolesToUpdate as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $permissions = $role->permissions ?? [];

                // Remove status permissions
                $permissions = array_values(array_filter($permissions, function($perm) use ($statusPermissions) {
                    return !in_array($perm, $statusPermissions);
                }));

                $role->update(['permissions' => $permissions]);
            }
        }
    }
};
