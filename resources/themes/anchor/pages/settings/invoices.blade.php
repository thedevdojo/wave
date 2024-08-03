<?php
    use function Laravel\Folio\{name};
    name('settings.invoices');
?>

@php
    $invoices = auth()->user()->invoices();
@endphp

<x-layouts.app>
        <div class="relative">
            <x-app.settings-layout
                title="Invoices"
                description="Your past plan invoices"
            >
                @empty($invoices)
                    <x-app.alert id="dashboard_alert">No invoices available.</x-app.alert>
                    <p class="mt-3">You do not have any past invoices. When you subscribe to a plan you'll see your past invoices here.</p>
                @else
                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-zinc-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-left uppercase bg-white text-zinc-500">Price</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-left uppercase bg-white text-zinc-500">Date of Invoice</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-right uppercase bg-white text-zinc-500">PDF Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr wire:key="invoice-{{ $invoice->id }}" class="@if($loop->index%2 == 0){{ 'bg-zinc-50' }}@else{{ 'bg-white' }}@endif">
                                        <td class="px-6 py-4 text-sm font-medium leading-5 text-left text-zinc-900 whitespace-no-wrap">${{ $invoice->total }}</td>
                                        <td class="px-6 py-4 text-sm font-medium leading-5 text-zinc-900 whitespace-no-wrap">{{ $invoice->created }}</td>
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
