<?php

namespace Wave\Http\Livewire\Referrals;

use Livewire\Component;

class Dashboard extends Component
{
    public $referralCode;

    public $referralUrl;

    public $totalClicks = 0;

    public $totalConversions = 0;

    public $pendingEarnings = 0;

    public $paidEarnings = 0;

    public $totalEarnings = 0;

    public $recentReferrals = [];

    public function mount()
    {
        $this->loadReferralData();
    }

    public function loadReferralData()
    {
        $user = auth()->user();

        // Get or create referral code
        $this->referralCode = $user->getOrCreateReferralCode();
        $this->referralUrl = url('/?ref='.$this->referralCode);

        // Get referral statistics
        $referrals = $user->referrals()->where('status', 'active')->get();

        $this->totalClicks = $referrals->sum('clicks');
        $this->totalConversions = $referrals->sum('conversions');

        // Get earnings
        $this->pendingEarnings = $user->pendingReferralEarnings();
        $this->paidEarnings = $user->paidReferralEarnings();
        $this->totalEarnings = $user->totalReferralEarnings();

        // Get recent conversions with user data
        $this->recentReferrals = $referrals
            ->filter(fn ($ref) => $ref->referred_user_id !== null)
            ->map(function ($referral) {
                return [
                    'user_name' => $referral->referredUser->name ?? 'Unknown',
                    'converted_at' => $referral->converted_at->diffForHumans(),
                    'earnings' => $referral->totalEarnings(),
                ];
            })
            ->take(10)
            ->values()
            ->toArray();
    }

    public function copyToClipboard()
    {
        $this->dispatch('copied');
    }

    public function render()
    {
        return view('wave::livewire.referrals.dashboard');
    }
}
