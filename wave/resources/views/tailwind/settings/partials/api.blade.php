<div class="flex flex-col px-10 py-8">
	<form action="{{ route('wave.settings.api.post') }}" method="POST">
		<div>
			<label for="key_name" class="block text-sm font-medium leading-5 text-gray-700">Create a new API Key</label>
			<div class="mt-1 rounded-md shadow-sm">
				<input id="key_name" type="text" name="key_name" placeholder="Key Name" class="w-full form-input">
			</div>
		</div>

		<div class="flex justify-end w-full mt-2">
			<button class="flex self-end justify-center w-auto px-4 py-2 mt-5 text-sm font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave active:bg-wave-700" dusk="update-profile-button">Create New Key</button>
		</div>

		{{ csrf_field() }}
	</form>
	<hr class="my-12 border-gray-200">
	@if(count(auth()->user()->apiKeys) > 0)

		<p class="block text-sm font-medium leading-5 text-gray-700">Current API Keys</p>

		<div class="mt-2 overflow-hidden border border-gray-150 sm:rounded">
			<table class="min-w-full divide-y divide-gray-200">
				<thead>
					<tr>
						<th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-100">
							Name
						</th>
						<th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-100">
							Created
						</th>
						<th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-100">
							Last Used
						</th>
						<th class="px-6 py-3 bg-gray-100"></th>
					</tr>
				</thead>
				<tbody>
					@foreach(auth()->user()->apiKeys as $apiKey)
						<!-- Odd row -->
						<tr class="@if($loop->index%2 == 0){{ 'bg-white' }}@else{{ 'bg-gray-50' }}@endif">
							<td class="px-6 py-4 text-sm font-medium leading-5 text-gray-900 whitespace-no-wrap">
								{{ $apiKey->name }}
							</td>
							<td class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap">
								{{ $apiKey->created_at->format('F j, Y') }}
							</td>
							<td class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap">
								@if(is_null($apiKey->last_used_at)){{ 'Never Used' }}@else{{ $apiKey->last_used_at->format('F j, Y') }}@endif
							</td>
							<td class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap">
								<button x-data onclick="actionClicked('view', '{{ $apiKey->id }}', '{{ $apiKey->name }}', '{{ $apiKey->key }}')" class="mr-2 text-indigo-600 hover:underline focus:outline-none">
									View
								</button>
								<button onclick="actionClicked('edit', '{{ $apiKey->id }}', '{{ $apiKey->name }}', '{{ $apiKey->key }}');" class="mr-2 text-indigo-600 hover:underline focus:outline-none">
									Edit
								</button>
								<button onclick="actionClicked('delete', '{{ $apiKey->id }}', '{{ $apiKey->name }}', '{{ $apiKey->key }}');" class="text-indigo-600 hover:underline focus:outline-none">
									Delete
								</button>
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		</div>

		<!-- Javascript to call each modal -->
		<script>
			window.actionClicked = function(action, id, name, key){
				Alpine.store(action + 'ApiKey').actionClicked(id, name, key);
			}
		</script>
		<!-- End JS for opening api action modal -->

		<!-- BELOW ARE THE MODALS TO VIEW, EDIT, AND DELETE AN API KEY -->


			<!-- View API KEY -->
			<div x-data="{ open: false, id: '', key: '', name: '' }" x-init="
				$watch('$store.viewApiKey.open', value => {
				if (value === true) { document.body.classList.add('overflow-hidden') }
				else { document.body.classList.remove('overflow-hidden') }
				});" id="viewApiKey" x-show="$store.viewApiKey.open" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
				<div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
					<div x-show="$store.viewApiKey.open" x-on:click="$store.viewApiKey.open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
						<div class="absolute inset-0 bg-black opacity-50"></div>
					</div>

					<!-- This element is to trick the browser into centering the modal contents. -->
					<span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
					<div x-show="$store.viewApiKey.open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
						<div class="flex items-center justify-start mb-4">
							<h3 class="text-lg font-medium leading-6 text-gray-700">
								API Key (<span x-text="$store.viewApiKey.name"></span>)
							</h3>
						</div>
						<div class="flex flex-col justify-between w-full mt-2">
							<input type="text" readonly="readonly" onfocus="this.select();" class="px-2 py-1 my-2 font-mono text-gray-700 bg-gray-100 rounded form-input" :value="$store.viewApiKey.key" id="viewKeyValue">
							<p class="text-sm text-gray-500">This API Key can be used to gain an <code>access_token</code>, which can then be used to interact with the API.</p>
						</div>
						<div class="mt-5 sm:mt-6">
							<span class="flex justify-end w-full rounded-md">
								<button x-on:click="$store.viewApiKey.open = false" type="button" class="inline-flex justify-center px-4 py-2 text-base font-medium leading-6 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline sm:text-sm sm:leading-5">
										Close
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<!-- END View API Key -->

			<!-- Edit API KEY -->
			<div x-data="{ open: false, id: '', key: '', name: '' }" x-init="
				$watch('$store.editApiKey.open', value => {
				if (value === true) { document.body.classList.add('overflow-hidden') }
				else { document.body.classList.remove('overflow-hidden') }
				});" id="editApiKey" x-show="$store.editApiKey.open" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
				<div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
					<div x-show="$store.editApiKey.open" x-on:click="$store.editApiKey.open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
						<div class="absolute inset-0 bg-black opacity-50"></div>
					</div>

					<!-- This element is to trick the browser into centering the modal contents. -->
					<span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
					<div x-show="$store.editApiKey.open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
						<div class="flex items-center justify-start mb-4">
							<h3 class="text-lg font-medium leading-6 text-gray-700">
								API Key Name (<span x-text="$store.editApiKey.open"></span>)
							</h3>
						</div>
						<div class="flex flex-col justify-between w-full mt-2">
							<form action="{{ route('wave.settings.api.put') }}" method="POST">
								<div>
									<label for="key_name" class="block text-sm font-medium leading-5 text-gray-700">Change the name of this API Key</label>
									<div class="mt-1 rounded-md shadow-sm">
										<input id="key_name" type="text" name="key_name" placeholder="Key Name" :value="$store.editApiKey.name" class="w-full form-input">
									</div>
								</div>
								<input type="hidden" name="_method" value="PUT">
								<input type="hidden" name="id" :value="$store.editApiKey.id">
								<p class="flex justify-end mt-5">
									<button x-on:click="$store.editApiKey.open = false" type="button" class="inline-flex justify-center px-4 py-2 mr-3 text-base font-medium leading-6 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline sm:text-sm sm:leading-5">Cancel</button>
									<button type="submit" class="inline-flex justify-center px-4 py-2 text-base font-medium leading-6 text-white transition duration-150 ease-in-out border border-transparent rounded-md shadow-sm bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave sm:text-sm sm:leading-5">Update</button>
								</p>
								{{ csrf_field() }}
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- END Edit API Key -->

			<!-- Delete API KEY -->
			<div x-data="{ open: false, id: '', key: '', name: '' }" x-init="
				$watch('$store.deleteApiKey.open', value => {
				if (value === true) { document.body.classList.add('overflow-hidden') }
				else { document.body.classList.remove('overflow-hidden') }
				});" id="deleteApiKey" x-show="$store.deleteApiKey.open" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
				<div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
					<div x-show="$store.deleteApiKey.open" x-on:click="$store.deleteApiKey.open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
						<div class="absolute inset-0 bg-black opacity-50"></div>
					</div>

					<!-- This element is to trick the browser into centering the modal contents. -->
					<span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
					<div x-show="$store.deleteApiKey.open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
						<div class="flex flex-col justify-between w-full mt-2">
							<div class="sm:flex sm:items-start">
								<div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
								    <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
								    </svg>
								</div>
								<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
								    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-headline">
										Delete this API Key?
								    </h3>
								    <div class="mt-2">
										<p class="text-sm leading-5 text-gray-500">Are you sure you want to delete API Key: <code class="px-2 py-1 text-gray-800 bg-gray-200 rounded"><span x-text="$store.deleteApiKey.name"></span></code></p>
								    </div>
								</div>
							</div>
							<div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
							    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
									<form action="{{ route('wave.settings.api.delete') }}" method="POST">
										<input type="hidden" name="_method" value="DELETE">
										<input type="hidden" name="id" :value="$store.deleteApiKey.id">
										<button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 text-white transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red sm:text-sm sm:leading-5">
							            	Delete
							        	</button>
										@csrf()
									</form>

							    </span>
							    <span class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
							        <button @click="$store.deleteApiKey.open = false;" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue sm:text-sm sm:leading-5">
							            Cancel
							        </button>
							    </span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END Delete API Key -->

	@else
		<p class="w-full text-sm text-center text-gray-600">No API Keys Created Yet.</p>
	@endif
</div>
