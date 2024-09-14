<?php

namespace App\User\Livewire\Auth;

use Livewire\Component;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $users;

    public $email;

    public $password;

    public $name;

    public $registerForm = false;

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
    }

    public function login()
    {
        $validated = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            session()->flash('message', 'You have been successfully login.');
            return;
        }

        session()->flash('error', 'email and password are wrong.');
    }

    public function register()
    {
        $this->registerForm = ! $this->registerForm;
    }

    public function registerStore()
    {
        $validated = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->password = Hash::make($validated['password']);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        User::create($data);

        session()->flash('message', 'You have been successfully registered.');

        $this->resetInputFields();
    }

    public function render()
    {
        return view('User::livewire.auth.login-form');
    }
}
