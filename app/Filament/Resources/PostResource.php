<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\User;
use Wave\Post;
use Wave\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'phosphor-newspaper-duotone';

    protected static ?int $navigationSort = 2;

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
                                Forms\Components\TextInput::make('title')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required()
                                    ->maxLength(191),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(191),
                                Forms\Components\Select::make('author_id')
                                    ->label('Author')
                                    ->options(User::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->label('Category')
                                    ->options(Category::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->required()
                                    ->options([
                                        'DRAFT' => 'Draft',
                                        'PUBLISHED' => 'Published',
                                        'ARCHIVED' => 'Archived',
                                    ]),
                                Forms\Components\Toggle::make('featured')
                                    ->label('Featured Post')
                                    ->helperText('Featured posts may be displayed prominently on the site')
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Content')
                            ->schema([
                                Forms\Components\RichEditor::make('body')
                                    ->label('Post Content')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('excerpt')
                                    ->label('Excerpt')
                                    ->helperText('A short summary of the post content')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('image')
                                    ->label('Featured Image')
                                    ->helperText('The main image for this post')
                                    ->image()
                                    ->directory('posts')
                                    ->visibility('public'),
                            ]),
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->helperText('If left empty, the post title will be used')
                                    ->maxLength(191),
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->helperText('A brief description of this post for search engines')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->helperText('Comma-separated keywords for search engines')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\IconColumn::make('featured')
                    ->boolean(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
