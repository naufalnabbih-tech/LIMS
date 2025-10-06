<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Url;

class UserManagement extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role_id = null;
    
    #[Url(as: 'q')]
    public $search = '';
    
    public $selectedUserId = null;
    public $showModal = false;
    public $isEditing = false;
    
    protected $queryString = ['search' => ['except' => '']];

    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'password' => 'required|min:6',
        'password_confirmation' => 'required|same:password',
        'role_id' => 'required|exists:roles,id',
    ];

    protected $listeners = [
        'refreshUsers' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role_id', 'selectedUserId', 'isEditing']);
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function openEditModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->selectedUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->password = '';
        $this->password_confirmation = '';
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
        try {
            // Simple test first - just flash a message to see if method is called
            session()->flash('error', 'Save method called! About to validate...');
            
            $rules = $this->rules;
            
            if ($this->isEditing) {
                $rules['email'] = 'required|email|unique:users,email,' . $this->selectedUserId;
                if (empty($this->password)) {
                    unset($rules['password'], $rules['password_confirmation']);
                }
            } else {
                $rules['email'] = 'required|email|unique:users,email';
            }

            $this->validate($rules);

            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
            ];

            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            if ($this->isEditing) {
                User::findOrFail($this->selectedUserId)->update($userData);
                session()->flash('success', 'User updated successfully.');
            } else {
                User::create($userData);
                session()->flash('success', 'User created successfully.');
            }

            $this->closeModal();
            $this->dispatch('refreshUsers');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors will be automatically displayed
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error saving user: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while saving the user: ' . $e->getMessage());
        }
    }

    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->delete();
        session()->flash('success', 'User deleted successfully.');
        $this->dispatch('refreshUsers');
    }

    public function testSave()
    {
        session()->flash('error', 'Test button clicked! Livewire is working. Data: ' . json_encode([
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'password' => $this->password ? 'set' : 'not set'
        ]));
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        $users = User::with('role')
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhereHas('role', function($q) {
                          $q->where('display_name', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $roles = Role::where('is_active', true)->orderBy('name')->get();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles
        ])->layout('layouts.app')->title('User Management');
    }
}
