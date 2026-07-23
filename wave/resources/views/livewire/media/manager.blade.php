<x-filament-panels::page>
        <div class="flex overflow-hidden relative justify-start items-start w-full h-full bg-white rounded-xl border shadow-sm border-zinc-200/50">
            <div class="w-full h-full">
                <div x-data="{ 
                        active: @entangle('selectedFile'),
                        search: @entangle('search'),
                        searchResults: @entangle('searchResults'),
                        clientSideActive: false,
                        files: @entangle('files'), 
                        storageURL: @entangle('storageURL'),
                        activeFileDrawer: true,
                        fileOrFolderCopied: @entangle('fileOrFolderCopied'),
                        view: $persist('grid').as('media-view'),
                        searchFocused: false,
                        isActiveFile(file) {
                            if(!this.active){
                                return false;
                            }
                            
                            return this.active.relative_path == file.relative_path;
                        },
                        activeFileSelected() {
                            if(!this.active) {
                                return false;
                            }
                            return true;
                        },
                        setSelectedFileNull(){
                            $wire.setSelectedFile(null);
                            this.active=null;
                        },
                        getFullImagePath(){
                            if(this.active){
                                return this.storageURL + this.active.relative_path
                            } else {
                                return '';
                            }
                        },
                        handleKeydown(event) {
                            // Check if an input element is focused
                            if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
                                return; // Exit the function if an input is focused
                            }

                            if (event.code === 'Space' || event.keyCode === 32) {
                                event.preventDefault(); // Prevent page scroll
                                window.dispatchEvent(new CustomEvent('open-file-modal', { detail: { file: this.active }}));

                            } else if (event.code === 'Delete' || event.keyCode === 46 || event.code === 'Backspace' || event.keyCode === 8) {
                                event.preventDefault();
                                console.log('Delete key pressed');
                                // Your delete key action here
                                if (this.activeFileSelected()) {
                                    $wire.triggerDeleteAction();
                                }
                            } else if (event.code === 'Escape' || event.keyCode === 27){
                                window.dispatchEvent(new CustomEvent('close-file-modal'));
                            }
                        },
                        renameActive(){
                            if(this.active){
                                document.getElementById('active-file').dispatchEvent(new CustomEvent('rename'));
                            }
                        },
                        handleFileDoubleClick(file){
                            if(file.type == 'folder'){
                                this.goToActiveDirectory();
                            } else {
                                window.dispatchEvent(new CustomEvent('open-file-modal', { detail: { file: file }}));
                            }
                        },
                        goToActiveDirectory(){
                            $wire.goToDirectory(this.active.relative_path)
                        },
                        setActiveFileBasedOnName(name){
                            let that = this;
                            setTimeout(function(){
                                that.files.forEach(file => {
                                    if (file.name === name) {
                                        that.active = file;
                                    }
                                });
                            }, 1);
                        },
                        isUploading: false, 
                        progress: 0
                    }"
                    x-init="
                        $watch('active', function(value){
                            if(value == null){
                                clientSideActive = false;
                            } else {
                                clientSideActive = true;
                            }
                        })
                    "
                    @set-active-file.window="setActiveFileBasedOnName($event.detail.name)"
                    @go-to-active-directory.window="goToActiveDirectory()"
                    @duplicate.window="$wire.duplicate()"
                    @copy.window="$wire.copy()"
                    @paste.window="$wire.paste()"
                    @rename-active.window="renameActive()"
                    x-on:keydown.window="handleKeydown"
                    @trigger-delete-action.window="$wire.triggerDeleteAction"
                    class="flex flex-col justify-start items-center w-full h-full bg-white">
                    
                    <div class="relative p-5 pb-0 w-full bg-white border-b border-zinc-200/70">
                        @include('wave::media.views.header')
                        @include('wave::media.views.breadcrumbs')
                    </div>

                    <div class="flex justify-start items-start w-full h-full bg-white">
                        @include('wave::media.views.files')
                        @include('wave::media.views.active-file')
                    </div>
                    @include('wave::media.views.full-screen-file-modal')
                </div>
            </div>
            
        <x-filament-actions::modals />
        </div>
    
</x-filament-panels::page>
