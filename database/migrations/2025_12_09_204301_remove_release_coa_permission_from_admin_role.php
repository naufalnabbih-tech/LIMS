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
            // Remove release_coa permission
            $permissions = array_filter($permissions, fn($p) => $p !== 'release_coa');
            $admin->permissions = array_values($permissions);
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
            if (!in_array('release_coa', $permissions)) {
                $permissions[] = 'release_coa';
            }
            $admin->permissions = $permissions;
            $admin->save();
        }
    }
};
