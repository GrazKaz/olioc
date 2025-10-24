<?php

namespace App\Filament\Resources\Communes;

use App\Filament\Resources\Communes\Pages\ManageCommunes;
use App\Models\Commune;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommuneResource extends Resource
{
    protected static ?string $model = Commune::class;

    protected static ?string $recordTitleAttribute = 'Commune';

    //protected static string|BackedEnum|null $navigationIcon = 'mdi-factory';

    public static function getNavigationLabel(): string
    {
        return __('Communes');
    }

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string
    {
        return __('Dictionaries');
    }

    public static function getBreadcrumb(): string
    {
        return __('Communes');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                            Select::make('county_id')
                                ->relationship('county', 'name')
                                ->label(__('County'))
                                ->required(),
                            TextInput::make('nr_dysp')
                                ->label('Numer dyspozycji')
                                ->required()
                                ->numeric(),
                            TextInput::make('office')
                                ->label('Urząd')
                                ->required(),
                            TextInput::make('city')
                                ->label(__('City'))
                                ->required(),
                            TextInput::make('name')
                                ->label(__('Name'))
                                ->required(),
                        ]),
                        Section::make()
                            ->columns(2)
                            ->schema([
                            Toggle::make('active')
                                ->required(),
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Commune')
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('county.name')
                    ->label(__('County'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nr_dysp')
                    ->label('Numer dyspozycji')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('office')
                    ->label(__('Office'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('city')
                    ->label(__('City'))
                    ->searchable()
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

            ])
            ->defaultSort('name', direction: 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCommunes::route('/'),
        ];
    }
}
