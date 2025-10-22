<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->label(__('Username'))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('First name'))
                    ->searchable(),
                TextColumn::make('surname')
                    ->label(__('Surname'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('E-mail'))
                    ->searchable(),
                TextColumn::make('county.name')
                    ->label(__('County'))
                    ->searchable(),
                TextColumn::make('commune.name')
                    ->label(__('Commune'))
                    ->sortable(),
                TextColumn::make('role')
                    ->label(__('Role'))
                    ->sortable(),
                IconColumn::make('active')
                    ->label(__('Active'))
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->boolean(),
                IconColumn::make('verified')
                    ->label(__('Verified'))
                    ->boolean()
                    ->default(false)
                    ->trueIcon('fas-stamp')
                    ->falseIcon('fas-question')
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->tooltip(function($record) {

                        return ($record->verified) ? __('Verified on') . ' ' . $record->verified : __('Not verified');
                    }),
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
