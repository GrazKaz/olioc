<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/dev-login/{type}', function() {

    abort_unless(app()->environment('local'), 403);

    if (request('type') === 'a')
    {
        auth()->login(User::where('role', 10)->first());
    }
    else if (request('type') === 's')
    {
        auth()->login(User::where('role', 7)->first());
    }
    else if (request('type') === 'u')
    {
        auth()->login(User::where('role', 1)->first());
    }

    return redirect()->to('/');

})->name('dev-login');
