<?php

namespace App\Filament\Resources\Referrals;

use App\Filament\Resources\Referrals\Pages\CreateReferral;
use App\Filament\Resources\Referrals\Pages\EditReferral;
use App\Filament\Resources\Referrals\Pages\ListReferrals;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Wave\Referral;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;

    protected static BackedEnum|string|null $navigationIcon = 'phosphor-share-network-duotone';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Referral Details')
                    ->description('Manage referral codes and track conversions')
                    ->schema([
                        Select::make('user_id')
                            ->label('Referrer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        TextInput::make('code')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(3),
                Section::make('Statistics')
                    ->description('View referral performance metrics')
                    ->schema([
                        TextInput::make('clicks')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                        TextInput::make('conversions')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                        Select::make('referred_user_id')
                            ->label('Referred User')
                            ->relationship('referredUser', 'name')
                            ->searchable()
                            ->disabled(),
                        DateTimePicker::make('converted_at')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Referrer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('clicks')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('conversions')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'suspended' => 'danger',
                    }),
                TextColumn::make('converted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
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
            'index' => ListReferrals::route('/'),
            'create' => CreateReferral::route('/create'),
            'edit' => EditReferral::route('/{record}/edit'),
        ];
    }
}
