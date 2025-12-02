<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DosenLogin extends Component
{
    public $email, $password;

    public function login()
    {
        if (Auth::guard('dosen')->attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect('/dosen'); // Arahkan ke dashboard dosen
        }

        session()->flash('error', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.dosen-login'); // Sesuaikan dengan nama file Blade
    }
}
