
<ul 
    x-data="{ 
        contextMenuOpen: false,
        contextMenuFileClicked: false,
        contextMenuToggle: function(event) {
            this.contextMenuOpen = true;
            event.preventDefault();
            this.$refs.contextmenu.classList.add('opacity-0');
            let that = this;
            $nextTick(function(){
                that.calculateContextMenuPosition(event);
                that.calculateSubMenuPosition(event);
                that.$refs.contextmenu.classList.remove('opacity-0');
            });
        },
        
        calculateContextMenuPosition (clickEvent) {
            if(window.innerHeight < clickEvent.clientY + this.$refs.contextmenu.offsetHeight){
                this.$refs.contextmenu.style.top = (window.innerHeight - this.$refs.contextmenu.offsetHeight) + 'px';
            } else {
                this.$refs.contextmenu.style.top = clickEvent.clientY + 'px';
            }
            if(window.innerWidth < clickEvent.clientX + this.$refs.contextmenu.offsetWidth){
                this.$refs.contextmenu.style.left = (clickEvent.clientX - this.$refs.contextmenu.offsetWidth) + 'px';
            } else {
                this.$refs.contextmenu.style.left = clickEvent.clientX + 'px';
            }
        },
        closeContentMenuIfOpen(event){
            if(this.contextMenuOpen){
                this.contextMenuOpen=false;
            }
        },
        openContextMenu(event){
            this.contextMenuToggle(event);
            $refs.contextmenu.style.top = event.clientY + 'px';
            $refs.contextmenu.style.left = event.clientX + 'px';
        },
        calculateSubMenuPosition (clickEvent) {
            let submenus = document.querySelectorAll('[data-submenu]');
            let contextMenuWidth = this.$refs.contextmenu.offsetWidth;
            for(let i = 0; i < submenus.length; i++){
                if(window.innerWidth < (clickEvent.clientX + contextMenuWidth + submenus[i].offsetWidth)){
                    submenus[i].classList.add('left-0', '-translate-x-full');
                    submenus[i].classList.remove('right-0', 'translate-x-full');
                } else {
                    submenus[i].classList.remove('left-0', '-translate-x-full');
                    submenus[i].classList.add('right-0', 'translate-x-full');
                }
                if(window.innerHeight < (submenus[i].previousElementSibling.getBoundingClientRect().top + submenus[i].offsetHeight)){
                    let heightDifference = (window.innerHeight - submenus[i].previousElementSibling.getBoundingClientRect().top) - submenus[i].offsetHeight;
                    submenus[i].style.top = heightDifference + 'px';
                } else {
                    submenus[i].style.top = '';
                }
            }
        }
    }" 
    x-init="
        
        window.addEventListener('resize', function(event) { contextMenuOpen = false; });
        
        $el.addEventListener('contextmenu', event => {
            console.log('we got entered');
            contextMenuFileClicked=false;
            event.preventDefault();
            setSelectedFileNull();
            openContextMenu(event);
        });
    "
    @close-context-menu.window="contextMenuOpen=false;"
    :class="{ 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5' : activeFileDrawer, 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6' : !activeFileDrawer }"
    class="grid overflow-hidden gap-x-4 gap-y-8 p-5 w-full h-full h-100 sm:gap-x-6 xl:gap-x-8" x-on:click="setSelectedFileNull(); closeContentMenuIfOpen($event)">
    <template x-for="file in files" :key="file.relative_path">
        <li class="relative group aspect-video" x-on:click="$wire.setSelectedFile(file); active=file; $event.stopPropagation(); closeContentMenuIfOpen($event);" x-on:contextmenu="$wire.setSelectedFile(file); active=file; contextMenuFileClicked=true; $event.stopPropagation(); openContextMenu($event);" x-on:dblclick="handleFileDoubleClick(file);">
            <div 
                :class="{ 'ring-indigo-500': isActiveFile(file), 'ring-gray-200' : !isActiveFile(file) }"
                class="flex overflow-hidden justify-center items-center w-full rounded-lg ring-2 ring-offset-2 group aspect-video">
                <template x-if="file.type == 'folder'">
                    <svg class="w-20 h-20 fill-current text-[#fbd775]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 00-3-3h-3.879a.75.75 0 01-.53-.22L11.47 3.66A2.25 2.25 0 009.879 3H6a3 3 0 00-3 3v3.162A3.756 3.756 0 014.094 9h15.812zM4.094 10.5a2.25 2.25 0 00-2.227 2.568l.857 6A2.25 2.25 0 004.951 21H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-2.227-2.568H4.094z" /></svg>
                </template>

                <template x-if="file.type.startsWith('image')">
                    <img :src="storageURL + file.relative_path" class="object-cover w-full h-full pointer-events-none" />
                </template>

                <template x-if="!file.type.startsWith('image') && file.type != 'folder'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12"><path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" /><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" /></svg>
                </template>
            
                <button type="button" class="absolute inset-0 focus:outline-none">
                    <span class="sr-only" x-text="file.name"></span>
                </button>
            </div>
            <div class="flex justify-center items-center">
                <p :class="{ 'bg-indigo-600 text-white': isActiveFile(file), 'text-neutral-700' : !isActiveFile(file) }" class="block px-1 py-0.5 -mx-1 mt-2 text-sm font-medium truncate rounded-md pointer-events-none text-ellipsis" x-text="file.name"></p>
            </div>
        </li>
    </template>

    <template x-teleport="body">
        <div wire:ignore x-show="contextMenuOpen" @click.away="contextMenuOpen=false" x-ref="contextmenu" class="z-50 ctx min-w-[8rem] text-neutral-800 rounded-md border border-neutral-200/70 bg-white text-sm fixed p-1 shadow-md w-64" x-cloak>
            <div x-show="!contextMenuFileClicked" class="relative w-full">
                <div x-on:click="window.dispatchEvent(new CustomEvent('close-context-menu')); window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'create-folder-modal' }}));" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" /></svg>
                    <span>New Folder</span>
                </div>
                <div class="-mx-1 my-1 h-px bg-neutral-200"></div>
                <div class="relative group">
                    <div class="flex items-center px-2 py-1.5 pl-8 rounded cursor-default outline-none select-none hover:bg-blue-600 hover:text-white">
                        <svg class="absolute left-2 -mt-px w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.74902 15.3258C3.74902 14.4555 4.45451 13.75 5.32478 13.75H8.67387C9.54414 13.75 10.2496 14.4555 10.2496 15.3258V18.6742C10.2496 19.5445 9.54414 20.25 8.67387 20.25H5.32478C4.45451 20.25 3.74902 19.5445 3.74902 18.6742V15.3258Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.74902 5.32576C3.74902 4.45549 4.45451 3.75 5.32478 3.75H8.67387C9.54414 3.75 10.2496 4.45549 10.2496 5.32576V8.67424C10.2496 9.54451 9.54414 10.25 8.67387 10.25H5.32478C4.45451 10.25 3.74902 9.54451 3.74902 8.67424V5.32576Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.749 5.32576C13.749 4.45549 14.4545 3.75 15.3248 3.75H18.6739C19.5441 3.75 20.2496 4.45549 20.2496 5.32576V8.67424C20.2496 9.54451 19.5441 10.25 18.6739 10.25H15.3248C14.4545 10.25 13.749 9.54451 13.749 8.67424V5.32576Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M14.75 15L19.25 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M14.75 19L19.25 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                        <span>View</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto w-4 h-4"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                    <div data-submenu class="absolute top-0 right-0 invisible mr-1 opacity-0 duration-200 ease-out translate-x-full group-hover:mr-0 group-hover:visible group-hover:opacity-100">
                        <div class="z-50 min-w-[8rem] overflow-hidden rounded-md border bg-white p-1 shadow-md animate-in slide-in-from-left-1 w-48">
                            <div @click="contextMenuOpen=false" class="relative pl-8 flex cursor-default select-none items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white text-sm outline-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                                <svg class="absolute left-2 -mt-px w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.75 5h1.5m-1.5 7h1.5m-1.5 7h1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.75 5h11.5M8.75 19h11.5m-11.5-7h11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                <span>as List</span>
                            </div>
                            <div @click="contextMenuOpen=false" class="relative pl-8 flex cursor-default select-none items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white text-sm outline-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                                <svg class="absolute left-2 -mt-px w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.75 5.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.705 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.705-1.575-1.576V5.326z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.75 15.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.706 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.706-1.575-1.576v-3.348z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.75 5.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.705 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.705-1.575-1.576V5.326z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.75 15.326c0-.87.705-1.576 1.575-1.576h3.349c.87 0 1.576.706 1.576 1.576v3.348c0 .87-.706 1.576-1.576 1.576h-3.35c-.87 0-1.575-.706-1.575-1.576v-3.348z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                <span>as Icons</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="contextMenuFileClicked" class="relative w-full">
                <div x-show="active != null && active.type == 'folder'" x-on:click="window.dispatchEvent(new CustomEvent('close-context-menu')); window.dispatchEvent(new CustomEvent('go-to-active-directory'));" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M16.89 20.25H5.75a3 3 0 0 1-3-3V6.75a3 3 0 0 1 3-3H7.9a3 3 0 0 1 1.573.445l1.804 1.11a3 3 0 0 0 1.572.445h2.402a3 3 0 0 1 3 3v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.89 20.25a3 3 0 0 0 2.916-2.294l1.39-5.735a2 2 0 0 0-1.944-2.471h-9.24a3 3 0 0 0-2.885 2.176l-2.343 8.166" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                    <span>Open</span>
                </div>
                <div x-show="active != null && active.type != 'folder'" x-on:click="window.dispatchEvent(new CustomEvent('close-context-menu')); window.dispatchEvent(new CustomEvent('open-file-modal-active-file'));" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><line x1="80" y1="112" x2="144" y2="112" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="112" cy="112" r="80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="168.57" y1="168.57" x2="224" y2="224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="112" y1="80" x2="112" y2="144" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                    <span>View File</span>
                </div>
                <div class="-mx-1 my-1 h-px bg-neutral-200"></div>
                <div @click="contextMenuOpen=false" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4 stroke-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M20.2507 8.25V5.75C20.2507 4.64543 19.3553 3.75 18.2507 3.75H5.74902C4.64445 3.75 3.74902 4.64543 3.74902 5.75V8.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 20.25L12 3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.75 20.25L15.25 20.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                    <span>Rename</span>
                </div>
                <div x-on:click="contextMenuOpen=false; window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'move-file-modal' }}))" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4 stroke-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M20.25 7h-9.875a6.625 6.625 0 1 0 0 13.25h5.875m4-13.25l-3.5 3.25M20.25 7l-3.5-3.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                    <span>Move</span>
                </div>
                    
                <div @click="contextMenuOpen=false" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" /></svg>
                    <span>Duplicate</span>
                </div>

            
                <div class="-mx-1 my-1 h-px bg-neutral-200"></div>
                <div @click="contextMenuOpen=false" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4 stroke-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M7.75 7.757V6.75a3 3 0 0 1 3-3h6.5a3 3 0 0 1 3 3v6.5a3 3 0 0 1-3 3h-.992" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.75 10.75a3 3 0 0 1 3-3h6.5a3 3 0 0 1 3 3v6.5a3 3 0 0 1-3 3h-6.5a3 3 0 0 1-3-3v-6.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                    <span>Copy</span>
                </div>
                <div class="-mx-1 my-1 h-px bg-neutral-200"></div>
                <div x-on:click="contextMenuOpen=false; window.dispatchEvent(new CustomEvent('trigger-delete-action'));" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-red-600 hover:text-white text-red-600 outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    
                    <svg class="absolute left-2 -mt-px w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    <span>Delete</span>
                </div>
            </div>
        </div>
    </template>
</ul>