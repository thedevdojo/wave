<form action="{{ route('wave.settings.profile.put') }}" method="POST" enctype="multipart/form-data">
	<div class="relative flex flex-col px-10 py-8 lg:flex-row">
		<div class="flex justify-start w-full mb-8 lg:w-3/12 xl:w-1/5 lg:m-b0">
			<div class="relative w-32 h-32 cursor-pointer group">
				<img id="preview" src="{{ Voyager::image(auth()->user()->avatar) . '?' . time() }}" class="w-32 h-32 rounded-full ">
				<div class="absolute inset-0 w-full h-full">
				    <input type="file" id="upload" class="absolute inset-0 z-20 w-full h-full opacity-0 cursor-pointer group">
				    <input type="hidden" id="uploadBase64" name="avatar">
				    <button class="absolute bottom-0 z-10 flex items-center justify-center w-10 h-10 mb-2 -ml-5 bg-black bg-opacity-75 rounded-full opacity-75 group-hover:opacity-100 left-1/2">
						<svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
					</button>
				</div>
			</div>
		</div>
		<div class="w-full lg:w-9/12 xl:w-4/5">
			<div>
				<label for="name" class="block text-sm font-medium leading-5 text-gray-700">Name</label>
				<div class="mt-1 rounded-md shadow-sm">
					<input id="name" type="text" name="name" placeholder="Name" value="{{ Auth::user()->name }}" required class="w-full form-input">
				</div>
			</div>

			<div class="mt-5">
				<label for="email" class="block text-sm font-medium leading-5 text-gray-700">Email Address</label>
				<div class="mt-1 rounded-md shadow-sm">
					<input id="email" type="text" name="email" placeholder="Email Address" value="{{ Auth::user()->email }}" required class="w-full form-input">
				</div>
			</div>

			<div class="mt-5">
				<label for="about" class="block text-sm font-medium leading-5 text-gray-700">About</label>
				<div class="mt-1 rounded-md">
					{!! profile_field('text_area', 'about') !!}
				</div>
			</div>

			<div class="flex justify-end w-full">
				<button class="flex self-end justify-center w-auto px-4 py-2 mt-5 text-sm font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave active:bg-wave-700" dusk="update-profile-button">Save</button>
			</div>
		</div>
	</div>

	{{ csrf_field() }}



</form>

<div id="uploadModal" x-data x-init="$watch('$store.uploadModal.open', value => {
      if (value === true) { document.body.classList.add('overflow-hidden') }
      else { document.body.classList.remove('overflow-hidden'); clearInputField(); }
    });" x-show="$store.uploadModal.open" class="fixed inset-0 z-10 z-30 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="$store.uploadModal.open" @click="$store.uploadModal.open = false;" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" x-cloak>
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
        <div x-show="$store.uploadModal.open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline" x-cloak>
            <div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-headline">
						Position and resize your photo
                    </h3>
                    <div class="mt-2">
						<div id="upload-crop-container" class="relative flex items-center justify-center h-56 mt-5">
							<div id="uploadLoading" class="flex items-center justify-center w-full h-full">
								<svg class="w-5 h-5 mr-3 -ml-1 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
					<button @click="$store.uploadModal.open = false;" class="inline-flex justify-center w-full px-4 py-2 mr-2 text-base font-medium leading-6 text-gray-700 transition duration-150 ease-in-out bg-white border border-transparent border-gray-300 rounded-md shadow-sm hover:text-gray-500 active:text-gray-800 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue sm:text-sm sm:leading-5" type="button">Cancel</button>
            		<button @click="$store.uploadModal.open = false; window.applyImageCrop()" class="inline-flex justify-center w-full px-4 py-2 ml-2 text-base font-medium leading-6 text-white transition duration-150 ease-in-out border border-transparent rounded-md shadow-sm bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave sm:text-sm sm:leading-5" id="apply-crop" type="button">Apply</button>
                </span>
            </div>
        </div>
    </div>
</div>
