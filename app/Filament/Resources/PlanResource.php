<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use Wave\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'phosphor-credit-card-duotone';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Plan Details')
                    ->description('Below are the basic details for each plan including name, description, and features')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(191)
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('description')
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                            ]),
                        Forms\Components\TagsInput::make('features')
                            ->reorderable()
                            ->separator(',')
                            ->placeholder('New feature')
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                            ]),
                    ])->columns(2),
                Section::make('Plan Pricing')
                    ->description('Add the pricing details for your plans below')
                    ->schema([ 
                        Forms\Components\TextInput::make('monthly_price_id')
                            ->label('Monthly Price ID')
                            ->hint('Stripe/Paddle ID')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('monthly_price')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('yearly_price_id')
                            ->label('Yearly Price ID')
                            ->hint('Stripe/Paddle ID')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('yearly_price')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('onetime_price_id')
                            ->label('One-time Price ID')
                            ->hint('Stripe/Paddle ID')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('onetime_price')
                            ->maxLength(191),
                    ])->columns(2),
                Section::make('Plan Status')
                    ->description('Make the plan default or active/inactive')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->required(),
                        Forms\Components\Toggle::make('default')
                            ->required()
                    ])->columns(2),
                Section::make('Associated Role')
                    ->description('When the user subscribes to this plan, what role should they be assigned?')
                    ->schema([
                        Forms\Components\Select::make('role_id')
                            ->label('Role')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('active')
                    ->sortable(),    
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
