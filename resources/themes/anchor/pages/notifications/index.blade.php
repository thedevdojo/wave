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
			<x-card class="lg:p-10">

				<x-elements.back-button
					text="Back to Dashboard"
					:href="route('dashboard')"
				/>

				<x-app.heading
					title="Notifications"
					description="View your current notifications"
				/>

				<div class="w-full">
					@forelse ($unreadNotifications as $index => $notification)
						@php $notification_data = (object)$notification->data; @endphp
						<div id="notification-li-{{ $index + 1 }}" class="flex flex-col pb-5 @if(!$loop->last){{ 'border-b' }}@endif border-zinc-200">

							<a href="{{ @$notification_data->link }}" class="flex items-start p-5 pb-2">
								<div class="flex-shrink-0 pt-1">
									<img class="w-10 h-10 rounded-full" src="{{ @$notification_data->icon }}" alt="">
								</div>
								<div class="flex flex-col flex-1 items-start ml-3 space-y-1 w-0">
									<div class="flex relative items-center space-x-2">
										<p class="text-sm font-bold leading-5 text-zinc-600">{{ @$notification_data->user['name'] }}</p>
										<time class="text-xs font-medium leading-5 text-zinc-500">{{ \Carbon\Carbon::parse(@$notification->created_at)->format('F, jS h:i A') }}</span></time>
									</div>
									<p class="text-sm leading-5 text-zinc-600">{{ @$notification_data->body }} in <span class="notification-highlight">{{ @$notification_data->title }}</span>
									
								</div>
							</a>
							<span wire:click="delete('{{ $notification->id }}')" class="flex justify-start py-1 pl-16 ml-1 w-full text-xs cursor-pointer text-zinc-500 k hover:text-zinc-700 mark-as-read hover:underline">
								<svg class="absolute mt-1 mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
								<svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
								Mark as Read
							</span>

						</div>
					@empty
						<div class="@if($notifications_count > 0){{ 'hidden' }}@endif flex items-center bg-gray-100 mt-5 rounded-lg justify-center h-24 w-full text-gray-400">
							<svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
							No Notifications
						</div>
					@endforelse
				</div>
				
			</x-card>
		</x-app.container>
	@endvolt

</x-layouts.app>