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
        // Add 'start_analysis' permission to admin and operator roles
        $rolesToUpdate = ['admin', 'operator'];

        foreach ($rolesToUpdate as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $permissions = $role->permissions ?? [];

                // Add 'start_analysis' if not exists
                if (!in_array('start_analysis', $permissions)) {
                    // For admin, add after 'takeover_samples'
                    // For operator, add after 'takeover_samples'
                    $index = array_search('takeover_samples', $permissions);

                    if ($index !== false) {
                        array_splice($permissions, $index + 1, 0, 'start_analysis');
                    } else {
                        // Fallback: just add at the end
                        $permissions[] = 'start_analysis';
                    }

                    $role->update(['permissions' => $permissions]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'start_analysis' permission from roles
        $rolesToUpdate = ['admin', 'operator'];

        foreach ($rolesToUpdate as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $permissions = $role->permissions ?? [];

                // Remove 'start_analysis' if exists
                $permissions = array_values(array_filter($permissions, function($perm) {
                    return $perm !== 'start_analysis';
                }));

                $role->update(['permissions' => $permissions]);
            }
        }
    }
};
