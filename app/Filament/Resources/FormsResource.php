<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FormsResource\Pages\ListForms;
use App\Filament\Resources\FormsResource\Pages\CreateForms;
use App\Filament\Resources\FormsResource\Pages\EditForms;
use App\Filament\Resources\FormsResource\Pages;
use App\Models\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FormsResource extends Resource
{
    protected static ?string $model = Forms::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(191),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(191),

                Repeater::make('fields')
                    ->schema([
                        TextInput::make('label')->required(),
                        Select::make('type')
                            ->options(config('forms.types'))
                            ->required(),
                        TextInput::make('rules'),
                        // Repeater::make('options')
                        //         ->schema([
                        //             TextInput::make('option')->required(),
                        //         ])->columnSpanFull()
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Is Active')
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('slug'),
                ToggleColumn::make('is_active'),
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
            'index' => ListForms::route('/'),
            'create' => CreateForms::route('/create'),
            'edit' => EditForms::route('/{record}/edit'),
        ];
    }
}
