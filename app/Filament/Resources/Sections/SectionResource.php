<?php

namespace App\Filament\Resources\Sections;

use App\Filament\Resources\Sections\Pages\ManageSections;
use App\Models\Section;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;



    protected static ?string $recordTitleAttribute = 'Section';

    public static function getNavigationLabel(): string
    {
        return __('Sections');
    }

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): string
    {
        return __('Dictionaries');
    }

    public static function getBreadcrumb(): string
    {
        return __('Sections');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('opis')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Section')
            ->columns([
                TextColumn::make('opis')
                    ->sortable()
                    ->searchable(),
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
            'index' => ManageSections::route('/'),
        ];
    }
}
