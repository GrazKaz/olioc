<?php

use Illuminate\Support\Facades\Route;

Route::get('/dev-login/{type}', function() {

    abort_unless(app()->environment('local'), 403);

    if (request('type') === 'a')
    {
        auth()->login(User::where('role_rank', 10)->first());
    }
    else if (request('type') === 's')
    {
        auth()->login(User::where('role_rank', 5)->first());
    }

    return redirect()->to('/');

})->name('dev-login');
