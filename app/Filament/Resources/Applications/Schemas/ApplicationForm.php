<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rpw')
                    ->required(),
                DatePicker::make('data_wplywu')
                    ->required(),
                TextInput::make('nr_wniosku')
                    ->required()
                    ->numeric(),
                TextInput::make('powia_gmina')
                    ->required(),
                TextInput::make('urzad')
                    ->required(),
                TextInput::make('gmina')
                    ->required(),
                TextInput::make('powiat')
                    ->required(),
                TextInput::make('ulica')
                    ->required(),
                TextInput::make('nr_budynku')
                    ->required(),
                TextInput::make('kod_pocztowy')
                    ->required(),
                TextInput::make('miejscowosc')
                    ->required(),
                TextInput::make('task_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tresc')
                    ->required(),
                TextInput::make('schron_liczba')
                    ->numeric(),
                TextInput::make('schron_liczba_osob')
                    ->numeric(),
                TextInput::make('mds_liczba')
                    ->numeric(),
                TextInput::make('mds_liczba_osob')
                    ->numeric(),
                TextInput::make('ukrycie_liczba')
                    ->numeric(),
                TextInput::make('ukrycie_liczba_osob')
                    ->numeric(),
                TextInput::make('koszt_calkowity')
                    ->required()
                    ->numeric(),
                TextInput::make('wydatki_biezace')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('wydatki_inwestycyjne')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('dotacja_biezaca')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('dotacja_na_wydatki')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('srodki_biezaca')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('srodki_inwestycyjne')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('dyspozycja')
                    ->required()
                    ->numeric(),
                TextInput::make('dzial')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->numeric(),
                TextInput::make('kwota_umowy')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('nr_zad_umowy'),
                TextInput::make('typ_zadania')
                    ->numeric(),
                Textarea::make('uwagi')
                    ->columnSpanFull(),
                DatePicker::make('wysylka_data'),
                TextInput::make('wysylka_nr_pzycji')
                    ->numeric(),
                TextInput::make('zgoda_mswia')
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
