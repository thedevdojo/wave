<?php

namespace Wave\Actions\Referrals;

use Wave\Referral;
use Wave\ReferralReward;
use Wave\Subscription;
use Wave\User;

class ProcessReferralConversion
{
    /**
     * Process a referral conversion when a user subscribes.
     */
    public function handle(User $user, Subscription $subscription): void
    {
        // Check if user was referred
        $referralCode = request()->cookie('referral_code');

        if (! $referralCode) {
            return;
        }

        // Find the referral
        $referral = Referral::where('code', $referralCode)
            ->where('status', 'active')
            ->first();

        if (! $referral) {
            return;
        }

        // Prevent self-referrals
        if ($referral->user_id === $user->id) {
            return;
        }

        // Check if user was already converted
        $existingConversion = Referral::where('referred_user_id', $user->id)->exists();

        if ($existingConversion) {
            return;
        }

        // Mark referral as converted
        $referral->markAsConverted($user->id);

        // Calculate reward based on subscription plan
        $this->createReward($referral, $subscription);

        // Clear the referral cookie
        cookie()->queue(cookie()->forget('referral_code'));
    }

    /**
     * Create a reward for the referrer.
     */
    protected function createReward(Referral $referral, Subscription $subscription): void
    {
        $plan = $subscription->plan;

        // Calculate commission (20% of first payment)
        $basePrice = $subscription->cycle === 'month'
            ? (float) $plan->monthly_price
            : (float) $plan->yearly_price;

        $commissionAmount = $basePrice * 0.20;

        // Create the reward
        ReferralReward::create([
            'referral_id' => $referral->id,
            'user_id' => $referral->user_id,
            'type' => 'commission',
            'amount' => $commissionAmount,
            'currency' => 'USD',
            'status' => 'pending',
            'description' => "Commission from {$plan->name} subscription",
        ]);
    }
}
