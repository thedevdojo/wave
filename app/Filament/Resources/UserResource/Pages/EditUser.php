<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Wave\Plan;
use Wave\Subscription;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Handle subscription plan changes
        $planId = $this->data['subscription_plan'] ?? null;
        
        if ($planId) {
            $plan = Plan::find($planId);
            
            if ($plan) {
                // Check if user already has a subscription
                $existingSubscription = $this->record->subscription;
                $planChanged = false;
                
                if ($existingSubscription) {
                    // Check if plan has changed
                    $planChanged = $existingSubscription->plan_id != $plan->id;
                    
                    // Update existing subscription
                    $existingSubscription->update([
                        'plan_id' => $plan->id,
                        'status' => 'active',
                    ]);
                } else {
                    // Create new subscription
                    Subscription::create([
                        'billable_id' => $this->record->id,
                        'billable_type' => 'user',
                        'plan_id' => $plan->id,
                        'status' => 'active',
                        'vendor_subscription_id' => 'manual_' . Str::random(10),
                        'vendor_slug' => 'manual',
                        'cycle' => 'month',
                    ]);
                    
                    $planChanged = true;
                }
                
                // Update user roles based on plan
                $this->record->switchPlans($plan);
                
                // Add plan credits to user if plan was changed and plan has credits
                if ($planChanged && $plan->post_credits > 0) {
                    $this->record->addPostCredits($plan->post_credits);
                    
                    Notification::make()
                        ->title('Credits Added')
                        ->body("{$plan->post_credits} credits have been added to this user from the {$plan->name} plan.")
                        ->success()
                        ->send();
                }
            }
        }
        
        // Handle manual post_credits updates
        if (isset($this->data['post_credits']) && $this->record->getOriginal('post_credits') != $this->data['post_credits']) {
            // Get the original and new credit values
            $originalCredits = (int)$this->record->getOriginal('post_credits');
            $newCredits = (int)$this->data['post_credits'];
            $difference = $newCredits - $originalCredits;
            
            // Use a direct database query to update the credits
            DB::table('users')
                ->where('id', $this->record->id)
                ->update(['post_credits' => $newCredits]);
                
            // Refresh the model to get the updated data
            $this->record->refresh();
            
            // Show a notification about the credit change
            $actionText = $difference > 0 ? 'added to' : 'removed from';
            $absChange = abs($difference);
            
            Notification::make()
                ->title('Credits Updated')
                ->body("{$absChange} credits have been {$actionText} this user.")
                ->success()
                ->send();
        }
    }
}
