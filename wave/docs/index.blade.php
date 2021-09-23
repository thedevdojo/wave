@php
    include base_path().'/wave/docs/load.php';
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="/wave/docs/assets/css/app.css">
    <style>.algolia-autocomplete{width:100%}</style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.6.0/styles/night-owl.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.6.0/highlight.min.js"></script>

    <!-- at the end of the HEAD -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.css" />


</head>

<body>

    <div class="absolute inset-0 z-10 flex items-center justify-center w-full h-full bg-white"><svg class="w-5 h-5 text-gray-900 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
    <!-- Full page fadeout -->
    <div class="fixed inset-0 z-50 w-screen h-screen transition duration-200 ease-in bg-white" x-data="{ backToSite: true }" x-init="() => { $el.classList.add('opacity-0'); setTimeout(function(){ $el.remove() }, 200); }"></div>

    <!-- Mobile Header -->
    <div class="relative z-10 flex items-center justify-center block h-20 px-10 mb-10 border-b border-gray-100 cursor-pointer md:px-24 lg:hidden">
        <svg onclick="openSidebar()" class="absolute left-0 w-6 h-6 ml-10 text-gray-500 cursor-pointer md:ml-20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path></svg>
        <svg class="w-9 h-9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 208 206"><defs></defs><defs><linearGradient id="a" x1="100%" x2="0%" y1="45.596%" y2="45.596%"><stop offset="0%" stop-color="#5D63FB"></stop><stop offset="100%" stop-color="#0769FF"></stop></linearGradient><linearGradient id="b" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stop-color="#39BEFF"></stop><stop offset="100%" stop-color="#0769FF"></stop></linearGradient><linearGradient id="c" x1="0%" x2="99.521%" y1="50%" y2="50%"><stop offset="0%" stop-color="#38BCFF"></stop><stop offset="99.931%" stop-color="#91D8FF"></stop></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="url(#a)" d="M185.302 38c14.734 18.317 22.742 41.087 22.698 64.545C208 159.68 161.43 206 103.986 206c-39.959-.01-76.38-22.79-93.702-58.605C-7.04 111.58-2.203 69.061 22.727 38a104.657 104.657 0 00-9.283 43.352c0 54.239 40.55 98.206 90.57 98.206 50.021 0 90.571-43.973 90.571-98.206A104.657 104.657 0 00185.302 38z"></path><path fill="url(#b)" d="M105.11 0A84.144 84.144 0 01152 14.21C119.312-.651 80.806 8.94 58.7 37.45c-22.105 28.51-22.105 68.58 0 97.09 22.106 28.51 60.612 38.101 93.3 23.239-30.384 20.26-70.158 18.753-98.954-3.75-28.797-22.504-40.24-61.021-28.47-95.829C36.346 23.392 68.723.002 105.127.006L105.11 0z"></path><path fill="url(#c)" d="M118.98 13c36.39-.004 66.531 28.98 68.875 66.234 2.343 37.253-23.915 69.971-60.006 74.766 29.604-8.654 48.403-38.434 43.99-69.685-4.413-31.25-30.678-54.333-61.459-54.014-30.78.32-56.584 23.944-60.38 55.28v-1.777C49.99 44.714 80.872 13.016 118.98 13z"></path></g></svg>
    </div>
    <!-- End Mobile Header -->

    <div class="relative z-20 flex h-screen antialiased bg-white docs">

        <div id="sidebarOverlay" class="fixed inset-0 hidden w-full h-full bg-black opacity-30">
            <div class="fixed top-0 right-0 flex items-center justify-center w-12 h-12 mt-4 mr-5 bg-white rounded-full cursor-pointer">
                <svg class="w-6 h-6 text-black fill-current" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
        </div>
        {{-- sidebar --}}
        <div id="sidebar" class="fixed top-0 left-0 z-40 flex flex-col w-64 h-screen py-4 pr-3 overflow-scroll transition duration-200 ease-out transform -translate-x-full bg-white border-r border-gray-200 select-none lg:translate-x-0 categories">
            <h1 class="px-5 text-sm font-bold text-black">Wave<span class="ml-1 text-xs font-medium text-blue-500 uppercase">docs</span></h1>
            <div id="bg-fade" class="fixed inset-0 z-40 invisible w-screen h-full transition duration-150 ease-out bg-gray-900 opacity-0"></div>
            <div class="relative z-50 flex items-center w-full pl-4 mt-5 mb-5 text-gray-400">
                <svg class="absolute z-20 w-4 h-4 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" onfocus="window.searchFocused(true)" onblur="window.searchFocused(false)" class="block search w-full z-10 py-1.5 pt-2 w-full pl-8 pr-4 leading-normal rounded-md text-xs focus:border-transparent focus:outline-none focus:ring-4 border border-gray-100 focus:ring-blue-500 focus:ring-opacity-40 bg-gray-50 text-gray-400" placeholder="Search the docs">
            </div>

            @foreach($menu_items as $item)
                {{-- if the current page is in this menu we want to have this drawer open --}}
                @php $isOpen = false; @endphp
                @foreach($item->sections as $index => $section)
                    @if(Request::getRequestUri() && Request::getRequestUri() == $section->url)
                        @php $isOpen = true; @endphp
                    @endif
                @endforeach
                <div x-data="{ open: '{{ $isOpen }}' }" class="relative text-xs font-semibold">
                    <div @click="open=!open" class="flex justify-between py-2 pl-5 pr-3 transition duration-150 ease-in-out rounded-r-lg cursor-pointer hover:bg-gray-50 group hover:text-gray-800">
                        <div class="text-gray-700 uppercase">{{ $item->title }}</div>
                        <svg class="w-4 h-4 text-gray-400 transform group-hover:text-gray-800" :class="{ '-rotate-180' : open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <div x-show="open" class="mt-2 space-y-0.5" x-cloak>
                        @foreach($item->sections as $index => $section)
                            <a href="{{ $section->url }}" @if(isset($section->attributes)){!! $section->attributes !!}@endif class="block cursor-pointer rounded-r-lg pl-6 py-2 @if(Request::getRequestUri() && Request::getRequestUri() == $section->url){{ 'bg-blue-50 text-blue-500' }}@else{{ 'text-gray-400 hover:text-gray-800' }}@endif">{{ $section->title }}</a>
                            @if(intval($index+1) >= count((array)$item->sections))
                                <div class="w-full h-5"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach


            <div class="relative flex flex-col items-start justify-between mt-auto">

                <a href="https://devdojo.com/course/wave" target="_blank" class="flex items-center justify-between w-full py-2 pl-5 pr-3 transition duration-150 ease-in-out rounded-r-lg cursor-pointer hover:bg-wave-100 bg-wave-50 group hover:text-gray-800">
                    <span class="flex items-center">🍿
                        <span class="ml-2 text-sm font-medium text-wave-500">Video Tutorials</span>
                    </span>
                    <svg class="w-4 h-4 ml-1 align-end text-wave-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path></svg>
                </a>

                <a id="backToSiteBtn" href="{{ url('/') }}" class="flex items-center pl-5 mt-4 text-xs font-bold text-blue-500 no-underline hover:text-black">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to My Site
                </a>
            </div>
        </div>
        {{-- end sidebar --}}



        <div class="w-full px-10 md:px-20 lg:ml-64 xl:px-0">
            @if($home)
                <div class="w-full max-w-4xl mx-auto">
                    <div class="hidden max-w-lg lg:mt-8 lg:-mb-8 lg:block">
                        <img src="../../wave/img/docs/2.0/wave-docs.png" class="w-full mb-0 ml-0">
                    </div>
                </div>
            @endif
            <div class="max-w-4xl py-0 pb-20 mx-auto prose prose-xl lg:pt-10 lg:prose-xl lg:px-0">
                {!! Illuminate\Support\Str::markdown($file) !!}
            </div>
        </div>

    </div>

    <script src="/wave/docs/assets/js/app.js"></script>
    <!-- at the end of the BODY -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.js"></script>
    <script type="text/javascript"> docsearch({
        apiKey: '57b9a8aca979f9716f86aa3d2b75a415',
        indexName: 'devdojo',
        inputSelector: '.search',
        debug: false // Set debug to true if you want to inspect the dropdown
        });
    </script>

    <script>
        hljs.initHighlightingOnLoad();
        function openSidebar(){
            document.getElementById('sidebar').classList.remove('-translate-x-full')
            document.getElementById('sidebarOverlay').classList.remove('hidden');
        }

        function closeSidebar(){
            document.getElementById('sidebar').classList.add('-translate-x-full')
            document.getElementById('sidebarOverlay').classList.add('hidden');
        }

        window.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('sidebarOverlay').addEventListener('click', function(){
                closeSidebar();
            });
        });
    </script>

</body>
</html>
