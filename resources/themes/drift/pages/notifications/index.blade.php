<?php
    use function Laravel\Folio\{middleware, name};
	use Livewire\Volt\Component;
    name('notifications');
    middleware('auth');

	new class extends Component{

		public $notifications_count;
		public $unreadNotifications;
		 
		public function mount(){
			$this->updateNotifications();
		}

		public function delete($id){
			$notification = auth()->user()->notifications()->where('id', $id)->first();
			if ($notification){
				$notification->delete();
			}
			$this->updateNotifications();
		}

		public function updateNotifications(){
			$this->setUnreadNotifications = $this->unreadNotifications = auth()->user()->unreadNotifications->all();  
			$this->notifications_count = auth()->user()->unreadNotifications->count();}
		}
?>

<x-layouts.app>
	@volt('notifications')
		<div class="relative w-full">
			<x-app.heading
					title="User Notifications"
					description="Find out what you've missed."
				/>
				
			<x-app.container class="z-0 my-6">
				
				<div class="max-w-4xl mx-auto">
					<x-back-button text="Back to Dashboard" href="/dashboard"></x-back-button>

					<x-app.card class="max-w-4xl p-10 mx-auto">
						<div class="relative">
							<div class="relative top-0 right-0 w-full my-2 lg:my-8 overflow-hidden origin-top">
								@if($notifications_count <= 0)
									<div id="notifications-none" class="flex items-center justify-center w-full h-24 text-sm font-medium text-gray-600 dark:text-gray-400">
										<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
										All Caught Up!
									</div>
								@endif
								<div class="relative space-y-5">
									@foreach ($unreadNotifications as $index => $notification)
										@php $notification_data = (object)$notification->data; @endphp	
										<div class="relative flex flex-col items-start w-full space-x-2 sm:flex-row">
											<a href="{{ @$notification_data->link }}" class="flex items-start flex-1 w-full p-5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl">
												<span class="flex-shrink-0 pt-1">
													<img class="w-10 h-10 rounded-full" src="{{ @$notification_data->icon }}" alt="">
												</span>
												<span class="flex flex-col items-start flex-1 w-0 ml-3">
													<span class="text-sm leading-5 text-gray-600 dark:text-gray-300">
														<strong>{{ @$notification_data->user['name'] }} · </strong>
														{{ @$notification_data->body }}
													</span>
													<span class="mt-1 text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
														<span class="notification-datetime">{{ \Carbon\Carbon::parse(@$notification->created_at)->format('F, jS h:i A') }}</span>
													</span>
												</span>
											</a>
											<div class="flex items-center justify-end w-full pt-1 sm:justify-start sm:w-auto">
												<span wire:click="delete('{{ $notification->id }}')" data-id="{{ $notification->id }}" data-listid="{{ $index+1 }}" class="flex justify-start w-auto px-3 py-2 mr-3 text-xs text-gray-500 rounded-md cursor-pointer sm:mr-0 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 hover:text-gray-700 mark-as-read hover:underline">
													<x-phosphor-check class="w-4 h-4 mr-1"></x-phosphor-check>
													Mark as Read
												</span>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						</div>
					</x-app.card>
				</div>
			</x-app.container>
		</div>
	@endvolt
</x-layouts.app>