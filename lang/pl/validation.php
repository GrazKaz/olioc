<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'required' => 'Pole :attribute jest wymagane.',
    'current_password' => 'Hasło jest niepoprawne.',
    'min' => [
        'numeric' => 'Pole :attribute musi mieć minimum :min znaków.',
        'string' => 'Pole :attribute musi mieć minimum :min znaków.',
    ],
    'size' => [
        'numeric' => 'Pole :attribute musi mieć :size znaków.',
        'string' => 'Pole :attribute musi mieć :size znaków.',
    ],
    'same' => 'Pole :attribute musi być identyczne z polem :other.',
    'unique' => 'Wartość pola :attribute musi być niepowtarzalna.',
    'regex' => 'Format pola :attribute jest niepoprawny.',
    'distinct' => 'Zduplikowana wartość pola.',
];
