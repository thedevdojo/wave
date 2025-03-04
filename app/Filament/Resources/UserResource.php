<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Wave\Plan;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'phosphor-users-duotone';

    protected static ?int $navigationSort = 1;

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
                                Forms\Components\TextInput::make('username')
                                    ->required()
                                    ->maxLength(191),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(191),
                                Forms\Components\FileUpload::make('avatar')
                                    ->image(),
                                Forms\Components\DateTimePicker::make('email_verified_at'),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create'),
                                Forms\Components\Select::make('roles')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required(),
                                Forms\Components\Toggle::make('verified')
                            ]),
                        Forms\Components\Tabs\Tab::make('Subscription & Credits')
                            ->schema([
                                Forms\Components\Select::make('subscription_plan')
                                    ->label('Subscription Plan')
                                    ->options(function () {
                                        // Get all active plans
                                        $plans = Plan::where('active', true)->get();
                                        
                                        // Get the current record ID if we're on the edit page
                                        $recordId = request()->route('record');
                                        
                                        // If we're editing a user, ensure their current plan is included
                                        if ($recordId) {
                                            $user = User::find($recordId);
                                            if ($user && $user->subscription) {
                                                $currentPlan = Plan::find($user->subscription->plan_id);
                                                if ($currentPlan && !$plans->contains('id', $currentPlan->id)) {
                                                    $plans->push($currentPlan);
                                                }
                                            }
                                        }
                                        
                                        // If we still have no plans, get all plans regardless of status
                                        if ($plans->isEmpty()) {
                                            $plans = Plan::all();
                                        }
                                        
                                        return $plans->pluck('name', 'id');
                                    })
                                    ->helperText(function ($state) {
                                        if ($state) {
                                            $plan = Plan::find($state);
                                            if ($plan) {
                                                if ($plan->post_credits > 0) {
                                                    return "This plan includes {$plan->post_credits} post credits";
                                                } else {
                                                    return "Warning: This plan has 0 post credits. Consider updating the plan or assigning credits manually.";
                                                }
                                            }
                                        }
                                        return 'Assign a subscription plan to this user';
                                    })
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->subscription) {
                                            $component->state($record->subscription->plan_id);
                                        }
                                    })
                                    ->dehydrated(false)
                                    ->reactive(),
                                Forms\Components\TextInput::make('post_credits')
                                    ->label('Post Credits')
                                    ->numeric()
                                    ->helperText('Number of post credits available to this user'),
                                Forms\Components\DateTimePicker::make('trial_ends_at')
                                    ->label('Trial Ends At')
                                    ->helperText('When the user\'s trial period ends'),
                                Forms\Components\TextInput::make('verification_code')
                                    ->maxLength(191),
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
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(url('storage/demo/default.png')),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subscription.plan.name')
                    ->label('Plan')
                    ->placeholder('No Plan'),
                Tables\Columns\TextColumn::make('post_credits')
                    ->label('Credits')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Impersonate')
                    ->url(fn ($record) => route('impersonate', $record))
                    ->visible(fn ($record) => auth()->user()->id !== $record->id),
                Tables\Actions\Action::make('Add Credits')
                    ->form([
                        Forms\Components\TextInput::make('credits')
                            ->label('Credits to Add')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->addPostCredits($data['credits']);
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
