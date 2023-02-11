<form action="{{ route('wave.settings.security.put') }}" method="POST" enctype="multipart/form-data">
	<div class="relative flex flex-col px-10 py-8">
		
		<div>
			<label for="current_password" class="block text-sm font-medium leading-5 text-gray-700">Current Password</label>
			<div class="mt-1 rounded-md shadow-sm">
				<input id="current_password" type="password" name="current_password" placeholder="Current Password" class="w-full form-input">
			</div>
		</div>

		<div class="mt-5">
			<label for="password" class="block text-sm font-medium leading-5 text-gray-700">New Password</label>
			<div class="mt-1 rounded-md shadow-sm">
				<input id="password" type="password" name="password" placeholder="New Password" class="w-full form-input">
			</div>
		</div>

		<div class="mt-5">
			<label for="password_confirmation" class="block text-sm font-medium leading-5 text-gray-700">Confirm New Password</label>
			<div class="mt-1 rounded-md shadow-sm">
				<input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm New Password" class="w-full form-input">
			</div>
		</div>

		<input type="hidden" name="_method" value="PUT">
		{{ csrf_field() }}
		<div class="flex justify-end w-full mt-2">
			<button class="flex self-end justify-center w-auto px-4 py-2 mt-5 text-sm font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave active:bg-wave-700" dusk="update-profile-button">Update</button>
		</div>
		
	</div>
</form>