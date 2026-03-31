<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', fn() => redirect()->route('dashboard'))->name('login.post');
Route::get('/logout', fn() => redirect()->route('login'))->name('logout');

// App (agrupar con middleware 'auth' cuando haya backend)
Route::get('/dashboard', fn() => view('dashboard.index'))->name('dashboard');
Route::get('/polizas', fn() => view('dashboard.index'))->name('polizas.index'); // Placeholder to dashboard
Route::get('/polizas/nueva', fn() => view('polizas.create'))->name('polizas.create');
