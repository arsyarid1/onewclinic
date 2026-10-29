<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'Kombinasi email atau kata sandi tidak cocok dengan data kami.',
            ]);
        }

        session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function quickFill(string $role): void
    {
        switch ($role) {
            case 'superadmin':
                $this->email = 'admin@onewclinic.com';
                $this->password = 'password';
                break;
            case 'receptionist':
                $this->email = 'receptionist@onewclinic.com';
                $this->password = 'password';
                break;
            case 'nurse':
                $this->email = 'nurse@onewclinic.com';
                $this->password = 'password';
                break;
            case 'doctor':
                $this->email = 'doctor@onewclinic.com';
                $this->password = 'password';
                break;
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
