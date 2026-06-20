<div x-data="{ copied: false }" @copied.window="copied = true; setTimeout(() => copied = false, 2000)">
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Referral Program</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Share your referral link and earn rewards when friends subscribe.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total Clicks -->
            <div class="p-6 bg-white border rounded-lg border-zinc-200 dark:bg-zinc-800 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Clicks</p>
                        <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalClicks }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Conversions -->
            <div class="p-6 bg-white border rounded-lg border-zinc-200 dark:bg-zinc-800 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Conversions</p>
                        <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalConversions }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Earnings -->
            <div class="p-6 bg-white border rounded-lg border-zinc-200 dark:bg-zinc-800 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Pending</p>
                        <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">${{ number_format($pendingEarnings, 2) }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="p-6 bg-white border rounded-lg border-zinc-200 dark:bg-zinc-800 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Earned</p>
                        <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">${{ number_format($totalEarnings, 2) }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-zinc-100 dark:bg-zinc-700">
                        <svg class="w-6 h-6 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Link Card -->
        <div class="p-6 bg-white border rounded-lg border-zinc-200 dark:bg-zinc-800 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Your Referral Link</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Share this link with friends to earn 20% commission on their first subscription payment.
            </p>
            
            <div class="flex gap-3 items-center mt-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        readonly 
                        value="{{ $referralUrl }}"
                        class="block px-4 py-3 w-full text-sm rounded-lg border border-zinc-300 bg-zinc-50 dark:bg-zinc-900 dark:border-zinc-600 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                <button 
                    x-on:click="navigator.clipboard.writeText('{{ $referralUrl }}'); $wire.copyToClipboard()"
                    class="inline-flex gap-2 items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700"
                >
                    <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <svg x-show="copied" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span x-text="copied ? 'Copied!' : 'Copy'">Copy</span>
                </button>
            </div>

            <div class="mt-4">
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Your Referral Code:</p>
                <p class="mt-1 text-lg font-mono font-bold text-zinc-900 dark:text-white">{{ $referralCode }}</p>
            </div>
        </div>

        <!-- Recent Referrals -->
        @if(count($recentReferrals) > 0)
            <div class="p-6 bg-white border rounded-lg border-zinc-200 dark:bg-zinc-800 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Recent Conversions</h3>
                <div class="mt-4 overflow-hidden">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-900">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-zinc-500 dark:text-zinc-400">User</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-zinc-500 dark:text-zinc-400">Date</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right uppercase text-zinc-500 dark:text-zinc-400">Earnings</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-800 dark:divide-zinc-700">
                            @foreach($recentReferrals as $referral)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-zinc-900 dark:text-white">
                                        {{ $referral['user_name'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-zinc-500 dark:text-zinc-400">
                                        {{ $referral['converted_at'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap text-green-600 dark:text-green-400">
                                        ${{ number_format($referral['earnings'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
