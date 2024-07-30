<div>
    <div class="p-8">

        @subscriber
            @php
                $subscription = new \Wave\Http\Controllers\SubscriptionController;
                $invoices = $subscription->invoices( auth()->user() );
            @endphp

            @if(isset($invoices->success) && $invoices->success == true)

                <table class="overflow-hidden min-w-full rounded-lg divide-y divide-zinc-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-left uppercase text-zinc-500 bg-zinc-100">
                                Date of Invoice
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-right uppercase text-zinc-500 bg-zinc-100">
                                Price
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-right uppercase text-zinc-500 bg-zinc-100">
                                Receipt Link
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices->response as $invoice)
                            <tr class="@if($loop->index%2 == 0){{ 'bg-zinc-50' }}@else{{ 'bg-zinc-100' }}@endif">
                                <td class="px-6 py-4 text-sm font-medium leading-5 text-zinc-900 whitespace-no-wrap">
                                    {{ Carbon\Carbon::parse($invoice->payout_date)->toFormattedDateString() }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium leading-5 text-right text-zinc-900 whitespace-no-wrap">
                                    ${{ $invoice->amount }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap">
                                    <a href="{{ $invoice->receipt_url }}" target="_blank" class="mr-2 text-indigo-600 hover:underline focus:outline-none">
                                        Download
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else
                <p>Sorry, there seems to be an issue retrieving your invoices or you may not have any invoices yet.</p>
            @endif

        @notsubscriber
            <p class="text-zinc-600">When you subscribe to a plan, this is where you will be able to download your invoices.</p>
            <a href="{{ route('wave.settings', 'plans') }}" class="inline-flex justify-center self-start px-4 py-2 mt-5 w-auto text-sm font-medium text-white bg-blue-600 rounded-md border border-transparent transition duration-150 ease-in-out hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave active:bg-blue-700">View Plans</a>
        @endsubscriber

    </div>
</div>
