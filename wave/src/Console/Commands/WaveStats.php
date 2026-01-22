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
        $verifiedUsers = User::where('verified', 1)->count();

        // Subscription Statistics
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $trialSubscriptions = Subscription::where('status', 'active')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', $now)
            ->count();
        $cancelledSubscriptions = Subscription::whereNotNull('ends_at')
            ->where('ends_at', '>', $now)
            ->count();

        $newSubscriptions = Subscription::where('status', 'active')
            ->where('created_at', '>=', $periodStart)
            ->count();

        // Revenue Calculations (MRR & ARR)
        $mrr = $this->calculateMRR();
        $arr = $mrr * 12;

        // Plan Breakdown
        $planBreakdown = $this->getPlanBreakdown();

        // Growth Metrics
        $previousPeriodStart = $periodStart->copy()->subDays($period);
        $previousNewUsers = User::whereBetween('created_at', [$previousPeriodStart, $periodStart])->count();
        $previousNewSubs = Subscription::where('status', 'active')
            ->whereBetween('created_at', [$previousPeriodStart, $periodStart])
            ->count();

        $userGrowthRate = $previousNewUsers > 0
            ? round((($newUsers - $previousNewUsers) / $previousNewUsers) * 100, 2)
            : 0;

        $subGrowthRate = $previousNewSubs > 0
            ? round((($newSubscriptions - $previousNewSubs) / $previousNewSubs) * 100, 2)
            : 0;

        // Churn Rate (subscriptions that ended in the period / active subscriptions at start)
        $churnedSubs = Subscription::where('status', '!=', 'active')
            ->whereBetween('updated_at', [$periodStart, $now])
            ->count();

        $churnRate = $activeSubscriptions > 0
            ? round(($churnedSubs / $activeSubscriptions) * 100, 2)
            : 0;

        return [
            'users' => [
                'total' => $totalUsers,
                'new' => $newUsers,
                'verified' => $verifiedUsers,
                'growth_rate' => $userGrowthRate,
            ],
            'subscriptions' => [
                'active' => $activeSubscriptions,
                'trial' => $trialSubscriptions,
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
        $this->components->twoColumnDetail('User Growth Rate', $this->formatGrowth($stats['users']['growth_rate']));

        $this->newLine();

        // Subscriptions Section
        $this->components->twoColumnDetail('<fg=cyan>💳 Subscriptions</>');
        $this->components->twoColumnDetail('Active Subscriptions', number_format($stats['subscriptions']['active']));
        $this->components->twoColumnDetail('Trial Subscriptions', number_format($stats['subscriptions']['trial']));
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

    protected function formatGrowth(float $rate): string
    {
        if ($rate > 0) {
            return '<fg=green>↑ '.$rate.'%</>';
        } elseif ($rate < 0) {
            return '<fg=red>↓ '.abs($rate).'%</>';
        }

        return '<fg=yellow>→ 0%</>';
    }
}
