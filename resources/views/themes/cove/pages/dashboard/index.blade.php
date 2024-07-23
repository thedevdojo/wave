<?php
    use function Laravel\Folio\{name};
    name('wave.dashboard');
?>

<x-layouts.app>
    <div class="flex flex-col px-8 mx-auto my-6 max-w-7xl lg:flex-row xl:px-5">
	    <div class="flex overflow-hidden flex-col flex-1 justify-start mb-5 bg-white rounded-lg border lg:mr-3 lg:mb-0 border-zinc-150">
	        <div class="flex flex-wrap justify-between items-center p-5 bg-white border-b border-zinc-150 sm:flex-no-wrap">
				<div class="flex justify-center items-center mr-5 w-12 h-12 bg-blue-100 rounded-lg">
					<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
				</div>
				<div class="relative flex-1">
	                <h3 class="text-lg font-medium leading-6 text-zinc-700">
	                    Welcome to your Dashboard
	                </h3>
	                <p class="text-sm leading-5 text-zinc-500 mt">
	                    Learn More Below
	                </p>
				</div>

	        </div>
	        <div class="relative p-5">
	            <p class="text-base leading-loose text-zinc-500">This is your application <a href="{{ route('wave.dashboard') }}" class="text-blue-500 underline">dashboard</a>, you can customize this view inside of <code class="px-2 py-1 font-mono text-base font-medium rounded-md text-zinc-600 bg-zinc-100">{{ theme_folder('/dashboard/index.blade.php') }}</code><br><br> (Themes are located inside the <code>resources/views/themes</code> folder)</p>
				<span class="inline-flex mt-5 rounded-md shadow-sm">
	                <a href="{{ url('docs') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 bg-white rounded-md border transition duration-150 ease-in-out text-zinc-700 border-zinc-300 hover:text-zinc-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-zinc-800 active:bg-zinc-50">
	                    Read The Docs
	                </a>
				</span>
			</div>
		</div>
		<div class="flex overflow-hidden flex-col flex-1 justify-start bg-white rounded-lg border lg:ml-3 border-zinc-150">
	        <div class="flex flex-wrap justify-between items-center p-5 bg-white border-b border-zinc-150 sm:flex-no-wrap">
				<div class="flex justify-center items-center mr-5 w-12 h-12 bg-blue-100 rounded-lg">
					<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
				</div>
				<div class="relative flex-1">
	                <h3 class="text-lg font-medium leading-6 text-zinc-700">
						Learn more about Wave
	                </h3>
	                <p class="text-sm leading-5 text-zinc-500 mt">
						Are you more of a visual learner?
	                </p>
				</div>

	        </div>
	        <div class="relative p-5">
				<p class="text-base leading-loose text-zinc-500">Make sure to head on over to the Wave Video Tutorials to learn more how to use and customize Wave.<br><br>Click on the button below to checkout the video tutorials.</p>
				<span class="inline-flex mt-5 rounded-md shadow-sm">
	                <a href="https://devdojo.com/course/wave" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 bg-white rounded-md border transition duration-150 ease-in-out text-zinc-700 border-zinc-300 hover:text-zinc-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-zinc-800 active:bg-zinc-50">
						Watch The Videos
	                </a>
				</span>
			</div>
	    </div>

	</div>
</x-layouts.app>
