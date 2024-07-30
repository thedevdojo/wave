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
		<x-app.container>
			<x-back-button text="Back to Dashboard" href="/dashboard"></x-back-button>
			<x-card class="p-10">
				<x-app.heading
					title="User Notifications"
					description="Find out what you've missed."
				/>

				<div class="relative">
					
					<div class="overflow-hidden relative top-0 right-0 my-8 w-full origin-top">
						@if($notifications_count <= 0)
							<div id="notifications-none" class="flex justify-center items-center w-full h-24 font-medium bg-gray-100 text-zinc-600">
								<svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
								All Caught Up!
							</div>
						@endif
						<div class="relative space-y-5">
							@foreach ($unreadNotifications as $index => $notification)
								@php $notification_data = (object)$notification->data; @endphp	
								<div id="notification-li-{{ $index + 1 }}" class="flex flex-col pb-5 border border-black hover:bg-gray-100">
									<a href="{{ @$notification_data->link }}" class="flex items-start p-5 pb-2">
										<div class="flex-shrink-0 pt-1">
											<img class="w-10 h-10 rounded-full" src="{{ @$notification_data->icon }}" alt="">
										</div>
										<div class="flex flex-col flex-1 items-start ml-3 w-0">
											<p class="text-sm leading-5 text-zinc-600">
												<strong>{{ @$notification_data->user['name'] }} · </strong>
												{{ @$notification_data->body }}
											</p>
											<p class="mt-2 text-sm font-medium leading-5 text-zinc-500">
												<span class="notification-datetime">{{ \Carbon\Carbon::parse(@$notification->created_at)->format('F, jS h:i A') }}</span>
											</p>
										</div>
									</a>
									<span wire:click="delete('{{ $notification->id }}')" data-id="{{ $notification->id }}" data-listid="{{ $index+1 }}" class="flex justify-start py-1 pl-16 ml-1 w-full text-xs cursor-pointer text-zinc-500 k hover:text-zinc-700 mark-as-read hover:underline">
										<x-phosphor-checks class="mr-1 w-4 h-4"></x-phosphor-check>
										Mark as Read
									</span>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</x-card>
		</x-app.container>
	@endvolt
</x-layouts.app>