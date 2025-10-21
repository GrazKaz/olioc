<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;

class RegisterResponse implements RegistrationResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        return redirect()->to(filament()->getLoginUrl())->with('account_message', 'Not verified!');
    }
}
