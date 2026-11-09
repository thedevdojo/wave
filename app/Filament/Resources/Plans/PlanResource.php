<?php

namespace App\Filament\Resources\Plans;

use App\Filament\Resources\Plans\Pages\CreatePlan;
use App\Filament\Resources\Plans\Pages\EditPlan;
use App\Filament\Resources\Plans\Pages\ListPlans;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
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
                        Select::make('currency')
                            ->default('$')
                            ->options([
                                '$' => '$',
                                '€' => '€',
                                '£' => '£',
                                '¥' => '¥',
                            ]),
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
                Section::make('Feature Limits')
                    ->description('Set usage limits for this plan. Leave empty for unlimited. Use -1 for explicitly unlimited, 0 to disable.')
                    ->schema([
                        KeyValue::make('limits')
                            ->keyLabel('Feature')
                            ->valueLabel('Limit')
                            ->keyPlaceholder('e.g., api_keys')
                            ->valuePlaceholder('e.g., 10')
                            ->reorderable()
                            ->rules([
                                fn (): Closure => function (string $attribute, mixed $value, Closure $fail) {
                                    foreach ((array) $value as $feature => $limit) {
                                        $limit = trim((string) $limit);

                                        if ($limit !== '' && (filter_var($limit, FILTER_VALIDATE_INT) === false || (int) $limit < -1)) {
                                            $fail("The limit for \"{$feature}\" must be a whole number, -1 or empty.");
                                        }
                                    }
                                },
                            ])
                            ->columnSpanFull(),
                    ]),
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
                TextColumn::make('currency')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                DeleteAction::make()
                    ->before(fn (DeleteAction $action, Plan $record) => static::preventDeletingSubscribedPlans($action, [$record->getKey()])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(fn (DeleteBulkAction $action, $records) => static::preventDeletingSubscribedPlans($action, $records->modelKeys())),
                ]),
            ]);
    }

    /**
     * Deleting a plan cascades to its subscriptions (billing history and paying
     * customers), so refuse while any exist and point the admin at deactivating.
     */
    public static function preventDeletingSubscribedPlans(Action $action, array $planIds): void
    {
        $names = Plan::whereKey($planIds)->has('subscriptions')->pluck('name');

        if ($names->isEmpty()) {
            return;
        }

        Notification::make()
            ->danger()
            ->title($names->count() === 1 ? 'This plan has subscriptions' : 'Some plans have subscriptions')
            ->body($names->join(', ').' can\'t be deleted because subscriptions use '.($names->count() === 1 ? 'it' : 'them').'. Turn off "Active" instead to hide a plan from new customers.')
            ->persistent()
            ->send();

        $action->cancel();
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
