<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label(__('Username'))
                    ->required(),
                TextInput::make('name')
                    ->label(__('Firstname'))
                    ->required(),
                TextInput::make('surname')
                    ->label(__('Surname'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('county_id')
                    ->label(__('County'))
                    ->relationship('county', 'name')
                    ->required(),
                TextInput::make('commune_id')
                    ->label(__('Commune'))
                    ->relationship('commune', 'name')
                    ->required(),
                TextInput::make('role')
                    ->label(__('Role'))
                    ->required()
                    ->numeric(),
                Toggle::make('active')
                    ->required(),
                Toggle::make('accepted')
                    ->label(__('Accepted'))
                    ->required(),
                DateTimePicker::make('email_verified_at'),
            ]);
    }
}
