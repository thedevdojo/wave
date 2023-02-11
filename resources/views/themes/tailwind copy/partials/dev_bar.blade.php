<div
    x-init="$watch('open', value => {
        if(value){
            document.body.classList.add('overflow-hidden');
            let thisElement = $el;
        } else {
            document.body.classList.remove('overflow-hidden');
        }})"
    id="wave_dev_bar"
    class="fixed bottom-0 left-0 z-40 w-full h-screen transition-all duration-150 ease-out transform"
    x-data="{ open: false, url: '', active: '' }"
    :class="{ 'translate-y-full': !open, 'translate-y-0': open }"
    x-on:keydown.escape.window="open = false"
    x-cloak>
    <div class="fixed inset-0 z-20 bg-black bg-opacity-25" x-show="open" @click="open=false"></div>



    <div class="absolute inset-0 z-30 hidden sm:block" :class="{ 'bottom-0': !open }">

        <div class="inset-0 z-40 transition duration-200 ease-out" :class="{ 'absolute h-14': open, 'relative h-10 -mt-10': !open }">
            <div class="w-full h-full border-t border-blue-500 bg-gradient-to-r from-wave-500 via-blue-500 to-purple-600" :class="{ 'overflow-hidden': open }">
                <div class="flex justify-between w-full h-full">
                    <div class="flex h-full">
                        <div class="relative flex items-center justify-center h-full" :class="{ 'px-2': !open, 'px-4': open }">
                            <svg class="mx-0.5" :class="{ 'w-5 h-5': !open, 'w-6 h-6': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 208 206"><defs></defs><defs><linearGradient id="a_devbar" x1="100%" x2="0%" y1="45.596%" y2="45.596%"><stop offset="0%" stop-color="#ffffff"></stop><stop offset="100%" stop-color="#ffffff"></stop></linearGradient><linearGradient id="b_devbar" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stop-color="#ffffff"></stop><stop offset="100%" stop-color="#ffffff"></stop></linearGradient><linearGradient id="c_devbar" x1="0%" x2="99.521%" y1="50%" y2="50%"><stop offset="0%" stop-color="#ffffff"></stop><stop offset="99.931%" stop-color="#ffffff"></stop></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="url(#a_devbar)" d="M185.302 38c14.734 18.317 22.742 41.087 22.698 64.545C208 159.68 161.43 206 103.986 206c-39.959-.01-76.38-22.79-93.702-58.605C-7.04 111.58-2.203 69.061 22.727 38a104.657 104.657 0 00-9.283 43.352c0 54.239 40.55 98.206 90.57 98.206 50.021 0 90.571-43.973 90.571-98.206A104.657 104.657 0 00185.302 38z"></path><path fill="url(#b_devbar)" d="M105.11 0A84.144 84.144 0 01152 14.21C119.312-.651 80.806 8.94 58.7 37.45c-22.105 28.51-22.105 68.58 0 97.09 22.106 28.51 60.612 38.101 93.3 23.239-30.384 20.26-70.158 18.753-98.954-3.75-28.797-22.504-40.24-61.021-28.47-95.829C36.346 23.392 68.723.002 105.127.006L105.11 0z"></path><path fill="url(#c_devbar)" d="M118.98 13c36.39-.004 66.531 28.98 68.875 66.234 2.343 37.253-23.915 69.971-60.006 74.766 29.604-8.654 48.403-38.434 43.99-69.685-4.413-31.25-30.678-54.333-61.459-54.014-30.78.32-56.584 23.944-60.38 55.28v-1.777C49.99 44.714 80.872 13.016 118.98 13z"></path></g></svg>
                        </div>

                        <div @click="open=true; url='/docs'; active='docs';" class="flex items-center justify-center h-full text-xs leading-none text-blue-100 border-l border-blue-500 cursor-pointer hover:bg-wave-600" :class="{ 'px-3': !open, 'px-5': open, 'bg-wave-600': active == 'docs' && open, 'bg-wave-500': active != 'docs' }">
                            Documentation
                        </div>
                        @if(!auth()->guest() && auth()->user()->can('browse_admin'))
                            <div @click="open=true; url='/admin'; active='admin';" class="flex items-center justify-center h-full text-xs leading-none text-blue-100 border-l border-blue-500 cursor-pointer hover:bg-wave-600" :class="{ 'px-3': !open, 'px-5': open, 'bg-wave-600': active == 'admin' && open, 'bg-wave-500': active != 'admin' }">
                                Admin
                            </div>
                        @endif
                    </div>
                    <div x-show="open" @click="open=false" class="flex flex-col items-center justify-center h-full text-white border-l border-purple-500 opacity-75 cursor-pointer w-14 hover:bg-black hover:bg-opacity-10 hover:opacity-100">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="text-xs opacity-50">esc</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="relative w-full h-full overflow-hidden bg-white">
            <iframe class="w-full h-full pt-14" :src="url"></iframe>
        </div>
    </div>
</div>
