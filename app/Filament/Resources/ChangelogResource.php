<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ChangelogResource\Pages\ListChangelogs;
use App\Filament\Resources\ChangelogResource\Pages\CreateChangelog;
use App\Filament\Resources\ChangelogResource\Pages\EditChangelog;
use App\Filament\Resources\ChangelogResource\Pages;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Wave\Changelog;

class ChangelogResource extends Resource
{
    protected static ?string $model = Changelog::class;

    protected static ?string $navigationIcon = 'phosphor-book-open-text-duotone';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(191),
                TextInput::make('description')
                    ->required()
                    ->maxLength(191),
                RichEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('description')
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
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChangelogs::route('/'),
            'create' => CreateChangelog::route('/create'),
            'edit' => EditChangelog::route('/{record}/edit'),
        ];
    }
}
