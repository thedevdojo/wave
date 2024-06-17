<x-layouts.app>

	{{-- <div class="flex w-full max-w-full"> --}}

	

	{{-- <x-card>
		<h3 class="hidden px-6 pb-3 text-xs font-semibold tracking-wider leading-4 uppercase text-zinc-500 md:block">Billing</h3>

		<a href="{{ route('wave.settings', 'plans') }}" class="block relative w-full flex items-center px-6 py-3 text-sm font-medium leading-5 @if(Request::is('settings/plans')){{ 'text-zinc-900' }}@else{{ 'text-zinc-600' }}@endif transition duration-150 ease-in-out rounded-md group hover:text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:text-zinc-900 focus:bg-zinc-50">
			<svg class="flex-shrink-0 w-5 h-5 mr-3 -ml-1 @if(Request::is('settings/plans')){{ 'text-zinc-500' }}@else{{ 'text-zinc-400' }}@endif transition duration-150 ease-in-out group-hover:text-zinc-500 group-focus:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
			<span class="hidden truncate md:inline-block">Plans</span>
			<span class="absolute left-0 block w-1 transition-all duration-300 ease-out rounded-full @if(Request::is('settings/plans')){{ 'bg-blue-500 h-full top-0' }}@else{{ 'top-1/2 bg-zinc-300 group-hover:top-0 h-0 group-hover:h-full' }}@endif "></span>
		</a>
		<a href="{{ route('wave.settings', 'subscription') }}" class="block relative w-full flex items-center px-6 py-3 text-sm font-medium leading-5 @if(Request::is('settings/payment-information')){{ 'text-zinc-900' }}@else{{ 'text-zinc-600' }}@endif transition duration-150 ease-in-out rounded-md group hover:text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:text-zinc-900 focus:bg-zinc-50">
			<svg class="flex-shrink-0 w-5 h-5 mr-3 -ml-1 @if(Request::is('settings/subscription')){{ 'text-zinc-500' }}@else{{ 'text-zinc-400' }}@endif transition duration-150 ease-in-out group-hover:text-zinc-500 group-focus:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
			<span class="hidden truncate md:inline-block">Subscription</span>
			<span class="absolute left-0 block w-1 transition-all duration-300 ease-out rounded-full @if(Request::is('settings/subscription')){{ 'bg-blue-500 h-full top-0' }}@else{{ 'top-1/2 bg-zinc-300 group-hover:top-0 h-0 group-hover:h-full' }}@endif "></span>
		</a>
		<a href="{{ route('wave.settings', 'invoices') }}" class="block relative w-full flex items-center px-6 py-3 text-sm font-medium leading-5 @if(Request::is('settings/invoices')){{ 'text-zinc-900' }}@else{{ 'text-zinc-600' }}@endif transition duration-150 ease-in-out rounded-md group hover:text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:text-zinc-900 focus:bg-zinc-50">
			<svg class="flex-shrink-0 w-5 h-5 mr-3 -ml-1 @if(Request::is('settings/invoices')){{ 'text-zinc-500' }}@else{{ 'text-zinc-400' }}@endif transition duration-150 ease-in-out group-hover:text-zinc-500 group-focus:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
			<span class="hidden truncate md:inline-block">Invoices</span>
			<span class="absolute left-0 block w-1 transition-all duration-300 ease-out rounded-full @if(Request::is('settings/invoices')){{ 'bg-blue-500 h-full top-0' }}@else{{ 'top-1/2 bg-zinc-300 group-hover:top-0 h-0 group-hover:h-full' }}@endif "></span>
		</a>
	</x-card> --}}

