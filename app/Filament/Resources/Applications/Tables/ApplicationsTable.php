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
                    ->searchable(),
                TextColumn::make('data_wplywu')
                    ->date()
                    ->sortable(),
                TextColumn::make('nr_wniosku')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('powia_gmina')
                    ->searchable(),
                TextColumn::make('urzad')
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
                    ->searchable(),
                TextColumn::make('task_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tresc')
                    ->searchable(),
                TextColumn::make('schron_liczba')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('schron_liczba_osob')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mds_liczba')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mds_liczba_osob')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ukrycie_liczba')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ukrycie_liczba_osob')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('koszt_calkowity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wydatki_biezace')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wydatki_inwestycyjne')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dotacja_biezaca')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dotacja_na_wydatki')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('srodki_biezaca')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('srodki_inwestycyjne')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dyspozycja')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dzial')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kwota_umowy')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nr_zad_umowy')
                    ->searchable(),
                TextColumn::make('typ_zadania')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wysylka_data')
                    ->date()
                    ->sortable(),
                TextColumn::make('wysylka_nr_pzycji')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('zgoda_mswia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_id')
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
