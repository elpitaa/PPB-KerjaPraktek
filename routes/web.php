<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\DosenLogin;

// Route::get('/dosen/login', [App\Http\Livewire\DosenLogin::class, '__invoke'])->name('dosen.login');


Route::get('/', function () {
    return view('homepage');
});
