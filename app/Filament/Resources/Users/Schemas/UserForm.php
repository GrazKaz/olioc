<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\OfficeType;
use App\Enums\Role;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
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
                            ->label(__('Password'))
                            ->password()
                            ->dehydrated(fn ($state) => filled($state)),
                    ]),
                Section::make()
                    ->columns(3)
                    ->schema([
                        Radio::make('office_type')
                            ->label(__('Office type'))
                            ->inline()
                            ->options(OfficeType::class)
                            ->live()
                            ->required(),
                        Select::make('county_id')
                            ->label(__('County'))
                            ->relationship('county', 'name')
                            ->disabled(function(Get $get) {

                                $office_type = OfficeType::tryFrom($get('office_type'));

                                if ($office_type == '') return true;
                                else return false;
                            })
                            ->required(),
                        Select::make('commune_id')
                            ->label(__('Commune'))
                            ->relationship('commune', 'name')
                            ->disabled(function(Get $get) {

                                $office_type = OfficeType::tryFrom($get('office_type'));

                                if ($office_type == '' || $office_type == 'P') return true;
                                else return false;
                            })
                            ->required(),
                    ]),
                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('role')
                            ->label(__('Role'))
                            ->options(Role::class)
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
                        Toggle::make('active')
                            ->label(__('Active'))
                            ->columnSpanFull()
                            ->default(true),


                    ]),
            ]);
    }
}
