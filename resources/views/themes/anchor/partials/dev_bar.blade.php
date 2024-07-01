<script>
    window.escapeKeyCloseDevBar = function(event){
        if(event.key === 'Escape'){
            document.getElementById('wave_dev_bar').__x.$data.open = false;
            document.getElementById('wave_dev_bar').__x.$data.url = '';
            document.getElementById('wave_dev_bar').__x.$data.active = '';
        }
    }
</script>
<div
    x-init="$watch('open', value => {
        if(value){
            document.body.classList.add('overflow-hidden');
            let thisElement = $el;
            escapeKeyListener = document.addEventListener('keydown', escapeKeyCloseDevBar);
        } else {
            document.body.classList.remove('overflow-hidden');
            document.removeEventListener('keydown', escapeKeyCloseDevBar);
        }})"
    id="wave_dev_bar"
    class="fixed bottom-0 left-0 z-[999] w-full h-screen transition-all duration-150 ease-out transform"
    x-data="{ open: false, url: '', active: '' }"
    :class="{ 'translate-y-full': !open, 'translate-y-0': open }"
    x-on:keydown.escape="open = false"
    x-cloak>
    <div class="fixed inset-0 z-20 bg-black bg-opacity-25" x-show="open" @click="open=false"></div>

    

    <div class="hidden absolute inset-0 z-30 sm:block" :class="{ 'bottom-0': !open }">
    
        <div class="inset-0 z-40 h-10 transition duration-200 ease-out" :class="{ 'absolute': open, 'relative -mt-10': !open }">
            <div class="w-full h-full bg-gradient-to-r from-blue-500 via-blue-500 to-purple-600 border-t border-blue-500" :class="{ 'overflow-hidden': open }">
                <div class="flex justify-between w-full h-full">
                    

                    <div class="flex items-center h-full">
                        
                        <div class="flex relative justify-center items-center px-2 h-full border-r border-white/10">
                            <svg class="mx-0.5 w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 151 146" fill="none"><path fill="currentColor" d="M4.062 145.905h36.147a4 4 0 0 0 4-4v-36.146a4 4 0 0 0-4-4H4.062a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4ZM57.037 145.908h36.147a4 4 0 0 0 4-4v-36.147a4 4 0 0 0-4-4H57.037a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4Z"/><path fill="currentColor" d="M57.038 95.138h36.147a4 4 0 0 0 4-4V54.99a4 4 0 0 0-4-4H57.038a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4Z"/><path fill="currentColor" d="M110.013 95.138h36.147a4 4 0 0 0 4-4V54.99a4 4 0 0 0-4-4h-36.147a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4ZM110.014 44.367h36.147a4 4 0 0 0 4-4V4.221a4 4 0 0 0-4-4h-36.147a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4Z"/></svg>
                        </div>
                        <div @click="open=true; url='/docs'; active='docs';" class="flex justify-center items-center px-3 h-full text-xs leading-none cursor-pointer text-zinc-100 hover:bg-blue-600" :class="{ 'bg-blue-600': active == 'docs' && open, 'bg-transparent': active != 'docs' }">
                            Documentation
                        </div>
                        @if(!auth()->guest() && auth()->user()->can('browse_admin'))
                            <div @click="open=true; url='/admin'; active='admin';" class="flex justify-center items-center h-full text-xs leading-none text-blue-100 cursor-pointer hover:bg-blue-600" :class="{ 'px-3': !open, 'px-5': open, 'bg-blue-600': active == 'admin' && open, 'bg-blue-500': active != 'admin' }">
                                Admin
                            </div>
                        @endif
                    </div>
                    <div x-show="open" @click="open=false" class="flex justify-center items-center px-2 w-auto h-full text-white border-l opacity-75 cursor-pointer border-white/10 hover:bg-blue-600 hover:bg-opacity-10 hover:opacity-100">
                        <span class="flex items-center px-2 py-1 pt-0.5 text-xs leading-none rounded-full opacity-50 bg-zinc-600">esc</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        
                    </div>
                </div>
            </div>
        </div>


        <div class="overflow-hidden relative w-full h-full bg-white">
            <iframe class="pt-10 w-full h-full" :src="url"></iframe>
        </div>
    </div>
</div>
