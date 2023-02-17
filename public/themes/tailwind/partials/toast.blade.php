<div class="fixed inset-0 z-40 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
    <div id="toast" x-data x-cloak
        @click="$store.toast.close()"
        x-show="$store.toast.show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transform transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-lg cursor-pointer pointer-events-auto hover:-translate-1">
        <div class="relative overflow-hidden rounded-lg shadow-xs">
            <div class="px-4 py-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 pr-0.5">
                        <template x-if="$store.toast.type == 'info'">
                            <div class="w-10 h-10">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </template>
                        <template x-if="$store.toast.type == 'warning'">
                            <div class="w-10 h-10">
                                <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                        </template>
                        <template x-if="$store.toast.type == 'success'">
                            <div class="w-10 h-10">
                                <svg class="w-10 h-10 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </template>
                        <template x-if="$store.toast.type == 'danger'">
                            <div class="w-10 h-10">
                                <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 w-0 pl-3.5 ml-1 border-l border-gray-100">
                        <p class="text-sm font-medium leading-5 text-gray-900">
                            <template x-if="$store.toast.type == 'info'"><span>Notice</span></template>
                            <template x-if="$store.toast.type == 'warning'"><span>Warning</span></template>
                            <template x-if="$store.toast.type == 'success'"><span>Success</span></template>
                            <template x-if="$store.toast.type == 'danger'"><span>Something went wrong</span></template>
                        </p>
                        <p class="text-sm leading-5 text-gray-500" x-text="$store.toast.message"></p>
                    </div>
                    <div class="flex self-start flex-shrink-0 ml-4">
                        <button @click="$store.toast.close()" class="inline-flex -mt-1 text-gray-400 transition duration-150 ease-in-out rounded-full focus:outline-none focus:text-gray-500">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div id="toast_bar" class="absolute bottom-0 left-0 w-full h-1 transition-all ease-out"
                :class="{ 'bg-indigo-400' : $store.toast.type == 'info', 'bg-yellow-400' : $store.toast.type == 'warning', 'bg-green-400' : $store.toast.type == 'success', 'bg-red-400' : $store.toast.type == 'danger' }"
                style="transition-duration: 3950ms;"></div>
        </div>
    </div>
</div>
