<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\OfficeType;
use App\Enums\Role;
use App\Models\Commune;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use function Pest\Laravel\instance;
use function PHPUnit\Framework\isInstanceOf;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make()
                    ->schema([
                        Tab::make('profile')
                            ->label(__('Profile'))
                            ->schema([
                                Section::make()
                                    ->columns(3)
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
                                        TextInput::make('phone_number')
                                            ->label(__('Phone number'))
                                            ->required(),
                                        TextInput::make('password')
                                            ->label(__('Password'))
                                            ->password()
                                            ->required(fn ($operation) => $operation == 'create')
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
                                            ->default(OfficeType::COUNTY)
                                            ->required(),
                                        Select::make('county_id')
                                            ->label(__('County'))
                                            ->relationship('county', 'name')
                                            ->disabled(function(Get $get) {

                                                if ($get('office_type') == null) return true;
                                                else return false;
                                            })
                                            ->live()
                                            ->required()
                                            ->afterStateUpdated(function (Set $set) {
                                                $set('commune_id', null);
                                            })
                                            ->hint(function ($component) {
                                                return new HtmlString(Blade::render('<x-filament::loading-indicator class="loading" wire:loading wire:target="data.office_type" />'));
                                            })
                                            ->extraInputAttributes(function ($component) {
                                                return [
                                                    'wire:loading.attr' => 'disabled',
                                                    'wire:target' => 'data.office_type',
                                                ];
                                            }),
                                        Select::make('commune_id')
                                            ->label(__('Commune'))
                                            ->options(function (Get $get) {
                                                return Commune::where('county_id', $get('county_id'))->pluck('name', 'id');
                                            })
                                            ->required(function(Get $get) {

                                                if ($get('office_type') != null && $get('office_type')->value == 'G') return true;
                                                else return false;
                                            })
                                            ->disabled(function(Get $get) {

                                                if ($get('office_type') == null || $get('office_type')->value == 'P') return true;
                                                else return false;
                                            })
                                            ->hint(function ($component) {
                                                return new HtmlString(Blade::render('<x-filament::loading-indicator class="loading" wire:loading wire:target="data.office_type,data.county_id" />'));
                                            })
                                            ->extraInputAttributes(function ($component) {
                                                return [
                                                    'wire:loading.attr' => 'disabled',
                                                    'wire:target' => 'data.office_type,data.county_id',
                                                ];
                                            }),
                                    ]),
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        Select::make('role')
                                            ->label(__('Role'))
                                            ->options(Role::class)
                                            ->default(Role::USER)
                                            ->required()
                                            ->disabled(function ($operation, $record) {

                                                if ($operation == 'edit') {
                                                    return ($record->id == 1);
                                                }

                                                return false;
                                            }),
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
                                            ->default(true)
                                            ->disabled(function ($operation, $record) {

                                                if ($operation == 'edit') {
                                                    return ($record->id == 1);
                                                }

                                                return false;
                                            }),
                                        Toggle::make('verified')
                                            ->label(__('Verified'))
                                            ->columnSpanFull()
                                            ->default(true)
                                            ->visible(fn ($operation) => $operation == 'create')
                                    ]),
                            ]),
                        Tab::make('address data')
                            ->label(__('Address data'))
                            ->schema([
                                Section::make()
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('street')
                                            ->label(__('Street'))
                                            ->maxLength(255),
                                        TextInput::make('street_number')
                                            ->label(__('Street number'))
                                            ->maxLength(30),
                                        TextInput::make('postal_code')
                                            ->label(__('Postal code'))
                                            ->afterLabel('Format 99-999')
                                            ->regex('/^[0-9]{2}-[0-9]{3}$/')
                                            ->mask('99-999'),
                                        TextInput::make('city')
                                            ->label(__('City'))
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ])
                    ->contained(false),
            ]);
    }
}
