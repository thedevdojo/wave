<?php

    use function Laravel\Folio\{middleware, name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
	use Livewire\Volt\Component;
	use Wave\Traits\HasDynamicFields;
    use Wave\ApiKey;
    
	middleware('auth');
    name('settings.profile');

	new class extends Component implements HasForms
	{
        use InteractsWithForms, HasDynamicFields;
        
        public ?array $data = [];
		public ?string $avatar = null;

		public function mount(): void
        {
            $this->form->fill();
        }

       public function form(Form $form): Form
        {
            return $form
                ->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required()
						->rules('required|string')
						->default(auth()->user()->name),
					\Filament\Forms\Components\TextInput::make('email')
                        ->label('Email Address')
                        ->required()
						->rules('sometimes|required|email|unique:users,email,' . auth()->user()->id)
						->default(auth()->user()->email),
					...($this->dynamicFields( config('profile.fields') ))
                ])
                ->statePath('data');
        }

		public function save()
		{
			$this->validate([
				'avatar' => 'sometimes|nullable|imageable',
			]);

			$state = $this->form->getState();
            $this->validate();
			
			if($this->avatar != null){
				$this->saveNewUserAvatar();
			}

			$this->saveFormFields($state);

			Notification::make()
                ->title('Successfully saved your profile settings')
                ->success()
                ->send();
		}

		private function saveNewUserAvatar(){
			$path = 'avatars/' . auth()->user()->username . '.png';
			$image = \Intervention\Image\ImageManagerStatic::make($this->avatar)->resize(800, 800);
			Storage::disk('public')->put($path, $image->encode());
			auth()->user()->avatar = $path;
			auth()->user()->save();
			// This will update/refresh the avatar in the sidebar
			$this->js('window.dispatchEvent(new CustomEvent("refresh-avatar"));');
		}

		private function saveFormFields($state){
			auth()->user()->name = $state['name'];
			auth()->user()->email = $state['email'];
			auth()->user()->save();
			$fieldsToSave = config('profile.fields');
			$this->saveDynamicFields($fieldsToSave);
		}

	}
?>

<x-layouts.app>

    <x-app.settings-layout
        title="Settings"
        description="Manage your account avatar, name, email, and more.">
        
		@volt('settings.profile')
		<div x-data="{
				uploadCropEl: null,
				uploadLoading: null,
				fileTypes: null,
				avatar: @entangle('avatar'),
				readFile() {
					input = document.getElementById('upload');
					if (input.files && input.files[0]) {
						let reader = new FileReader();

						let fileType = input.files[0].name.split('.').pop().toLowerCase();
						if (this.fileTypes.indexOf(fileType) < 0) {
							alert('Invalid file type. Please select a JPG or PNG file.');
							return false;
						}
						reader.onload = function (e) {
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
						alert('Sorry - you\'re browser doesn\'t support the FileReader API');
					}
				},
				applyImageCrop(){
					let fileType = input.files[0].name.split('.').pop().toLowerCase();
					if (this.fileTypes.indexOf(fileType) < 0) {
						alert('Invalid file type. Please select a JPG or PNG file.');
						return false;
					}
					let that = this;
					uploadCrop.result({type:'base64',size:'original',format:'png',quality:1}).then(function(base64) {
						that.avatar = base64;
						document.getElementById('preview').src = that.avatar;
					});
					
				}
			}" 
		x-init="
			uploadCropEl = document.getElementById('upload-crop');
			uploadLoading = document.getElementById('uploadLoading');
			fileTypes = ['jpg', 'jpeg', 'png'];

			if(document.getElementById('upload')){
				document.getElementById('upload').addEventListener('change', function () {
					window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'profile-avatar-crop' }}));
					uploadCropEl.classList.add('hidden');
					uploadLoading.classList.remove('hidden');
					setTimeout(function(){
						uploadLoading.classList.add('hidden');
						uploadCropEl.classList.remove('hidden');

						if(typeof(uploadCrop) != 'undefined'){
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
		"
		class="relative w-full">
			<form wire:submit="save" class="w-full">
				<div class="relative flex flex-col mt-5 lg:px-10">
					<div class="relative flex-shrink-0 w-32 h-32 cursor-pointer group">
						<img id="preview" src="{{ auth()->user()->avatar() . '?' . time() }}" class="w-32 h-32 rounded-full">
						
						<div class="absolute inset-0 w-full h-full">
							<input type="file" id="upload" class="absolute inset-0 z-20 w-full h-full opacity-0 cursor-pointer group">
							<button class="absolute bottom-0 z-10 flex items-center justify-center w-10 h-10 mb-2 -ml-5 bg-black bg-opacity-75 rounded-full opacity-75 left-1/2 group-hover:opacity-100">
								<svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
							</button>
						</div>
					</div>
					@error('avatar')
						<p class="mt-3 text-sm text-red-600">The avatar must be a valid image type.</p>
					@enderror
					<div class="w-full mt-8">
						{{ $this->form }}
					</div>
					<div class="w-full pt-6 text-right">
						<x-button type="submit">Save</x-button>
					</div>
				</div>

			</form>

			<div class="z-[99]">
				<x-filament::modal id="profile-avatar-crop">
					<div>
						<div class="mt-3 text-center sm:mt-5">
							<h3 class="text-lg font-medium leading-6 text-zinc-900" id="modal-headline">
								Position and resize your photo
							</h3>
							<div class="mt-2">
								<div id="upload-crop-container" class="relative flex items-center justify-center h-56 mt-5">
									<div id="uploadLoading" class="flex items-center justify-center w-full h-full">
										<svg class="w-5 h-5 mr-3 -ml-1 animate-spin text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
							<button @click="window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'profile-avatar-crop' }}));" class="inline-flex justify-center w-full px-4 py-2 mr-2 text-base font-medium leading-6 transition duration-150 ease-in-out bg-white border border-transparent rounded-md shadow-sm text-zinc-700 border-zinc-300 hover:text-zinc-500 active:text-zinc-800 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue sm:text-sm sm:leading-5" type="button">Cancel</button>
							<button @click="window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'profile-avatar-crop' }})); applyImageCrop()" class="inline-flex justify-center w-full px-4 py-2 ml-2 text-base font-medium leading-6 text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave sm:text-sm sm:leading-5" id="apply-crop" type="button">Apply</button>
						</span>
					</div>
				</x-filament::modal>
			</div>
		</div>
		@endvolt
    </x-app.settings-layout>

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
	</x-slot>

</x-layouts.app>
