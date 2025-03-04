<?php

namespace App\Filament\Resources\PlanResource\Pages;

use App\Filament\Resources\PlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Request;
use Wave\Plan;

class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    public function mount(): void
    {
        parent::mount();
        
        // Check if we need to update user credits
        $updateCreditsId = Request::query('updateCredits');
        
        if ($updateCreditsId) {
            $plan = Plan::find($updateCreditsId);
            
            if ($plan) {
                // Get all users with this plan
                $users = \App\Models\User::whereHas('subscription', function ($query) use ($plan) {
                    $query->where('plan_id', $plan->id);
                })->get();
                
                $count = 0;
                foreach ($users as $user) {
                    $user->addPostCredits($plan->post_credits);
                    $count++;
                }
                
                Notification::make()
                    ->title('Credits Updated')
                    ->body("Added {$plan->post_credits} credits to {$count} users on the {$plan->name} plan.")
                    ->success()
                    ->send();
            }
        }
    }
}
