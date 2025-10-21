<?php

namespace App\Filament\Resources\Counties;

use App\Filament\Resources\Counties\Pages\ManageCounties;
use App\Models\County;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CountyResource extends Resource
{
    protected static ?string $model = County::class;

    public static function getNavigationLabel(): string
    {
        return __('Counties');
    }

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string
    {
        return __('Dictionaries');
    }

    public static function getBreadcrumb(): string
    {
        return __('Counties');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'County';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('nr_dysp')
                    ->required()
                    ->numeric(),
                Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('County')
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nr_dysp')
                    ->label('Nr dyspozycji')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('active')
                    ->label(__('Active'))
                    ->sortable()
                    ->boolean(),
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
                EditAction::make()
                    ->label(''),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCounties::route('/'),
        ];
    }
}
