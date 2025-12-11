<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => [
                    // User & Role Management
                    'manage_users',
                    'manage_roles',
                    'view_users',
                    'manage_profile',

                    // Raw Materials
                    'manage_raw_materials',
                    'view_raw_materials',
                    'manage_raw_material_categories',
                    'view_raw_material_categories',
                    'manage_raw_material_references',
                    'view_raw_material_references',
                    'manage_raw_material_specifications',
                    'view_raw_material_specifications',

                    // Solders
                    'manage_solders',
                    'view_solders',
                    'manage_solder_categories',
                    'view_solder_categories',
                    'manage_solder_references',
                    'view_solder_references',
                    'manage_solder_specifications',
                    'view_solder_specifications',

                    // Chemicals
                    'manage_chemicals',
                    'view_chemicals',
                    'manage_chemical_categories',
                    'view_chemical_categories',
                    'manage_chemical_references',
                    'view_chemical_references',
                    'manage_chemical_specifications',
                    'view_chemical_specifications',

                    // Samples & Analysis
                    'manage_samples',
                    'view_samples',
                    'manage_sample_analysis',
                    'view_sample_analysis',
                    'submit_samples',
                    'create_chemical_submission',
                    'create_rawmat_submission',
                    'create_solder_submission',
                    'handover_samples',
                    'takeover_samples',
                    'analyze_samples',
                    'review_samples',
                    'approve_samples',
                    'delete_sample',

                    // Instruments
                    'manage_instruments',
                    'view_instruments',
                    'manage_instrument_conditions',
                    'view_instrument_conditions',

                    // Thermohygrometers
                    'manage_thermohygrometers',
                    'view_thermohygrometers',
                    'manage_thermohygrometer_conditions',
                    'view_thermohygrometer_conditions',

                    // Reports & Dashboard
                    'view_reports',
                    'view_analysis_reports',
                    'view_audit_reports',
                    'view_dashboard',

                    // CoA (Certificate of Analysis)
                    'manage_coas',
                    'view_coas',
                    'create_coa',
                    'edit_coa',
                    'approve_coa',
                    'delete_coa',

                    // Settings
                    'manage_settings',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Management access with most permissions',
                'permissions' => [
                    'manage_raw_materials',
                    'manage_categories',
                    'manage_specifications',
                    'manage_references',
                    'manage_samples',
                    'submit_samples',
                    'create_chemical_submission',
                    'create_rawmat_submission',
                    'create_solder_submission',
                    'delete_sample',
                    'view_reports',
                    'view_users',
                    'view_dashboard',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Basic user access for daily operations',
                'permissions' => [
                    'view_raw_materials',
                    'view_categories',
                    'view_specifications',
                    'view_references',
                    'manage_samples',
                    'submit_samples',
                    'create_chemical_submission',
                    'create_rawmat_submission',
                    'create_solder_submission',
                    'view_reports',
                    'view_dashboard',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'operator',
                'display_name' => 'Laboratory Operator',
                'description' => 'Laboratory operator who performs sample analysis',
                'permissions' => [
                    // View access
                    'view_raw_materials',
                    'view_solders',
                    'view_chemicals',
                    'view_samples',
                    'view_sample_analysis',
                    'view_dashboard',
                    'view_coas',

                    // Analysis workflow
                    'analyze_samples',
                    'handover_samples',
                    'takeover_samples',
                    'submit_samples',
                    'create_chemical_submission',
                    'create_rawmat_submission',
                    'create_solder_submission',

                    // Instruments
                    'view_instruments',
                    'view_instrument_conditions',
                    'view_thermohygrometers',
                    'view_thermohygrometer_conditions',

                    // Profile
                    'manage_profile',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'reviewer',
                'display_name' => 'Sample Reviewer',
                'description' => 'Can review sample analysis results',
                'permissions' => [
                    // View access
                    'view_samples',
                    'view_sample_analysis',
                    'view_raw_materials',
                    'view_solders',
                    'view_chemicals',
                    'view_dashboard',
                    'view_reports',
                    'view_coas',

                    // Review permission
                    'review_samples',

                    // Profile
                    'manage_profile',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'approver',
                'display_name' => 'Sample Approver',
                'description' => 'Can approve reviewed samples and create CoA',
                'permissions' => [
                    // View access
                    'view_samples',
                    'view_sample_analysis',
                    'view_raw_materials',
                    'view_solders',
                    'view_chemicals',
                    'view_dashboard',
                    'view_reports',
                    'view_coas',

                    // Approval & CoA permissions
                    'approve_samples',
                    'create_coa',
                    'edit_coa',
                    'manage_coas',

                    // Profile
                    'manage_profile',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
