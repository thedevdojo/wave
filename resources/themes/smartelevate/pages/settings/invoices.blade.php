<?php
    use function Laravel\Folio\{middleware, name};
    middleware('auth');
    name('settings.invoices');
?>

@php
    $invoices = auth()->user()->invoices();
@endphp

<x-layouts.app>
        <div class="relative dark:text-gray-400">
            <x-app.settings-layout
                title="Invoices"
                description="Your past plan invoices"
            >
                @empty($invoices)
                    <x-app.alert id="dashboard_alert">No invoices available.</x-app.alert>
                    <p class="mt-3">You do not have any past invoices. When you subscribe to a plan you'll see your past invoices here.</p>
                @else
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-zinc-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left uppercase bg-white text-zinc-500">Price</th>
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left uppercase bg-white text-zinc-500">Date of Invoice</th>
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-right uppercase bg-white text-zinc-500">PDF Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr wire:key="invoice-{{ $invoice->id }}" class="@if($loop->index%2 == 0){{ 'bg-zinc-50' }}@else{{ 'bg-white' }}@endif">
                                        <td class="px-6 py-4 text-sm font-medium leading-5 text-left whitespace-no-wrap text-zinc-900">${{ $invoice->total }}</td>
                                        <td class="px-6 py-4 text-sm font-medium leading-5 whitespace-no-wrap text-zinc-900">{{ $invoice->created }}</td>
                                        <td class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap">
                                            <a href="{{ $invoice->download }}" @if(config("wave.billing_provider") == 'stripe') target="_blank" @endif class="mr-2 text-indigo-600 hover:underline focus:outline-none">Download</a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endempty

            </x-app.settings-layout>
        </div>
</x-layouts.app>
