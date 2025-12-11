<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $signature_qr_image;
    public $new_signature_qr;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'new_signature_qr' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
    ];

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->signature_qr_image = $user->signature_qr_image;
    }

    public function updatedNewSignatureQr()
    {
        $this->validate([
            'new_signature_qr' => 'required|image|max:2048|mimes:jpeg,png,jpg',
        ]);
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Profile updated successfully!');
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

    public function uploadSignatureQR()
    {
        $this->validate([
            'new_signature_qr' => 'required|image|max:2048|mimes:jpeg,png,jpg',
        ]);

        $user = auth()->user();

        try {
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

            $this->signature_qr_image = $filename;
            $this->new_signature_qr = null;

            session()->flash('message', 'QR Signature uploaded and resized to 150x150px successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error uploading QR signature: ' . $e->getMessage());
        }
    }

    public function deleteSignatureQR()
    {
        $user = auth()->user();

        if ($user->signature_qr_image) {
            Storage::disk('public')->delete($user->signature_qr_image);

            $user->update([
                'signature_qr_image' => null,
            ]);

            $this->signature_qr_image = null;

            session()->flash('message', 'QR Signature deleted successfully!');
        }
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}
