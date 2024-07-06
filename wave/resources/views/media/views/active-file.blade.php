<active-file x-show="activeFileDrawer" class="relative flex-shrink-0 w-[17rem] h-full  bg-gray-50 rounded-md p-3">
    <template x-if="!activeFileSelected()">
        <div class="flex justify-center items-center h-32 text-sm text-gray-500">
            <p>No active file selected</p>
        </div>
    </template>

    <template x-if="activeFileSelected() && clientSideActive">
        <div class="w-full h-auto">
            <div class="flex justify-center items-center p-3 w-full h-auto border-b border-neutral-200">
                <template x-if="active.type == 'folder'">
                    <div class="flex justify-center items-center h-32">
                        <svg class="w-20 h-20 fill-current text-[#fbd775]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 00-3-3h-3.879a.75.75 0 01-.53-.22L11.47 3.66A2.25 2.25 0 009.879 3H6a3 3 0 00-3 3v3.162A3.756 3.756 0 014.094 9h15.812zM4.094 10.5a2.25 2.25 0 00-2.227 2.568l.857 6A2.25 2.25 0 004.951 21H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-2.227-2.568H4.094z" /></svg>
                    </div>
                </template>

                <template x-if="active.type.startsWith('image')">
                    <img :src="getFullImagePath()" class="object-cover w-full h-auto rounded pointer-events-none" />
                </template>

                <template x-if="!active.type.startsWith('image') && active.type != 'folder'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12"><path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" /><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" /></svg>
                </template>
            </div>
            <div class="p-2 space-y-2 h-auto text-xs break-all text-neutral-400">
                <p><strong class="text-neutral-500">Filename:</strong> <span x-text="active.name"></span></p>
                <p><strong class="text-neutral-500">Type:</strong> <span x-text="active.type"></span></p>
                <p><strong class="text-neutral-500">Filesize:</strong> <span x-text="active.size"></span></p>
                <p x-show="active.type != 'folder'"><strong class="text-neutral-500">Full URL:</strong> <a :href="active.url" target="_blank" class="text-blue-300 underline hover:text-blue-400">open in new tab</a></p>
                <p><strong class="text-neutral-500">Last Modified:</strong> <span x-text="active.last_modified"></span></p>
            </div>
        </div>
    </template>
</active-file>