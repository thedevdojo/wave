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
use Filament\Forms\Components\Placeholder;
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
            ->columns(2)
            ->components([
                Section::make('Referral Information')
                    ->description('Configure the referral code and status')
                    ->schema([
                        Select::make('user_id')
                            ->label('Referrer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('code')
                            ->label('Referral Code')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->helperText('Leave empty to auto-generate'),
                        
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columnSpan(1),
                
                Section::make('Performance Metrics')
                    ->description('Real-time referral statistics')
                    ->schema([
                        Placeholder::make('clicks')
                            ->label('Total Clicks')
                            ->content(fn (?Referral $record): string => $record ? number_format($record->clicks) : '0'),
                        
                        Placeholder::make('conversions')
                            ->label('Conversions')
                            ->content(fn (?Referral $record): string => $record ? number_format($record->conversions) : '0'),
                        
                        Placeholder::make('conversion_rate')
                            ->label('Conversion Rate')
                            ->content(fn (?Referral $record): string => $record && $record->clicks > 0 
                                ? number_format(($record->conversions / $record->clicks) * 100, 2) . '%'
                                : 'N/A'),
                    ])
                    ->columnSpan(1),
                
                Section::make('Conversion Details')
                    ->description('Information about the referred user and conversion')
                    ->schema([
                        Select::make('referred_user_id')
                            ->label('Referred User')
                            ->relationship('referredUser', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled(),
                        
                        DateTimePicker::make('converted_at')
                            ->label('Conversion Date')
                            ->disabled(),
                    ])
                    ->columnSpan(2)
                    ->columns(2)
                    ->collapsed()
                    ->visible(fn (?Referral $record): bool => $record?->conversions > 0),
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
