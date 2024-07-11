
<div 
    x-data="{ 
        contextMenuOpen: false,
        contextMenuFileClicked: false,
        draggedItem: null,
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
    :class="{ 
        'grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5' : activeFileDrawer && view == 'grid', 
        'grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6' : !activeFileDrawer && view == 'grid',
        'grid overflow-hidden gap-x-4 gap-y-8 p-4 w-full h-full sm:p-6 xl:p-8 sm:gap-x-6 xl:gap-x-8' : view == 'grid',
        'w-full flex flex-col' : view == 'list'
    }"
     x-on:click="setSelectedFileNull(); closeContentMenuIfOpen($event)">
    <template x-for="file in files" :key="file.relative_path">
        <div 
            :class="{ 
                'bg-indigo-600 text-white' : view == 'list' && isActiveFile(file),
                'flex px-4' : view == 'list',
                'odd:bg-zinc-50' : view == 'list' && !isActiveFile(file),
                'rounded-lg' : view == 'grid' }"
            class="relative z-10 group" 
            draggable="true" 
            x-on:dragstart="draggedItem = file; active=file;" 
            x-on:dragover.prevent="if ($event.target !== draggedItem) $el.classList.add('dragover')"
            x-on:dragleave="$el.classList.remove('dragover')"
            x-on:dragend="draggedItem = null;"
            x-on:drop="if(file.type == 'folder'){ $wire.moveSelectedFileIntoFolder(file); } $el.classList.remove('dragover')"
            x-on:click="$wire.setSelectedFile(file); active=file; $event.stopPropagation(); closeContentMenuIfOpen($event);" 
            x-on:contextmenu="$wire.setSelectedFile(file); active=file; contextMenuFileClicked=true; $event.stopPropagation(); openContextMenu($event);" 
            x-on:dblclick="handleFileDoubleClick(file);"
        >
            <div 
                :class="{ 
                    'border-indigo-500 bg-zinc-50': isActiveFile(file) && view == 'grid', 
                    'border-gray-200' : !isActiveFile(file) && view == 'grid',
                    'flex overflow-hidden relative justify-center group-[&.dragover]:bg-zinc-100 group-[&.dragover]:border-indigo-500 items-center w-auto rounded-lg border-2 aspect-video' : view == 'grid',
                    'w-10 h-10 flex-shrink-0 p-2' : view == 'list'
                }"
                class="relative group">
                <template x-if="file.type == 'folder'">
                    <svg 
                        :class="{ 
                            'w-20 h-20 fill-current text-[#fbd775]' : view == 'grid',
                            'w-full h-full flex-shrink-0 fill-current text-[#fbd775]' : view == 'list'
                        }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 00-3-3h-3.879a.75.75 0 01-.53-.22L11.47 3.66A2.25 2.25 0 009.879 3H6a3 3 0 00-3 3v3.162A3.756 3.756 0 014.094 9h15.812zM4.094 10.5a2.25 2.25 0 00-2.227 2.568l.857 6A2.25 2.25 0 004.951 21H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-2.227-2.568H4.094z" /></svg>
                </template>

                <template x-if="file.type.startsWith('image')">
                    <img :src="file.url" 
                        :class="{ 'object-cover absolute w-auto max-w-full h-auto max-h-full pointer-events-none' : view == 'grid', 'h-full w-full object-contain rounded-md' : view == 'list' }" />
                </template>

                <template x-if="file.type.endsWith('pdf')">
                    <svg :class="{ 'w-12 h-12 fill-current' : view == 'grid', 'w-full h-full flex-shrink-0 p-0.5 fill-current' : view == 'list' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M44,120H212a4,4,0,0,0,4-4V88a8,8,0,0,0-2.34-5.66l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40v76A4,4,0,0,0,44,120ZM152,44l44,44H152Zm72,108.53a8.18,8.18,0,0,1-8.25,7.47H192v16h15.73a8.17,8.17,0,0,1,8.25,7.47,8,8,0,0,1-8,8.53H192v15.73a8.17,8.17,0,0,1-7.47,8.25,8,8,0,0,1-8.53-8V152a8,8,0,0,1,8-8h32A8,8,0,0,1,224,152.53ZM64,144H48a8,8,0,0,0-8,8v55.73A8.17,8.17,0,0,0,47.47,216,8,8,0,0,0,56,208v-8h7.4c15.24,0,28.14-11.92,28.59-27.15A28,28,0,0,0,64,144Zm-.35,40H56V160h8a12,12,0,0,1,12,13.16A12.25,12.25,0,0,1,63.65,184ZM128,144H112a8,8,0,0,0-8,8v56a8,8,0,0,0,8,8h15.32c19.66,0,36.21-15.48,36.67-35.13A36,36,0,0,0,128,144Zm-.49,56H120V160h8a20,20,0,0,1,20,20.77C147.58,191.59,138.34,200,127.51,200Z"/></svg>
                </template>

                <template x-if="file.type.endsWith('zip')">
                    <svg :class="{ 'w-12 h-12 fill-current' : view == 'grid', 'w-full h-full flex-shrink-0 p-0.5 fill-current' : view == 'list' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M184,144H168a8,8,0,0,0-8,8v55.73a8.17,8.17,0,0,0,7.47,8.25,8,8,0,0,0,8.53-8v-8h7.4c15.24,0,28.14-11.92,28.59-27.15A28,28,0,0,0,184,144Zm-.35,40H176V160h8A12,12,0,0,1,196,173.16,12.25,12.25,0,0,1,183.65,184ZM136,152v55.73a8.17,8.17,0,0,1-7.47,8.25,8,8,0,0,1-8.53-8V152.27a8.17,8.17,0,0,1,7.47-8.25A8,8,0,0,1,136,152ZM96,208.53A8.17,8.17,0,0,1,87.73,216H56.23a8.27,8.27,0,0,1-6-2.5A8,8,0,0,1,49.05,204l25.16-44H56.27A8.17,8.17,0,0,1,48,152.53,8,8,0,0,1,56,144H87.77a8.27,8.27,0,0,1,6,2.5A8,8,0,0,1,95,156L69.79,200H88A8,8,0,0,1,96,208.53ZM213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40v76a4,4,0,0,0,4,4H212a4,4,0,0,0,4-4V88A8,8,0,0,0,213.66,82.34ZM152,88V44l44,44Z"/></svg>
                </template>

                <template x-if="file.type.endsWith('wav') || file.type.endsWith('mp3') || file.type.endsWith('m4a')">
                    <svg :class="{ 'w-12 h-12 fill-current' : view == 'grid', 'w-full h-full flex-shrink-0 p-0.5 fill-current' : view == 'list' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M152,180a40.55,40.55,0,0,1-20,34.91A8,8,0,0,1,124,201.09a24.49,24.49,0,0,0,0-42.18A8,8,0,0,1,132,145.09,40.55,40.55,0,0,1,152,180ZM99.06,128.61a8,8,0,0,0-8.72,1.73L68.69,152H48a8,8,0,0,0-8,8v40a8,8,0,0,0,8,8H68.69l21.65,21.66A8,8,0,0,0,104,224V136A8,8,0,0,0,99.06,128.61ZM216,88V216a16,16,0,0,1-16,16H168a8,8,0,0,1,0-16h32V96H152a8,8,0,0,1-8-8V40H56v80a8,8,0,0,1-16,0V40A16,16,0,0,1,56,24h96a8,8,0,0,1,5.66,2.34l56,56A8,8,0,0,1,216,88Zm-56-8h28.69L160,51.31Z"/></svg>
                </template>

                <template x-if="file.type.endsWith('mp4') || file.type.endsWith('quicktime') || file.type.endsWith('avi') || file.type.endsWith('wmv') || file.type.endsWith('webm') || file.type.endsWith('ogg') || file.type.endsWith('mpeg') || file.type.endsWith('mpg') || file.type.endsWith('m4v')">
                    <svg :class="{ 'w-12 h-12 fill-current' : view == 'grid', 'w-full h-full flex-shrink-0 p-0.5 fill-current' : view == 'list' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><polyline points="152 32 152 88 208 88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M192,224h8a8,8,0,0,0,8-8V88L152,32H56a8,8,0,0,0-8,8v72" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><rect x="40" y="152" width="80" height="64" rx="8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><rect x="40" y="152" width="80" height="64" rx="8"/><polyline points="120 172 152 152 152 216 120 196" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                </template>

                <template x-if="!file.type.startsWith('image') && file.type != 'folder' && !file.type.endsWith('pdf') && !file.type.endsWith('zip') && !file.type.endsWith('wav') && !file.type.endsWith('mp3') && !file.type.endsWith('m4a') && !file.type.endsWith('mp4') && !file.type.endsWith('quicktime') && !file.type.endsWith('avi') && !file.type.endsWith('wmv') && !file.type.endsWith('webm') && !file.type.endsWith('ogg') && !file.type.endsWith('mpeg') && !file.type.endsWith('mpg') && !file.type.endsWith('m4v')">
                    <svg :class="{ 'w-12 h-12' : view == 'grid', 'w-full h-full flex-shrink-0 p-0.5' : view == 'list' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" /><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" /></svg>
                </template>

            
                <button type="button" class="absolute inset-0 cursor-default focus:outline-none">
                    <span class="sr-only" x-text="file.name"></span>
                </button>
            </div>
            <div
                x-data="{
                    changeName: false,
                        textWidth: 0,
                        minWidth: 0,
                        measureText() {
                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d');
                            const styles = window.getComputedStyle(this.$el);
                            
                            context.font = `${styles.fontWeight} ${styles.fontSize} ${styles.fontFamily}`;
                            
                            const metrics = context.measureText(file.name || this.$el.placeholder);
                            const paddingX = parseFloat(styles.paddingLeft) + parseFloat(styles.paddingRight);
                            const borderX = parseFloat(styles.borderLeftWidth) + parseFloat(styles.borderRightWidth);
                            
                            this.textWidth = Math.max(this.minWidth, Math.ceil(metrics.width) + paddingX + borderX);
                            
                            this.$el.style.width = `${this.textWidth}px`;
                        },
                        saveFilenameChange(){
                            if (file.name !== file.original_name) {
                                $wire.rename();
                            }
                            this.changeName=false; 
                        },
                        changeNameAndFocus(){
                            this.changeName=true; 
                            let that = this;
                            setTimeout(function(){ that.$refs.input.focus() }, 10);
                        }
                    }"
                    x-init="
                        $watch('changeName', function(value){
                            console.log('changer' + value);
                        });
                    "
                    @rename="changeNameAndFocus()"
                    :id="isActiveFile(file) ? 'active-file' : null"
                    class="flex relative md:max-w-[300px] max-w-[200px] xl:max-w-[400px] justify-center items-center"
                    >
                <p  x-show="!changeName" x-on:click="if(active && active.name == file.name){ changeNameAndFocus()  }"  
                        :class="{ 
                            'bg-indigo-600 text-white': isActiveFile(file), 
                            'text-neutral-700' : !isActiveFile(file),
                            'mt-2' : view == 'grid' 
                        }" class="group-[&.dragover]:bg-indigo-600 group-[&.dragover]:text-white block relative px-1 py-0.5 text-sm font-medium truncate rounded-md cursor-default text-ellipsis" x-text="file.name"></p>
                <div x-show="changeName" class="flex relative justify-center items-center w-full">
                    <input 
                        x-on:keydown.enter="$el.blur()"
                        x-on:blur="saveFilenameChange()"
                        x-model="file.name"
                        x-init="measureText"
                        @input="measureText"
                        @resize.window="measureText"
                        x-ref="input"
                        type="text"
                        :placeholder="file.name"
                        :class="{ 'mt-2' : view == 'grid' }"
                        class="px-1 py-0.5 text-sm rounded-md border border-blue-500 shadow-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :style="`width: ${textWidth}px; min-width: ${minWidth}px;`"
                    >
                </div>
         
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div wire:ignore x-show="contextMenuOpen" x-on:click.away="contextMenuOpen=false" x-ref="contextmenu" class="ctx min-w-[8rem] text-neutral-800 rounded-md border border-neutral-200/70 bg-white text-sm fixed p-1 shadow-md w-64 z-[99]" x-cloak>
            <div x-show="!contextMenuFileClicked" class="relative w-full">
                <div x-on:click="window.dispatchEvent(new CustomEvent('close-context-menu')); window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'create-folder-modal' }}));" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" /></svg>
                    <span>New Folder</span>
                </div>
                <div x-show="fileOrFolderCopied" x-on:click="contextMenuOpen=false;  window.dispatchEvent(new CustomEvent('paste'))" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    <span>Paste Item</span>
                </div>
                
                <div class="-mx-1 my-1 h-px bg-neutral-200"></div>
                <div x-on:click="window.dispatchEvent(new CustomEvent('close-context-menu')); document.getElementById('upload-button').click()" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M12.0005 11.7495L12.0005 20.2495M12.0005 11.7495L15.2505 15.2495M12.0005 11.7495L8.75049 15.2495" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M4.25 15.25C2.96461 14.2882 2.75 13.1762 2.75 12C2.75 9.94957 4.20204 8.23828 6.13392 7.83831C7.01365 5.45184 9.30808 3.75 12 3.75C15.3711 3.75 18.1189 6.41898 18.2454 9.75913C19.9257 9.8846 21.25 11.2876 21.25 13C21.25 14.0407 20.5 15 19.75 15.25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                    <span>Upload File</span>
                </div>
                <div class="relative group">
                    <div class="flex items-center px-2 py-1.5 pl-8 rounded cursor-default outline-none select-none hover:bg-blue-600 hover:text-white">
                        <svg class="absolute left-2 -mt-px w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.74902 15.3258C3.74902 14.4555 4.45451 13.75 5.32478 13.75H8.67387C9.54414 13.75 10.2496 14.4555 10.2496 15.3258V18.6742C10.2496 19.5445 9.54414 20.25 8.67387 20.25H5.32478C4.45451 20.25 3.74902 19.5445 3.74902 18.6742V15.3258Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.74902 5.32576C3.74902 4.45549 4.45451 3.75 5.32478 3.75H8.67387C9.54414 3.75 10.2496 4.45549 10.2496 5.32576V8.67424C10.2496 9.54451 9.54414 10.25 8.67387 10.25H5.32478C4.45451 10.25 3.74902 9.54451 3.74902 8.67424V5.32576Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.749 5.32576C13.749 4.45549 14.4545 3.75 15.3248 3.75H18.6739C19.5441 3.75 20.2496 4.45549 20.2496 5.32576V8.67424C20.2496 9.54451 19.5441 10.25 18.6739 10.25H15.3248C14.4545 10.25 13.749 9.54451 13.749 8.67424V5.32576Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M14.75 15L19.25 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M14.75 19L19.25 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                        <span>View</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto w-4 h-4"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                    <div data-submenu class="absolute top-0 right-0 invisible mr-1 opacity-0 duration-200 ease-out translate-x-full group-hover:mr-0 group-hover:visible group-hover:opacity-100">
                        <div class="min-w-[8rem] overflow-hidden rounded-md border bg-white p-1 shadow-md animate-in slide-in-from-left-1 w-48">
                            <div x-on:click="contextMenuOpen=false" class="relative pl-8 flex cursor-default select-none items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white text-sm outline-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                                <svg class="absolute left-2 -mt-px w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.75 5h1.5m-1.5 7h1.5m-1.5 7h1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.75 5h11.5M8.75 19h11.5m-11.5-7h11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                <span>as List</span>
                            </div>
                            <div x-on:click="contextMenuOpen=false" class="relative pl-8 flex cursor-default select-none items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white text-sm outline-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
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
                <div x-show="fileOrFolderCopied" x-on:click="contextMenuOpen=false; window.dispatchEvent(new CustomEvent('paste'))" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    <span>Paste Item</span>
                </div>
                <div class="-mx-1 my-1 h-px bg-neutral-200"></div>
                <div x-on:click="contextMenuOpen=false; window.dispatchEvent(new CustomEvent('rename-active'))" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4 stroke-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M20.2507 8.25V5.75C20.2507 4.64543 19.3553 3.75 18.2507 3.75H5.74902C4.64445 3.75 3.74902 4.64543 3.74902 5.75V8.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 20.25L12 3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.75 20.25L15.25 20.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                    <span>Rename</span>
                </div>
                <div x-on:click="contextMenuOpen=false; window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'move-file-modal' }}))" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4 stroke-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M20.25 7h-9.875a6.625 6.625 0 1 0 0 13.25h5.875m4-13.25l-3.5 3.25M20.25 7l-3.5-3.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                    <span>Move</span>
                </div>
                    
                <div x-on:click="contextMenuOpen=false; window.dispatchEvent(new CustomEvent('duplicate'))" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
                    <svg class="absolute left-2 -mt-px w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" /></svg>
                    <span>Duplicate</span>
                </div>

            
                <div class="-mx-1 my-1 h-px bg-neutral-200"></div>
                <div x-on:click="contextMenuOpen=false; window.dispatchEvent(new CustomEvent('copy'))" class="relative flex cursor-default select-none group items-center rounded px-2 py-1.5 hover:bg-blue-600 hover:text-white outline-none pl-8  data-[disabled]:opacity-50 data-[disabled]:pointer-events-none">
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
</div>