<?php
    use function Laravel\Folio\{name};
    name('home');
?>

<x-layouts.marketing
    :seo="[
        'title'         => 'Supapost - X (Twitter) AI Post Generator',
        'description'   => 'Boost engagement, visibility and streamline your social media with high impact AI generated X (Twitter) posts.',
        'image'         => url('/og_image.png'),
        'type'          => 'website'
    ]"
>
        
        <x-marketing.sections.hero />
        
        <x-container class="py-12 border-t sm:py-24 border-zinc-200">
            <x-marketing.sections.features />
        </x-container>

        <x-container class="py-12 border-t sm:py-24 border-zinc-200">
            <x-marketing.sections.testimonials />
        </x-container>

        <x-container class="py-12 border-t sm:py-24 border-zinc-200">
            <section class="w-full">
                <x-marketing.elements.heading level="h2" title="Frequently Asked Questions" description="Get answers to common questions about Supapost and how it can help your social media strategy." />
                
                <div class="max-w-3xl mx-auto mt-12">
                    <div class="space-y-8">
                        <div class="border-b border-zinc-200 pb-6">
                            <h3 class="text-lg font-medium text-zinc-900">What is Supapost?</h3>
                            <p class="mt-2 text-zinc-500">Supapost is an AI X (Twitter) post generator to help you create content consistently and on brand voice.</p>
                        </div>
                        
                        <div class="border-b border-zinc-200 pb-6">
                            <h3 class="text-lg font-medium text-zinc-900">How does Supapost work?</h3>
                            <p class="mt-2 text-zinc-500">Supapost uses AI to create posts in your brand voice. It learns from your existing content and audience interactions to create relevant posts.</p>
                        </div>
                        
                        <div class="border-b border-zinc-200 pb-6">
                            <h3 class="text-lg font-medium text-zinc-900">Is there a browser extension available?</h3>
                            <p class="mt-2 text-zinc-500">Yes, Supapost has a Chrome extension to create posts directly on X (Twitter) without leaving the platform.</p>
                        </div>
                        
                        <div class="border-b border-zinc-200 pb-6">
                            <h3 class="text-lg font-medium text-zinc-900">How can Supapost help improve my social media presence?</h3>
                            <p class="mt-2 text-zinc-500">Supapost increases your visibility on X (Twitter) by creating interactive content, saves time, maintains brand voice and adapts to real-time audience engagement.</p>
                        </div>
                        
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-zinc-900">How can I get early access to Supapost?</h3>
                            <p class="mt-2 text-zinc-500">You can sign up for early access by clicking the "Get Early Access" button on our homepage. Early subscribers get exclusive access to how-to guides, engagement tips, and special deals.</p>
                        </div>
                    </div>
                </div>
            </section>
        </x-container>

        <x-container class="py-12 border-t sm:py-24 border-zinc-200">
            <section class="w-full">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">Ready to transform your X (Twitter) strategy?</h2>
                    <p class="mt-4 text-lg text-zinc-500">Sign up for early access and be among the first to try out our product. Get exclusive how-to guides, engagement tips and special deals available only to our early subscribers.</p>
                    <div class="mt-8 flex justify-center">
                        <a href="{{ route('register') }}" class="flex items-center justify-center px-8 py-3 text-base font-medium text-white duration-200 bg-zinc-900 rounded-lg hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 md:py-4 md:text-lg md:px-10">
                            Get Early Access
                        </a>
                    </div>
                </div>
            </section>
        </x-container>

</x-layouts.marketing>
