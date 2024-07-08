<nav class="flex justify-between items-center py-2 w-full text-xs">
    <ol role="list" class="flex items-center space-x-1">
        <li>
            <button wire:click="goToDirectory('/')" class="@if($this->isRootDirectory()){{ 'bg-gray-100' }}@else{{ 'bg-transparent' }}@endif inline-flex items-center px-3 py-2 font-normal text-center text-gray-900 rounded-md hover:bg-gray-100 focus:outline-none">
                <svg class="mr-1 w-3 h-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z" fill="currentColor"></path></svg>
                <span>Home</span>
            </button>
        </li>
        @foreach($breadcrumbs as $breadcrumb)
            <li><svg class="flex-shrink-0 w-5 h-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"></path></svg></li>
            <li>
                <button 
                    wire:click="goToDirectory('{{ $breadcrumb->location }}')" 
                    class="@if($loop->last) bg-gray-100 @else bg-transparent @endif
                        inline-flex items-center px-3 py-2 font-normal text-center text-gray-900 rounded-md hover:bg-gray-100 focus:outline-none">
                    @if($loop->last)
                        <svg class="mr-1 w-3 h-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19 9V8.84848C19 6.69156 17.2546 4.93939 15.0971 4.93939H12.7731C12.4214 4.93939 12.0764 4.8416 11.7766 4.65669L10.0313 3.58039C9.41611 3.20098 8.70772 3 7.985 3H5.90292C3.74539 3 2 4.75216 2 6.90909V17.0909C2 19.2478 3.74539 21 5.90292 21H16.761C18.5535 21 20.1353 19.7959 20.5654 18.0554L21.9153 12.5931C22.3735 10.7387 20.9376 9 19.056 9H19ZM5.90292 5C4.85397 5 4 5.85272 4 6.90909V17.0909C4 17.5423 4.15596 17.9566 4.41644 18.283L6.31638 11.7918C6.80168 10.1278 8.3447 9 10.0788 9H17V8.84848C17 7.79212 16.146 6.93939 15.0971 6.93939H12.7731C12.0504 6.93939 11.342 6.73841 10.7268 6.35901L8.98151 5.2827C8.68168 5.0978 8.33672 5 7.985 5H5.90292Z" fill="currentColor"></path></svg>
                    @else
                        <svg class="mr-1 w-3 h-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.74927 3.00003C3.6782 3.00003 1.99927 4.67896 1.99927 6.75003V17.25C1.99927 19.3211 3.6782 21 5.74927 21H18.2509C20.322 21 22.0009 19.3211 22.0009 17.25V9.75003C22.0009 7.67896 20.322 6.00003 18.2509 6.00003H15.1731C14.6074 6.00003 14.0625 5.78699 13.6469 5.40333L12.1207 3.99453C11.428 3.3551 10.5199 3.00003 9.57716 3.00003H5.74927Z" fill="currentColor"></path></svg>
                    @endif
                    <span>{{ $breadcrumb->display }}</span>
                </button>
            </li>
        @endforeach
    </ol>
    <div class="hidden items-center h-full lg:flex">
        <button @click="activeFileDrawer=!activeFileDrawer" class="p-2 h-full rounded-md hover:bg-gray-100">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M2.74902 6.75C2.74902 5.09315 4.09217 3.75 5.74902 3.75H18.2507C19.9075 3.75 21.2507 5.09315 21.2507 6.75V17.25C21.2507 18.9069 19.9075 20.25 18.2507 20.25H5.74902C4.09217 20.25 2.74902 18.9069 2.74902 17.25V6.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.75 3.75V20.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.75 7.75L18.25 7.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.75 11L18.25 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.75 14.25L18.25 14.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
        </button>
    </div>
</nav>