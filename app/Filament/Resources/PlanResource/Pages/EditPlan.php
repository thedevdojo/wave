<?php

namespace App\Filament\Resources\PlanResource\Pages;

use App\Filament\Resources\PlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class EditPlan extends EditRecord
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        // Check if post_credits was changed
        if ($this->record->wasChanged('post_credits')) {
            $oldValue = $this->record->getOriginal('post_credits');
            $newValue = $this->record->post_credits;
            
            // Get count of users on this plan
            $userCount = \App\Models\User::whereHas('subscription', function ($query) {
                $query->where('plan_id', $this->record->id);
            })->count();
            
            if ($userCount > 0) {
                Notification::make()
                    ->title('Plan Credits Updated')
                    ->body("You've changed the post credits from {$oldValue} to {$newValue}. Would you like to update the {$userCount} users on this plan?")
                    ->actions([
                        Action::make('updateUsers')
                            ->label('Update Users')
                            ->color('success')
                            ->button()
                            ->url(route('filament.admin.resources.plans.index') . "?updateCredits={$this->record->id}")
                            ->close(),
                        Action::make('dismiss')
                            ->label('Not Now')
                            ->close(),
                    ])
                    ->persistent()
                    ->success()
                    ->send();
            }
        }
    }
}
