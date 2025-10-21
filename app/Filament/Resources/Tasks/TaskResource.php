<?php

namespace App\Filament\Resources\Tasks;

use App\Filament\Resources\Tasks\Pages\ManageTasks;
use App\Models\Task;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    public static function getNavigationLabel(): string
    {
        return __('Tasks');
    }

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): string
    {
        return __('Dictionaries');
    }

    public static function getBreadcrumb(): string
    {
        return __('Tasks');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numer')
                    ->required(),
                TextInput::make('dofinansowanie')
                    ->numeric(),
                TextInput::make('section_id')
                    ->numeric(),
                TextInput::make('ngo_hns'),
                TextInput::make('dodatkowe_info'),
                TextInput::make('opis'),
                TextInput::make('task_type')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Task')
            ->columns([
                TextColumn::make('numer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dofinansowanie')
                    ->suffix('%')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('section.opis')
                    ->label('Rodzaj zadania')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('ngo_hns')
                    ->label('NGO/HNS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dodatkowe_info')
                    ->label('Dodatkowe informacje')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('opis')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('task_type')
                    ->label('Typ')
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
                EditAction::make()
                    ->label(''),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTasks::route('/'),
        ];
    }
}
