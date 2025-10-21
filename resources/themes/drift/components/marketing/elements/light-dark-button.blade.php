<div 
    x-data="{
        theme: 'light',
        toggle() {
            if(this.theme == 'dark'){ 
                this.theme = 'light';
                localStorage.setItem('theme', 'light');
            }else{ 
                this.theme = 'dark';
                localStorage.setItem('theme', 'dark');
            }
        }
    }"
    x-init="
        if(localStorage.getItem('theme')){
            theme = localStorage.getItem('theme');
        }
        if(theme=='system'){
            theme =  'light';
        }
        if(document.documentElement.classList.contains('dark')){ theme='dark'; }
        $watch('theme', function(value){
            if(value == 'dark'){
                document.documentElement.classList.add('dark');
        } else {
                document.documentElement.classList.remove('dark');
            }
        })
    "
    x-on:click="toggle()"
    class="relative"
    x-cloak
>

    <input type="hidden" name="toggleDarkMode" :value="theme">
 
    <button
        x-ref="toggle"
        type="button"
        :aria-checked="theme == 'dark'"
        :aria-labelledby="$id('toggle-label')"
        :class="(theme == 'dark') ? 'bg-black border-transparent md:border-gray-800 hover:bg-gray-900' : 'border-transparent md:border-gray-200 hover:bg-gray-50'"
        class="relative flex flex-col items-center justify-center flex-shrink-0 w-8 h-8 py-1 ml-1 overflow-hidden text-gray-700 border rounded-md md:w-7 md:h-7 group dark:text-white focus:ring-0"
    >   
        <span :class="{ 'opacity-100 group-hover:-translate-y-full group-hover:opacity-0' : theme == 'light', 'opacity-0 absolute translate-y-full group-hover:translate-y-0 group-hover:opacity-100' : theme != 'light' }" class="absolute flex-shrink-0 w-4 h-4 duration-300 ease-out">
            <x-phosphor-sun class="w-full h-full" />
        </span>
        <span :class="{ 'opacity-100 group-hover:-translate-y-full group-hover:opacity-0' : theme == 'dark', 'opacity-0 absolute translate-y-full group-hover:translate-y-0 group-hover:opacity-100' : theme != 'dark' }" class="absolute flex-shrink-0 w-4 h-4 duration-300 ease-out">
            <x-phosphor-moon class="w-full h-full" />
        </span>
    </button>

</div>