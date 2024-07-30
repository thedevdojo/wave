@php $notifications_count = auth()->user()->unreadNotifications->count(); @endphp

@if(!isset($show_all_notifications))
    @php $unreadNotifications = auth()->user()->unreadNotifications->take(5); @endphp
    <div id="notification-list" @click.away="open = false" class="flex relative items-center h-full" x-data="{ open: false }">
        <div id="notification-icon relative">
            <button @click="open = !open" class="relative p-1 ml-3 rounded-full transition duration-150 ease-in-out text-zinc-400 hover:text-zinc-500 focus:outline-none focus:text-zinc-500 focus:bg-zinc-100">
                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                @if($unreadNotifications && $notifications_count > 0) <span id="notification-count" class="flex absolute top-0 right-0 justify-center items-center w-4 h-4 text-xs text-red-100 bg-red-500 rounded-full">{{ $notifications_count }}</span> @endif
            </button>
        </div>
@else
    @php $unreadNotifications = auth()->user()->unreadNotifications->all(); @endphp
@endif

    @if(!isset($show_all_notifications))
        <div x-show="open"
            x-transition:enter="duration-100 ease-out scale-95"
            x-transition:enter-start="opacity-50 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition duration-50 ease-in scale-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="overflow-hidden absolute top-0 right-0 mt-20 max-w-lg max-w-7xl rounded-lg shadow-lg transform origin-top-right w-104" x-cloak>
    @else
        <div class="overflow-hidden relative top-0 right-0 my-8 w-full max-w-7xl origin-top">
    @endif
        <div class="bg-white rounded-md border border-zinc-100 @if(!isset($show_all_notifications)){{ 'shadow-md' }}@endif" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
        @if(!isset($show_all_notifications))
            <div id="notification-header">
                <div id="notification-head-content" class="flex items-center px-3 py-3 w-full border-b text-zinc-600 border-zinc-200">
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Notifications
                </div>
            </div>
        @endif

            <div id="notifications-none" class="@if($notifications_count > 0){{ 'hidden' }}@endif @if(isset($show_all_notifications)){{ 'bg-zinc-150' }}@endif flex items-center justify-center h-24 w-full text-zinc-600 font-medium">
                <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                All Caught Up!
            </div>

            <div class="relative">


                @foreach ($unreadNotifications as $index => $notification)
                    @php $notification_data = (object)$notification->data; @endphp
                    <div id="notification-li-{{ $index + 1 }}" class="flex flex-col pb-5 border-b border-zinc-200 @if(!isset($show_all_notifications)){{ 'hover:bg-zinc-50' }}@endif">

                        <a href="{{ @$notification_data->link }}" class="flex items-start p-5 pb-2">
                            <div class="flex-shrink-0 pt-1">
                                <img class="w-10 h-10 rounded-full" src="{{ @$notification_data->icon }}" alt="">
                            </div>
                            <div class="flex flex-col flex-1 items-start ml-3 w-0">
                                <p class="text-sm leading-5 text-zinc-600">
                                    <strong>{{ @$notification_data->user['username'] }} @if(isset($notification_data->type) && @$notification_data->type == 'message'){{ 'left a message' }}@else{{ 'said' }}@endif</strong>
                                    {{ @$notification_data->body }} in <span class="notification-highlight">{{ @$notification_data->title }}</span>
                                </p>
                                <p class="mt-2 text-sm font-medium leading-5 text-zinc-500">
                                    <span class="notification-datetime">{{ \Carbon\Carbon::parse(@$notification->created_at)->format('F, jS h:i A') }}</span>
                                </p>
                            </div>
                        </a>
                        <span data-id="{{ $notification->id }}" data-listid="{{ $index+1 }}" class="flex justify-start py-1 pl-16 ml-1 w-full text-xs cursor-pointer text-zinc-500 k hover:text-zinc-700 mark-as-read hover:underline">
                            <svg class="absolute mt-1 mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Mark as Read
                        </span>

                    </div>

                @endforeach

            </div>

        @if(!isset($show_all_notifications))
            <div id="notification-footer" class="flex justify-center items-center py-3 text-xs font-medium border-t text-zinc-600 bg-zinc-100 border-zinc-200">
                <a href="{{ route('notifications') }}"><span uk-icon="icon: eye"></span>View All Notifications</a>
            </div>
        @endif

        </div>
    </div>

@if(!isset($show_all_notifications))
    </div><!-- End of #notification-list -->
@endif
