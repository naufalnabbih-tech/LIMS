<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;
    public $genericError = '';

    public function updatedEmail()
    {
        $this->genericError = '';
    }

    public function updatedPassword()
    {
        $this->genericError = '';
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return $this->redirect('/dashboard', navigate: true);
        }

        // Clear any previous field errors and show generic message instead
        $this->resetErrorBag();
        $this->genericError = 'Incorrect email or password.';
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest')->title('Login');
    }
}
