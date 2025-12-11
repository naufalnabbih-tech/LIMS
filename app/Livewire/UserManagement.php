<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;

class UserManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role_id = null;
    public $new_signature_qr = null;
    public $current_user_qr = null;

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
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role_id', 'selectedUserId', 'isEditing', 'new_signature_qr', 'current_user_qr']);
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->current_user_qr = null;
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
        $this->new_signature_qr = null;
        $this->current_user_qr = $user->signature_qr_image ? Storage::url($user->signature_qr_image) : null;
        $this->showModal = true;
        $this->isEditing = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Resize image using PHP GD library
     */
    private function resizeImage($sourcePath, $targetWidth = 150, $targetHeight = 150)
    {
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Create source image based on MIME type
        $sourceImage = null;
        if ($mimeType === 'image/jpeg') {
            $sourceImage = imagecreatefromjpeg($sourcePath);
        } elseif ($mimeType === 'image/png') {
            $sourceImage = imagecreatefrompng($sourcePath);
        }

        if (!$sourceImage) {
            throw new \Exception('Unable to create image from source');
        }

        // Create new image with target dimensions
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve transparency for PNG
        if ($mimeType === 'image/png') {
            imagecolortransparent($targetImage, imagecolorallocatealpha($targetImage, 0, 0, 0, 127));
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
        }

        // Resize image
        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0, 0, 0, 0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight
        );

        // Save to temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'qr_');
        imagepng($targetImage, $tempFile, 9); // 9 = max compression

        // Cleanup
        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        return file_get_contents($tempFile);
    }

    public function uploadQRSignature()
    {
        $this->validate([
            'new_signature_qr' => 'required|image|max:2048|mimes:jpeg,png,jpg',
        ]);

        try {
            $user = User::findOrFail($this->selectedUserId);

            // Delete old signature if exists
            if ($user->signature_qr_image) {
                Storage::disk('public')->delete($user->signature_qr_image);
            }

            // Process and resize image to 150x150px
            $sourcePath = $this->new_signature_qr->getRealPath();
            $resizedImageData = $this->resizeImage($sourcePath, 150, 150);

            $filename = 'signatures/qr_' . $user->id . '_' . time() . '.png';

            // Save to storage
            Storage::disk('public')->put($filename, $resizedImageData);

            // Update user record
            $user->update([
                'signature_qr_image' => $filename,
            ]);

            $this->current_user_qr = Storage::url($filename);
            $this->new_signature_qr = null;

            session()->flash('success', 'QR Signature uploaded successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error uploading QR signature: ' . $e->getMessage());
        }
    }

    public function deleteQRSignature()
    {
        try {
            $user = User::findOrFail($this->selectedUserId);

            if ($user->signature_qr_image) {
                Storage::disk('public')->delete($user->signature_qr_image);

                $user->update([
                    'signature_qr_image' => null,
                ]);

                $this->current_user_qr = null;

                session()->flash('success', 'QR Signature deleted successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting QR signature: ' . $e->getMessage());
        }
    }

    public function updatedNewSignatureQr()
    {
        $this->validate([
            'new_signature_qr' => 'required|image|max:2048|mimes:jpeg,png,jpg',
        ]);
    }

    public function save()
    {
        try {
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

            // Upload QR signature if new one provided
            if ($this->new_signature_qr) {
                $this->uploadQRSignature();
            }

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
        ]);
    }
}
