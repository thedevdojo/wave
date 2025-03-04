<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use Wave\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'phosphor-tag-duotone';

    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(191),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(191),
                                Forms\Components\Select::make('parent_id')
                                    ->label('Parent Category')
                                    ->options(Category::all()->pluck('name', 'id'))
                                    ->searchable(),
                                Forms\Components\TextInput::make('order')
                                    ->required()
                                    ->numeric()
                                    ->default(1),
                            ]),
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->helperText('If left empty, the category name will be used')
                                    ->maxLength(191),
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->helperText('A brief description of this category for search engines')
                                    ->rows(3)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->helperText('Comma-separated keywords for search engines')
                                    ->maxLength(191),
                            ]),
                        Forms\Components\Tabs\Tab::make('Page Content')
                            ->schema([
                                Forms\Components\TextInput::make('page_heading')
                                    ->label('Page Heading')
                                    ->helperText('The main heading displayed on the category page. If left empty, the category name will be used')
                                    ->maxLength(191),
                                Forms\Components\Textarea::make('page_description')
                                    ->label('Page Description')
                                    ->helperText('A description that will be displayed on the category page')
                                    ->rows(3),
                                Forms\Components\FileUpload::make('image')
                                    ->label('Featured Image')
                                    ->helperText('Image to be used for this category')
                                    ->image()
                                    ->directory('categories')
                                    ->visibility('public'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
