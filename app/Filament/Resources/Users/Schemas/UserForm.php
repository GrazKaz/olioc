<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
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
                    ->dehydrated(fn ($state) => filled($state)),
                Select::make('county_id')
                    ->label(__('County'))
                    ->relationship('county', 'name')
                    ->required(),
                Select::make('commune_id')
                    ->label(__('Commune'))
                    ->relationship('commune', 'name')
                    ->required(),
                Select::make('role')
                    ->label(__('Role'))
                    ->options(Role::class)
                    ->required(),
                Toggle::make('active')
                    ->required(),
                TextEntry::make('verified')
                    ->label(__('Verified'))
                    ->state(function ($record) {

                        if ($record->verified === null) {
                            return __('Not verified');
                        }

                        return $record->verified;
                    })
                    ->visible(fn ($operation) => $operation == 'edit')
                    ->extraAttributes(['class' => 'placeholder']),
            ]);
    }
}
