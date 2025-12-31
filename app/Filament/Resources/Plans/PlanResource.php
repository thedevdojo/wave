<?php

namespace App\Filament\Resources\Plans;

use App\Filament\Resources\Plans\Pages\CreatePlan;
use App\Filament\Resources\Plans\Pages\EditPlan;
use App\Filament\Resources\Plans\Pages\ListPlans;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use Wave\Plan;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static BackedEnum|string|null $navigationIcon = 'phosphor-credit-card-duotone';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan Details')
                    ->description('Below are the basic details for each plan including name, description, and features')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(191)
                            ->columnSpan(2),
                        Textarea::make('description')
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                            ]),
                        TagsInput::make('features')
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
                        TextInput::make('monthly_price_id')
                            ->label('Monthly Price ID')
                            ->hint('Stripe/Paddle ID')
                            ->maxLength(191),
                        TextInput::make('monthly_price')
                            ->maxLength(191),
                        TextInput::make('yearly_price_id')
                            ->label('Yearly Price ID')
                            ->hint('Stripe/Paddle ID')
                            ->maxLength(191),
                        TextInput::make('yearly_price')
                            ->maxLength(191),
                        TextInput::make('onetime_price_id')
                            ->label('One-time Price ID')
                            ->hint('Stripe/Paddle ID')
                            ->maxLength(191),
                        TextInput::make('onetime_price')
                            ->maxLength(191),
                    ])->columns(2),
                Section::make('Plan Status')
                    ->description('Make the plan default or active/inactive and set the sort order')
                    ->schema([
                        Toggle::make('active')
                            ->required(),
                        Toggle::make('default')
                            ->required(),
                        TextInput::make('sort_order')
                            ->integer()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                    ])->columns(2),
                Section::make('Associated Role')
                    ->description('When the user subscribes to this plan, what role should they be assigned?')
                    ->schema([
                        Select::make('role_id')
                            ->label('Role')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('role_id')
                    ->numeric()
                    ->sortable(),
                BooleanColumn::make('active')
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
            ->defaultSort('sort_order')
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
            'index' => ListPlans::route('/'),
            'create' => CreatePlan::route('/create'),
            'edit' => EditPlan::route('/{record}/edit'),
        ];
    }
}
