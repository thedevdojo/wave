<?php

namespace Wave\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Wave\Plan;
use Wave\Subscription;

class WaveStats extends Command
{
    protected $signature = 'wave:stats
                            {--period=30 : Number of days to analyze for growth metrics}
                            {--json : Output in JSON format}';

    protected $description = 'Display Wave application statistics including MRR, subscriptions, users, and growth metrics';

    public function handle(): int
    {
        $period = (int) $this->option('period');
        $json = $this->option('json');

        $stats = $this->gatherStats($period);

        if ($json) {
            $this->line(json_encode($stats, JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        $this->displayStats($stats);

        return self::SUCCESS;
    }

    protected function gatherStats(int $period): array
    {
        $now = Carbon::now();
        $periodStart = $now->copy()->subDays($period);

        // User Statistics
        $totalUsers = User::count();
        $newUsers = User::where('created_at', '>=', $periodStart)->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();

        // Trials are set on the user, and end once they subscribe
        $usersOnTrial = User::where('trial_ends_at', '>', $now)
            ->whereDoesntHave('subscriptions', fn ($query) => $query->where('status', 'active'))
            ->count();

        // Subscription Statistics
        $activeSubscriptions = Subscription::where('status', 'active')->count();

        // Cancelled, but still running until the end of the billing period
        $cancelledSubscriptions = Subscription::where('status', 'active')
            ->where('ends_at', '>', $now)
            ->count();

        $newSubscriptions = Subscription::where('created_at', '>=', $periodStart)->count();

        // Revenue Calculations (MRR & ARR)
        $mrr = $this->calculateMRR();
        $arr = $mrr * 12;

        // Plan Breakdown
        $planBreakdown = $this->getPlanBreakdown();

        // Growth Metrics
        $previousPeriodStart = $periodStart->copy()->subDays($period);
        $previousNewUsers = User::where('created_at', '>=', $previousPeriodStart)
            ->where('created_at', '<', $periodStart)
            ->count();
        $previousNewSubs = Subscription::where('created_at', '>=', $previousPeriodStart)
            ->where('created_at', '<', $periodStart)
            ->count();

        $userGrowthRate = $this->growthRate($newUsers, $previousNewUsers);
        $subGrowthRate = $this->growthRate($newSubscriptions, $previousNewSubs);

        // Churn: of the subscriptions active when the period started, the share cancelled since.
        // There's no cancelled_at column, so updated_at stands in for the cancellation date.
        $startedBefore = Subscription::where('created_at', '<', $periodStart);
        $churnedSubs = (clone $startedBefore)->where('status', 'cancelled')
            ->where('updated_at', '>=', $periodStart)
            ->count();
        $activeAtStart = (clone $startedBefore)->where('status', 'active')->count() + $churnedSubs;

        $churnRate = $activeAtStart > 0
            ? round(($churnedSubs / $activeAtStart) * 100, 2)
            : 0;

        return [
            'users' => [
                'total' => $totalUsers,
                'new' => $newUsers,
                'verified' => $verifiedUsers,
                'on_trial' => $usersOnTrial,
                'growth_rate' => $userGrowthRate,
            ],
            'subscriptions' => [
                'active' => $activeSubscriptions,
                'cancelled' => $cancelledSubscriptions,
                'new' => $newSubscriptions,
                'growth_rate' => $subGrowthRate,
                'churn_rate' => $churnRate,
            ],
            'revenue' => [
                'mrr' => $mrr,
                'arr' => $arr,
                'currency' => config('app.currency', 'USD'),
            ],
            'plans' => $planBreakdown,
            'period_days' => $period,
        ];
    }

    // Null when there's nothing to compare with, rather than a misleading 0%
    protected function growthRate(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    protected function calculateMRR(): float
    {
        $monthlyRevenue = 0;

        $activeSubscriptions = Subscription::where('status', 'active')->with('plan')->get();

        foreach ($activeSubscriptions as $subscription) {
            if (! $subscription->plan) {
                continue;
            }

            if ($subscription->cycle === 'month') {
                $monthlyRevenue += (float) $subscription->plan->monthly_price;
            } elseif ($subscription->cycle === 'year') {
                $monthlyRevenue += (float) $subscription->plan->yearly_price / 12;
            }
        }

        return round($monthlyRevenue, 2);
    }

    protected function getPlanBreakdown(): array
    {
        $plans = Plan::withCount(['subscriptions' => function ($query) {
            $query->where('status', 'active');
        }])->get();

        return $plans->map(function ($plan) {
            return [
                'name' => $plan->name,
                'active_subscriptions' => $plan->subscriptions_count,
            ];
        })->toArray();
    }

    protected function displayStats(array $stats): void
    {
        $this->newLine();
        $this->components->info('Wave Statistics');
        $this->newLine();

        // Users Section
        $this->components->twoColumnDetail('<fg=cyan>👥 Users</>');
        $this->components->twoColumnDetail('Total Users', number_format($stats['users']['total']));
        $this->components->twoColumnDetail('New Users (Last '.$stats['period_days'].' days)', number_format($stats['users']['new']));
        $this->components->twoColumnDetail('Verified Users', number_format($stats['users']['verified']));
        $this->components->twoColumnDetail('Users on Trial', number_format($stats['users']['on_trial']));
        $this->components->twoColumnDetail('User Growth Rate', $this->formatGrowth($stats['users']['growth_rate']));

        $this->newLine();

        // Subscriptions Section
        $this->components->twoColumnDetail('<fg=cyan>💳 Subscriptions</>');
        $this->components->twoColumnDetail('Active Subscriptions', number_format($stats['subscriptions']['active']));
        $this->components->twoColumnDetail('Cancelled (Active)', number_format($stats['subscriptions']['cancelled']));
        $this->components->twoColumnDetail('New Subscriptions (Last '.$stats['period_days'].' days)', number_format($stats['subscriptions']['new']));
        $this->components->twoColumnDetail('Subscription Growth Rate', $this->formatGrowth($stats['subscriptions']['growth_rate']));
        $this->components->twoColumnDetail('Churn Rate', '<fg='.($stats['subscriptions']['churn_rate'] > 5 ? 'red' : 'green').'>'.$stats['subscriptions']['churn_rate'].'%</>');

        $this->newLine();

        // Revenue Section
        $this->components->twoColumnDetail('<fg=cyan>💰 Revenue</>');
        $this->components->twoColumnDetail('MRR (Monthly Recurring Revenue)', '$'.number_format($stats['revenue']['mrr'], 2));
        $this->components->twoColumnDetail('ARR (Annual Recurring Revenue)', '$'.number_format($stats['revenue']['arr'], 2));

        // Plans Breakdown
        if (! empty($stats['plans'])) {
            $this->newLine();
            $this->components->twoColumnDetail('<fg=cyan>📊 Plan Breakdown</>');
            foreach ($stats['plans'] as $plan) {
                $this->components->twoColumnDetail($plan['name'], number_format($plan['active_subscriptions']).' active');
            }
        }

        $this->newLine();
    }

    protected function formatGrowth(?float $rate): string
    {
        if ($rate === null) {
            return '<fg=gray>n/a</>';
        }

        if ($rate > 0) {
            return '<fg=green>↑ '.$rate.'%</>';
        } elseif ($rate < 0) {
            return '<fg=red>↓ '.abs($rate).'%</>';
        }

        return '<fg=yellow>→ 0%</>';
    }
}
