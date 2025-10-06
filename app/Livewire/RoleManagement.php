<?php

namespace App\Livewire;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class RoleManagement extends Component
{
    use WithPagination;

    public $name = '';
    public $display_name = '';
    public $description = '';
    public $permissions = [];
    public $is_active = true;
    
    #[Url(as: 'q')]
    public $search = '';
    
    public $selectedRoleId = null;
    public $showModal = false;
    public $isEditing = false;

    protected $queryString = ['search' => ['except' => '']];

    protected $rules = [
        'name' => 'required|min:2|unique:roles,name',
        'display_name' => 'required|min:2',
        'description' => 'nullable|string',
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    protected $listeners = [
        'refreshRoles' => '$refresh',
    ];

    public $availablePermissions = [
        // System Management
        'manage_users' => 'Manage Users',
        'manage_roles' => 'Manage Roles',
        'view_users' => 'View Users',
        'manage_settings' => 'Manage Settings',
        
        // Raw Materials Management
        'manage_raw_materials' => 'Manage Raw Materials',
        'view_raw_materials' => 'View Raw Materials',
        'manage_categories' => 'Manage Raw Material Categories',
        'view_categories' => 'View Raw Material Categories',
        
        // References & Specifications
        'manage_references' => 'Manage References',
        'view_references' => 'View References',
        'manage_specifications' => 'Manage Specifications',
        'view_specifications' => 'View Specifications',
        
        // Sample Management
        'manage_samples' => 'Manage Sample Submissions',
        'view_samples' => 'View Sample Submissions',
        'manage_sample_analysis' => 'Manage Sample Analysis',
        'view_sample_analysis' => 'View Sample Analysis',
        
        // Instrument Management
        'manage_instruments' => 'Manage Instruments',
        'view_instruments' => 'View Instruments',
        'manage_instrument_conditions' => 'Manage Instrument Conditions',
        'view_instrument_conditions' => 'View Instrument Conditions',
        
        // Thermohygrometer Management
        'manage_thermohygrometers' => 'Manage Thermohygrometers',
        'view_thermohygrometers' => 'View Thermohygrometers',
        'manage_thermohygrometer_conditions' => 'Manage Thermohygrometer Conditions',
        'view_thermohygrometer_conditions' => 'View Thermohygrometer Conditions',
        
        // Reports & Analytics
        'view_reports' => 'View Reports',
        'view_analysis_reports' => 'View Analysis Reports',
        'view_audit_reports' => 'View Audit Reports',
        'export_reports' => 'Export Reports',
        
        // Dashboard & Profile
        'view_dashboard' => 'View Dashboard',
        'manage_profile' => 'Manage Own Profile',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['name', 'display_name', 'description', 'permissions', 'is_active', 'selectedRoleId', 'isEditing']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function openEditModal($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->selectedRoleId = $role->id;
        $this->name = $role->name;
        $this->display_name = $role->display_name;
        $this->description = $role->description;
        $this->permissions = $role->permissions ?? [];
        $this->is_active = $role->is_active;
        $this->showModal = true;
        $this->isEditing = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $rules = $this->rules;
        
        if ($this->isEditing) {
            $rules['name'] = 'required|min:2|unique:roles,name,' . $this->selectedRoleId;
        }

        $this->validate($rules);

        $roleData = [
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'permissions' => $this->permissions,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            Role::findOrFail($this->selectedRoleId)->update($roleData);
            session()->flash('success', 'Role updated successfully.');
        } else {
            Role::create($roleData);
            session()->flash('success', 'Role created successfully.');
        }

        $this->closeModal();
        $this->dispatch('refreshRoles');
    }

    public function delete($roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Check if role has users
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role that is assigned to users.');
            return;
        }

        // Prevent deletion of system roles
        if (in_array($role->name, ['admin', 'manager', 'user'])) {
            session()->flash('error', 'Cannot delete system roles.');
            return;
        }

        $role->delete();
        session()->flash('success', 'Role deleted successfully.');
        $this->dispatch('refreshRoles');
    }

    public function toggleStatus($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->update(['is_active' => !$role->is_active]);
        
        $status = $role->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Role {$status} successfully.");
        $this->dispatch('refreshRoles');
    }

    public function selectAllPermissions()
    {
        $this->permissions = array_keys($this->availablePermissions);
    }

    public function deselectAllPermissions()
    {
        $this->permissions = [];
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('display_name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.role-management', [
            'roles' => $roles
        ])->layout('layouts.app')->title('Role Management');
    }
}
