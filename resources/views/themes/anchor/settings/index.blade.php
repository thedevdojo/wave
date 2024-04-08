<x-layouts.app>

	<x-container class="flex">

		<!-- Left Settings Menu -->
		<div class="mr-6 space-y-5 w-16 md:w-1/4">

			<div class="space-y-1">
				<h3 class="hidden pt-2 pb-3 text-xs font-semibold tracking-wider leading-4 uppercase text-zinc-500 md:block">Settings</h3>
				<x-settings-sidebar-link :href="route('wave.settings', 'profile')" icon="phosphor-user-circle">Profile</x-settings-sidebar-link>
				<x-settings-sidebar-link :href="route('wave.settings', 'security')" icon="phosphor-lock">Security</x-settings-sidebar-link>
				<x-settings-sidebar-link :href="route('wave.settings', 'api')" icon="phosphor-code">API Keys</x-settings-sidebar-link>
			</div>

			<x-card>
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
			</x-card>

		</div>
		<!-- End Settings Menu -->

		<x-card class="w-full md:w-3/4">
			<div class="flex flex-wrap justify-between items-center border-b border-zinc-200 sm:flex-no-wrap">
	            <div class="relative p-6">
	                <h3 class="flex text-lg font-medium leading-6 text-zinc-600">
						<svg class="mr-3 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
						@if(isset($section_title)){{ $section_title }}@else{{ Auth::user()->name . '\'s' }} {{ ucwords(str_replace('-', ' ', Request::segment(2)) ?? 'profile') . ' Settings' }}@endif
	                </h3>
	            </div>
	        </div>
			<div class="uk-card-body">
				@if($section == 'profile')
				    @include('theme::settings.partials.' . $section)
				@else
				    @livewire('wave.settings.' . $section)
				@endif
			</div>
		</x-card>
	</x-container>

	<x-slot:javascript>

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
						Alpine.store('uploadModal').openModal();
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


