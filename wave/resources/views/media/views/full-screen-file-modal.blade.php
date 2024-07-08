
<div
    x-data="{
        show: false,
        file: null,
        isImage: false,
        showModal(file) {
            console.log('mader');
            this.file = file;
            this.isImage = file.type.startsWith('image');
            this.show = true;
        },
        hideModal() {
            this.show = false;
            setTimeout(function(){
                this.file = null;
            }, 500);
        }
    }"
    @open-file-modal.window="showModal($event.detail.file)"
    @open-file-modal-active-file.window="showModal(active)"
    @close-file-modal.window="hideModal();"
    x-show="show"
    x-cloak
    class="overflow-y-auto fixed inset-0 z-50 py-10"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <div class="flex overflow-scroll fixed inset-0 justify-center items-center px-0 py-10 w-screen h-screen text-center sm:px-4 lg:px-0">
        <div
            x-show="show"
            @click="hideModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-60 transition-opacity"
            aria-hidden="true"
        ></div>


        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="block overflow-hidden mx-auto w-full h-full text-left bg-white shadow-xl transition-all transform sm:rounded-lg sm:max-w-3xl lg:max-w-5xl"
        >
            <div class="absolute top-0 right-0 z-50 pt-4 pr-4">
                <button
                    @click="hideModal"
                    type="button"
                    class="p-1 text-gray-700 rounded-full bg-white/50 hover:text-gray-900 focus:outline-none"
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex flex-col h-full sm:items-start">
                <div class="flex overflow-hidden relative justify-center items-center w-full h-full bg-zinc-200">
                    <template x-if="isImage">
                        <img :src="file.url" alt="File preview" class="object-cover absolute w-auto max-w-full h-auto max-h-full">
                    </template>
                    <template x-if="!isImage">
                        <div class="p-4 bg-gray-100 rounded">
                            <p class="text-gray-700">File preview not available</p>
                        </div>
                    </template>
                </div>
                <div class="flex-shrink-0 px-4 pb-4 mt-4 w-full h-auto text-sm">
                    <h3 class="mb-3 text-base font-bold leading-6 text-gray-900" id="modal-title" x-text="file ? file.name : ''"></h3>
                    
                    <p><strong>Type:</strong> <span x-text="file ? file.type : ''"></span></p>
                    <p x-show="file && file.type == 'folder'"><strong>File count:</strong> <span x-text="file ? file.items : ''"></span></p>
                    <p x-show="file && file.type != 'folder'"><strong>Filesize:</strong> <span x-text="file ? file.size : ''"></span></p>
                    <p x-show="file && file.type != 'folder'"><strong>Full URL:</strong> <a :href="file ? file.url : ''" target="_blank" class="text-blue-600 underline hover:text-blue-500">open in new tab</a></p>
                    <p x-show="file && file.type != 'folder'"><strong>Last Modified:</strong> <span x-text="file ? file.last_modified : ''"></span></p>

                </div>
            </div>
        </div>
    </div>
</div>