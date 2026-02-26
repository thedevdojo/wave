<?php

/**
 * Referral System Test Suite
 *
 * Tests the referral tracking system including:
 * - Referral code generation and uniqueness
 * - Cookie-based tracking
 * - Click counting
 * - Conversion tracking
 * - Reward calculation
 * - User relationships
 */

use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Wave\Actions\Referrals\ProcessReferralConversion;
use Wave\Plan;
use Wave\Referral;
use Wave\ReferralReward;
use Wave\Subscription;

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed();

    // Create test users
    $this->referrer = User::factory()->create();
    $this->referred = User::factory()->create();

    // Create a test plan
    $this->plan = Plan::first();
});

test('user can generate unique referral code', function () {
    $code = $this->referrer->getOrCreateReferralCode();

    expect($code)->toBeString()
        ->and(strlen($code))->toBe(8)
        ->and($this->referrer->referrals()->count())->toBe(1);
});

test('referral code is unique', function () {
    $code1 = Referral::generateUniqueCode();
    $code2 = Referral::generateUniqueCode();

    expect($code1)->not->toBe($code2);
});

test('existing referral code is returned if already exists', function () {
    $firstCode = $this->referrer->getOrCreateReferralCode();
    $secondCode = $this->referrer->getOrCreateReferralCode();

    expect($firstCode)->toBe($secondCode)
        ->and($this->referrer->referrals()->count())->toBe(1);
});

test('referral tracks clicks', function () {
    $referral = $this->referrer->referrals()->create([
        'code' => 'TEST1234',
        'status' => 'active',
        'clicks' => 0,
        'conversions' => 0,
    ]);

    expect($referral->clicks)->toBe(0);

    $referral->incrementClicks();
    $referral->incrementClicks();

    expect($referral->fresh()->clicks)->toBe(2);
});

test('referral can be marked as converted', function () {
    $referral = $this->referrer->referrals()->create([
        'code' => 'TEST1234',
        'status' => 'active',
    ]);

    $referral->markAsConverted($this->referred->id);

    expect($referral->fresh()->conversions)->toBe(1)
        ->and($referral->fresh()->referred_user_id)->toBe($this->referred->id)
        ->and($referral->fresh()->converted_at)->not->toBeNull();
});

test('referral relationships work correctly', function () {
    $referral = $this->referrer->referrals()->create([
        'code' => 'TEST1234',
        'status' => 'active',
        'referred_user_id' => $this->referred->id,
    ]);

    expect($referral->user->id)->toBe($this->referrer->id)
        ->and($referral->referredUser->id)->toBe($this->referred->id);
});

test('user can get referral statistics', function () {
    $referral = $this->referrer->referrals()->create([
        'code' => 'TEST1234',
        'status' => 'active',
    ]);

    // Create some rewards
    ReferralReward::create([
        'referral_id' => $referral->id,
        'user_id' => $this->referrer->id,
        'type' => 'commission',
        'amount' => 10.00,
        'status' => 'pending',
    ]);

    ReferralReward::create([
        'referral_id' => $referral->id,
        'user_id' => $this->referrer->id,
        'type' => 'commission',
        'amount' => 15.00,
        'status' => 'paid',
    ]);

    expect($this->referrer->totalReferralEarnings())->toBe(25.0)
        ->and($this->referrer->pendingReferralEarnings())->toBe(10.0)
        ->and($this->referrer->paidReferralEarnings())->toBe(15.0);
});

test('referral reward can be marked as paid', function () {
    $referral = $this->referrer->referrals()->create([
        'code' => 'TEST1234',
        'status' => 'active',
    ]);

    $reward = ReferralReward::create([
        'referral_id' => $referral->id,
        'user_id' => $this->referrer->id,
        'type' => 'commission',
        'amount' => 20.00,
        'status' => 'pending',
    ]);

    expect($reward->isPending())->toBeTrue()
        ->and($reward->isPaid())->toBeFalse();

    $reward->markAsPaid();

    expect($reward->fresh()->isPaid())->toBeTrue()
        ->and($reward->fresh()->paid_at)->not->toBeNull();
});

