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
        $admin = Role::where('name', 'admin')->first();

        if ($admin) {
            $permissions = $admin->permissions ?? [];

            // Add CoA permissions
            $coaPermissions = [
                'manage_coa',
                'view_coa',
                'create_coa',
                'edit_coa',
                'approve_coa',
                'release_coa',
                'delete_coa',
            ];

            foreach ($coaPermissions as $permission) {
                if (!in_array($permission, $permissions)) {
                    $permissions[] = $permission;
                }
            }

            $admin->permissions = $permissions;
            $admin->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $admin = Role::where('name', 'admin')->first();

        if ($admin) {
            $permissions = $admin->permissions ?? [];

            // Remove CoA permissions
            $coaPermissions = [
                'manage_coa',
                'view_coa',
                'create_coa',
                'edit_coa',
                'approve_coa',
                'release_coa',
                'delete_coa',
            ];

            $admin->permissions = array_values(array_diff($permissions, $coaPermissions));
            $admin->save();
        }
    }
};
