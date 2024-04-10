<div>

    <form wire:submit="save" method="POST">
        <div class="relative flex flex-col px-10 py-8">

            <div>
                <label for="current_password" class="block text-sm font-medium leading-5 text-zinc-700">Current Password</label>
                <div class="mt-1 rounded-md shadow-sm">
                    <input wire:model.live="current_password" id="current_password" type="password" name="current_password" placeholder="Current Password" class="w-full form-input">
                </div>
                @error('current_password') <span class="block mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-5">
                <label for="password" class="block text-sm font-medium leading-5 text-zinc-700">New Password</label>
                <div class="mt-1 rounded-md shadow-sm">
                    <input wire:model.live="password" id="password" type="password" name="password" placeholder="New Password" class="w-full form-input">
                </div>
                @error('password') <span class="block mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-5">
                <label for="password_confirmation" class="block text-sm font-medium leading-5 text-zinc-700">Confirm New Password</label>
                <div class="mt-1 rounded-md shadow-sm">
                    <input wire:model.live="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm New Password" class="w-full form-input">
                </div>
                @error('password_confirmation') <span class="block mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <input type="hidden" name="_method" value="PUT">
            {{ csrf_field() }}
            <div class="flex justify-end w-full mt-2">
                <button class="flex self-end justify-center w-auto px-4 py-2 mt-5 text-sm font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave active:bg-blue-700" dusk="update-profile-button">Update</button>
            </div>

        </div>
    </form>

</div>