test('referral conversion processes correctly on subscription', function () {
    // Create referral code
    $referralCode = $this->referrer->getOrCreateReferralCode();

    // Simulate cookie with referral code
    Cookie::queue('referral_code', $referralCode, 60 * 24 * 30);

    // Create subscription for referred user
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->referred->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Mock request with cookie
    request()->cookies->set('referral_code', $referralCode);

    // Process conversion
    app(ProcessReferralConversion::class)->handle($this->referred, $subscription);

    $referral = Referral::where('code', $referralCode)->first();

    expect($referral->conversions)->toBe(1)
        ->and($referral->referred_user_id)->toBe($this->referred->id)
        ->and($referral->rewards()->count())->toBe(1);

    $reward = $referral->rewards()->first();

    // Check that commission is 20% of plan price
    $expectedAmount = floatval($this->plan->monthly_price) * 0.20;

    expect($reward->type)->toBe('commission')
        ->and($reward->status)->toBe('pending')
        ->and(floatval($reward->amount))->toBe($expectedAmount);
});

test('self referrals are prevented', function () {
    // Create referral code for user
    $referralCode = $this->referrer->getOrCreateReferralCode();

    // Create subscription for same user (self-referral attempt)
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->referrer->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Mock request with cookie
    request()->cookies->set('referral_code', $referralCode);

    // Process conversion (should fail)
    app(ProcessReferralConversion::class)->handle($this->referrer, $subscription);

    $referral = Referral::where('code', $referralCode)->first();

    // Should not create conversion
    expect($referral->conversions)->toBe(0)
        ->and($referral->rewards()->count())->toBe(0);
});

test('duplicate conversions are prevented', function () {
    // Create referral code
    $referralCode = $this->referrer->getOrCreateReferralCode();
    $referral = Referral::where('code', $referralCode)->first();

    // Mark as already converted
    $referral->markAsConverted($this->referred->id);

    // Create another subscription
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->referred->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Mock request with cookie
    request()->cookies->set('referral_code', $referralCode);

    // Try to process conversion again
    app(ProcessReferralConversion::class)->handle($this->referred, $subscription);

    // Should still only have 1 conversion
    expect($referral->fresh()->conversions)->toBe(1)
        ->and($referral->rewards()->count())->toBe(0); // No new rewards
});

test('inactive referrals are not tracked', function () {
    $referral = $this->referrer->referrals()->create([
        'code' => 'INACTIVE1',
        'status' => 'inactive',
    ]);

    expect($referral->isActive())->toBeFalse();

    // Create subscription
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->referred->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Mock request with inactive code
    request()->cookies->set('referral_code', 'INACTIVE1');

    // Process conversion
    app(ProcessReferralConversion::class)->handle($this->referred, $subscription);

    // Should not create conversion
    expect($referral->fresh()->conversions)->toBe(0);
});

test('yearly subscription rewards are calculated correctly', function () {
    // Create referral code
    $referralCode = $this->referrer->getOrCreateReferralCode();

    // Create yearly subscription
    $subscription = Subscription::create([
        'billable_type' => 'user',
        'billable_id' => $this->referred->id,
        'plan_id' => $this->plan->id,
        'vendor_slug' => 'stripe',
        'vendor_customer_id' => 'cus_'.uniqid(),
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'year',
        'status' => 'active',
        'seats' => 1,
    ]);

    // Mock request with cookie
    request()->cookies->set('referral_code', $referralCode);

    // Process conversion
    app(ProcessReferralConversion::class)->handle($this->referred, $subscription);

    $referral = Referral::where('code', $referralCode)->first();
    $reward = $referral->rewards()->first();

    // Check that commission is 20% of yearly plan price
    $expectedAmount = floatval($this->plan->yearly_price) * 0.20;

    expect(floatval($reward->amount))->toBe($expectedAmount);
});
