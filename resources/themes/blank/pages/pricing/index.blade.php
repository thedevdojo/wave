<?php
    use function Laravel\Folio\{middleware, name};
    name('pricing');
?>

<x-layouts.marketing>

    <x-container class="py-12">
        <x-marketing.pricing></x-marketing.pricing>

        <div class="flex flex-col w-full">
            @if(config('wave.paddle.env') == 'sandbox')
                <div class="mx-auto w-full max-w-6xl">
                    <div class="p-10 w-full rounded-xl border border-zinc-200 text-zinc-600">
                        <div class="flex items-center pb-4">
                            <div class="flex justify-center items-center mr-3 w-10 h-10 text-white rounded-md bg-zinc-900">
                                <x-phosphor-test-tube-duotone class="w-6 h-6" />
                            </div>
                            <div class="relative">
                                <h2 class="text-sm font-bold text-zinc-700">Testing in Sandbox Mode</h2>
                                <p class="text-xs text-zinc-600">Application billing is in sandbox mode, which means you can test the checkout process using the following credentials:</p>
                            </div>
                        </div>
                        <div class="pt-1 text-sm font-semibold text-zinc-500">
                            Credit Card Number: <span class="ml-2 font-mono text-black">4242 4242 4242 4242</span>
                        </div>
                        <div class="pt-1 text-sm font-semibold text-zinc-500">
                            Expiration Date: <span class="ml-2 font-mono text-black">Any future date</span>
                        </div>
                        <div class="pt-1 text-sm font-semibold text-zinc-500">
                            Security Code: <span class="ml-2 font-mono text-black">Any 3 digits</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-container>

</x-layouts.marketing>