<!-- End Settings Menu -->

	<x-card class="flex flex-col mx-auto my-10 w-full max-w-5xl">
		<div class="flex flex-wrap justify-between items-center pb-3 border-b border-zinc-200 dark:border-zinc-800 sm:flex-no-wrap">
				<div class="relative p-2">
						<div class="space-y-0.5">
							<h2 class="text-xl font-semibold tracking-tight dark:text-zinc-100">Settings</h2>
							<p class="text-sm text-zinc-500 dark:text-zinc-400">Manage your account settings and set e-mail preferences.</p>
						</div>

						{{-- @if(isset($section_title)){{ $section_title }}@else{{ Auth::user()->name . '\'s' }} {{ ucwords(str_replace('-', ' ', Request::segment(2)) ?? 'profile') . ' Settings' }}@endif --}}
					
				</div>
			</div>
		<div class="flex pt-3">
			<aside class="pt-4 lg:w-1/4">
				<nav class="flex space-x-2 lg:flex-col lg:space-x-0 lg:space-y-1">
					<div class="px-2.5 pb-1.5 text-xs font-semibold leading-6 text-zinc-500">Settings</div>
					<x-settings-sidebar-link :href="route('wave.settings', 'profile')" icon="phosphor-user-circle-duotone">Profile</x-settings-sidebar-link>
					<x-settings-sidebar-link :href="route('wave.settings', 'security')" icon="phosphor-lock-duotone">Security</x-settings-sidebar-link>
					<x-settings-sidebar-link :href="route('wave.settings', 'api')" icon="phosphor-code-duotone">API Keys</x-settings-sidebar-link>
					

					<div class="px-2.5 pt-3.5 pb-1.5 text-xs font-semibold leading-6 text-zinc-500">Billing</div>
					<x-settings-sidebar-link :href="route('wave.settings', 'plans')" icon="phosphor-storefront-duotone">Plans</x-settings-sidebar-link>
					<x-settings-sidebar-link :href="route('wave.settings', 'subscription')" icon="phosphor-credit-card-duotone">Subscription</x-settings-sidebar-link>
					<x-settings-sidebar-link :href="route('wave.settings', 'invoices')" icon="phosphor-invoice-duotone">Invoices</x-settings-sidebar-link>
				</nav>
			</aside>

			<div class="lg:w-full">
				@if($section == 'profile')
					@include('theme::settings.partials.' . $section)
				@else
					@livewire('wave.settings.' . $section)
				@endif
			</div>
		</div>
	</x-card>

	<x-slot:javascript>

		<div class="z-[99]">
			<x-filament::modal id="profile-avatar-crop">
				<div>
					<div class="mt-3 text-center sm:mt-5">
						<h3 class="text-lg font-medium leading-6 text-zinc-900" id="modal-headline">
							Position and resize your photo
						</h3>
						<div class="mt-2">
							<div id="upload-crop-container" class="flex relative justify-center items-center mt-5 h-56">
								<div id="uploadLoading" class="flex justify-center items-center w-full h-full">
									<svg class="mr-3 -ml-1 w-5 h-5 animate-spin text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
										<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
										<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
									</svg>
								</div>
								<div id="upload-crop"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="mt-5 sm:mt-6">
					<span class="flex w-full rounded-md shadow-sm">
						<button @click="window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'profile-avatar-crop' }}));" class="inline-flex justify-center px-4 py-2 mr-2 w-full text-base font-medium leading-6 bg-white rounded-md border border-transparent shadow-sm transition duration-150 ease-in-out text-zinc-700 border-zinc-300 hover:text-zinc-500 active:text-zinc-800 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue sm:text-sm sm:leading-5" type="button">Cancel</button>
						<button @click="window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'profile-avatar-crop' }})); window.applyImageCrop()" class="inline-flex justify-center px-4 py-2 ml-2 w-full text-base font-medium leading-6 text-white bg-blue-600 rounded-md border border-transparent shadow-sm transition duration-150 ease-in-out hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave sm:text-sm sm:leading-5" id="apply-crop" type="button">Apply</button>
					</span>
				</div>
			</x-filament::modal>
		</div>

		<style>
			#upload-crop-container .croppie-container .cr-resizer, #upload-crop-container .croppie-container .cr-viewport{
				box-shadow: 0 0 2000px 2000px rgba(255,255,255,1) !important;
				border: 0px !important;
			}
			.croppie-container .cr-boundary {
				border-radius: 50% !important;
				overflow: hidden;
			}
			.croppie-container .cr-slider-wrap{
				margin-bottom: 0px !important;
			}
			.croppie-container{
				height:auto !important;
			}
		</style>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
		<script>

				let uploadCropEl = document.getElementById('upload-crop');
				let uploadLoading = document.getElementById('uploadLoading');
				let fileTypes = ['jpg', 'jpeg', 'png'];

				function readFile() {
					input = document.getElementById('upload');
					if (input.files && input.files[0]) {
						let reader = new FileReader();

						let fileType = input.files[0].name.split('.').pop().toLowerCase();
						if (fileTypes.indexOf(fileType) < 0) {
							alert('Invalid file type. Please select a JPG or PNG file.');
							return false;
						}
						reader.onload = function (e) {
							//$('.upload-demo').addClass('ready');
							uploadCrop.bind({
								url: e.target.result,
								orientation: 4
							}).then(function(){
								//uploadCrop.setZoom(0);
							});
						}
						reader.readAsDataURL(input.files[0]);
					}
					else {
						alert("Sorry - you're browser doesn't support the FileReader API");
					}
				}

				if(document.getElementById('upload')){
					document.getElementById('upload').addEventListener('change', function () {
						window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'profile-avatar-crop' }}));
						uploadCropEl.classList.add('hidden');
						uploadLoading.classList.remove('hidden');
						setTimeout(function(){
							uploadLoading.classList.add('hidden');
							uploadCropEl.classList.remove('hidden');

							if(typeof(uploadCrop) != "undefined"){
								uploadCrop.destroy();
							}
							uploadCrop = new Croppie(uploadCropEl, {
								viewport: { width: 190, height: 190, type: 'square' },
								boundary: { width: 190, height: 190 },
								enableExif: true,
							});

							readFile();
						}, 800);
					});
				}

				function clearInputField(){
					document.getElementById('upload').value = '';
				}

				function applyImageCrop(){
					let fileType = input.files[0].name.split('.').pop().toLowerCase();
					if (fileTypes.indexOf(fileType) < 0) {
						alert('Invalid file type. Please select a JPG or PNG file.');
						return false;
					}
					uploadCrop.result({type:'base64',size:'original',format:'png',quality:1}).then(function(base64) {
						document.getElementById('preview').src = base64;
						document.getElementById('uploadBase64').value = base64;
					});
				}

		</script>
	</x-slot>

</x-layouts.app>


