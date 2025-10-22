<?php

namespace App\Filament\Resources\Applications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rpw')
                    ->label('RPW')
                    ->searchable(),
                TextColumn::make('data_wplywu')
                    ->label('Data wpływu')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('nr_wniosku')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('powiat_gmina')
                    ->label('Powiat/Gmina')
                    ->searchable(),
                TextColumn::make('urzad')
                    ->label('Urząd')
                    ->searchable(),
                TextColumn::make('gmina')
                    ->searchable(),
                TextColumn::make('powiat')
                    ->searchable(),
                TextColumn::make('ulica')
                    ->searchable(),
                TextColumn::make('nr_budynku')
                    ->searchable(),
                TextColumn::make('kod_pocztowy')
                    ->searchable(),
                TextColumn::make('miejscowosc')
                    ->label('Miejscowość')
                    ->searchable(),
                TextColumn::make('task.numer')
                    ->label('Numer zadania')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tresc')
                    ->label('Treść zadania')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('schron_liczba')
                    ->label('Liczba schronów')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('schron_liczba_osob')
                    ->label('Liczba osób w schronach')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mds_liczba')
                    ->label('Liczba MDS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mds_liczba_osob')
                    ->label('Liczba osób w MDS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ukrycie_liczba')
                    ->label('Liczba miejsc ukrycia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ukrycie_liczba_osob')
                    ->label('Liczba osób w miejscach ukrycia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('koszt_calkowity')
                    ->label('Koszt całkowity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wydatki_biezace')
                    ->label('Wydatki bieżące')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wydatki_inwestycyjne')
                    ->label('Wydatki inwestycyjne')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dotacja_biezaca')
                    ->label('Bieżąca dotacja')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dotacja_na_wydatki')
                    ->label('Dotacja na wydatki')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('srodki_biezaca')
                    ->label('Bieżące środki')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('srodki_inwestycyjne')
                    ->label('Środki inwestycyjne')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dyspozycja')
                    ->label('Dyspozycja')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dzial')
                    ->label('Dział')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kwota_umowy')
                    ->label('Kwota umowy')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nr_zad_umowy')
                    ->label('Numer zadania w umowie')
                    ->searchable(),
                TextColumn::make('typ_zadania')
                    ->label('Typ zadania')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wysylka_data')
                    ->label('Data wysyłki')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('wysylka_nr_pozycji')
                    ->label('Numer pozycji - wysyłka ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('zgoda_mswia')
                    ->label('Zgoda MSWiA')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
