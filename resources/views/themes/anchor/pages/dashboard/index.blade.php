<?php
    use function Laravel\Folio\{middleware, name};
    name('dashboard');
    middleware('wave');
?>

<x-layouts.app>

	<x-app.container class="space-y-6">

        <x-app.alert>
             This is the user dashboard where users will manage settings and access features. <a href="#_" target="_blank" class="underline">View the docs</a> to learn more.
        </x-app.alert>


        <x-app.heading
                title="Dashboard"
                description="Welcome to an example applicaction dashboard. Find more resources below."
                size="lg"
                :border="false"
            />

        {{-- <div class="flex relative flex-col space-y-3">
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            <p class="text-gray-600">This is your application admin. Let's start crafting your next great idea.</p>
        </div> --}}

        <div class="flex space-x-5 w-full">
            <x-app.dashboard-card
				href="/docs"
				title="Documentation"
				description="Learn how to customize your app and make it shine!"
				link_text="View The Docs"
				image="/wave/img/docs.png"
			/>

			<x-app.dashboard-card
				href="/questions"
				title="Ask The Community"
				description="Share your progress and get help from other builders."
				link_text="Ask a Question"
				image="/wave/img/community.png"
			/>

			



        </div>

		<div class="flex space-x-5 w-full">
			<x-app.dashboard-card
				href="/repo"
				title="Github Repo"
				description="View the source code and submit a Pull Request"
				link_text="View on Github"
				image="/wave/img/laptop.png"
			/>

			<x-app.dashboard-card
				href="/repo"
				title="Resources"
				description="View resources that will help you build your SaaS"
				link_text="View Resources"
				image="/wave/img/globe.png"
			/>
		</div>
    </x-app.container>
	

</x-layouts.app>
