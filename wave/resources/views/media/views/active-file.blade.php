<active-file x-show="activeFileDrawer" class="relative flex-shrink-0 w-[17rem] h-full lg:block hidden bg-gray-50 rounded-md p-3">
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

                <template x-if="active.type.endsWith('pdf')">
                    <svg class="w-20 h-20 fill-current text-zinc-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M44,120H212a4,4,0,0,0,4-4V88a8,8,0,0,0-2.34-5.66l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40v76A4,4,0,0,0,44,120ZM152,44l44,44H152Zm72,108.53a8.18,8.18,0,0,1-8.25,7.47H192v16h15.73a8.17,8.17,0,0,1,8.25,7.47,8,8,0,0,1-8,8.53H192v15.73a8.17,8.17,0,0,1-7.47,8.25,8,8,0,0,1-8.53-8V152a8,8,0,0,1,8-8h32A8,8,0,0,1,224,152.53ZM64,144H48a8,8,0,0,0-8,8v55.73A8.17,8.17,0,0,0,47.47,216,8,8,0,0,0,56,208v-8h7.4c15.24,0,28.14-11.92,28.59-27.15A28,28,0,0,0,64,144Zm-.35,40H56V160h8a12,12,0,0,1,12,13.16A12.25,12.25,0,0,1,63.65,184ZM128,144H112a8,8,0,0,0-8,8v56a8,8,0,0,0,8,8h15.32c19.66,0,36.21-15.48,36.67-35.13A36,36,0,0,0,128,144Zm-.49,56H120V160h8a20,20,0,0,1,20,20.77C147.58,191.59,138.34,200,127.51,200Z"/></svg>
                </template>

                <template x-if="active.type.endsWith('zip')">
                    <svg class="w-20 h-20 fill-current text-zinc-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M184,144H168a8,8,0,0,0-8,8v55.73a8.17,8.17,0,0,0,7.47,8.25,8,8,0,0,0,8.53-8v-8h7.4c15.24,0,28.14-11.92,28.59-27.15A28,28,0,0,0,184,144Zm-.35,40H176V160h8A12,12,0,0,1,196,173.16,12.25,12.25,0,0,1,183.65,184ZM136,152v55.73a8.17,8.17,0,0,1-7.47,8.25,8,8,0,0,1-8.53-8V152.27a8.17,8.17,0,0,1,7.47-8.25A8,8,0,0,1,136,152ZM96,208.53A8.17,8.17,0,0,1,87.73,216H56.23a8.27,8.27,0,0,1-6-2.5A8,8,0,0,1,49.05,204l25.16-44H56.27A8.17,8.17,0,0,1,48,152.53,8,8,0,0,1,56,144H87.77a8.27,8.27,0,0,1,6,2.5A8,8,0,0,1,95,156L69.79,200H88A8,8,0,0,1,96,208.53ZM213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40v76a4,4,0,0,0,4,4H212a4,4,0,0,0,4-4V88A8,8,0,0,0,213.66,82.34ZM152,88V44l44,44Z"/></svg>
                </template>

                <template x-if="active.type.endsWith('wav') || active.type.endsWith('mp3') || active.type.endsWith('m4a')">
                    <svg class="w-20 h-20 fill-current text-zinc-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M152,180a40.55,40.55,0,0,1-20,34.91A8,8,0,0,1,124,201.09a24.49,24.49,0,0,0,0-42.18A8,8,0,0,1,132,145.09,40.55,40.55,0,0,1,152,180ZM99.06,128.61a8,8,0,0,0-8.72,1.73L68.69,152H48a8,8,0,0,0-8,8v40a8,8,0,0,0,8,8H68.69l21.65,21.66A8,8,0,0,0,104,224V136A8,8,0,0,0,99.06,128.61ZM216,88V216a16,16,0,0,1-16,16H168a8,8,0,0,1,0-16h32V96H152a8,8,0,0,1-8-8V40H56v80a8,8,0,0,1-16,0V40A16,16,0,0,1,56,24h96a8,8,0,0,1,5.66,2.34l56,56A8,8,0,0,1,216,88Zm-56-8h28.69L160,51.31Z"/></svg>
                </template>

                <template x-if="active.type.endsWith('mp4') || active.type.endsWith('quicktime') || active.type.endsWith('avi') || active.type.endsWith('wmv') || active.type.endsWith('webm') || active.type.endsWith('ogg') || active.type.endsWith('mpeg') || active.type.endsWith('mpg') || active.type.endsWith('m4v')">
                    <svg class="w-20 h-20 fill-current text-zinc-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><polyline points="152 32 152 88 208 88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M192,224h8a8,8,0,0,0,8-8V88L152,32H56a8,8,0,0,0-8,8v72" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><rect x="40" y="152" width="80" height="64" rx="8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><rect x="40" y="152" width="80" height="64" rx="8"/><polyline points="120 172 152 152 152 216 120 196" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                </template>

                <template x-if="!active.type.startsWith('image') && active.type != 'folder' && !active.type.endsWith('pdf') && !active.type.endsWith('zip') && !active.type.endsWith('wav') && !active.type.endsWith('mp3') && !active.type.endsWith('m4a') && !active.type.endsWith('mp4') && !active.type.endsWith('quicktime') && !active.type.endsWith('avi') && !active.type.endsWith('wmv') && !active.type.endsWith('webm') && !active.type.endsWith('ogg') && !active.type.endsWith('mpeg') && !active.type.endsWith('mpg') && !active.type.endsWith('m4v')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12"><path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" /><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" /></svg>
                </template>
            </div>
            <div class="p-2 space-y-2 h-auto text-xs break-all text-neutral-500">
                <p><strong class="text-neutral-600">Name:</strong> <span x-text="active.name"></span></p>
                <p><strong class="text-neutral-600">Type:</strong> <span x-text="active.type"></span></p>
                <p x-show="active.type == 'folder'"><strong class="text-neutral-600">File count:</strong> <span x-text="active.items"></span></p>
                <p x-show="active.type != 'folder'"><strong class="text-neutral-600">Filesize:</strong> <span x-text="active.size"></span></p>
                <p x-show="active.type != 'folder'"><strong class="text-neutral-600">Full URL:</strong> <a :href="active.url" target="_blank" class="text-blue-600 underline hover:text-blue-500">open in new tab</a></p>
                <p x-show="active.type != 'folder'"><strong class="text-neutral-600">Last Modified:</strong> <span x-text="active.last_modified"></span></p>
            </div>
        </div>
    </template>
</active-file>