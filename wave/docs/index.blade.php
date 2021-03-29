@php
    include base_path().'/wave/docs/load.php';
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/wave/docs/assets/css/app.css">
    <style>.algolia-autocomplete{width:100%}</style>

    <!-- Before the closing </head> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsearch.js@2.6.3/dist/cdn/docsearch.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.6.0/styles/night-owl.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.6.0/highlight.min.js"></script>


</head>

<body>

    <!-- Full page fadeout -->
    <div class="fixed inset-0 z-50 w-screen h-screen transition duration-200 ease-in bg-white" x-data="{ backToSite: true }" x-init="() => { $el.classList.add('opacity-0'); setTimeout(function(){ $el.remove() }, 200); }"></div>

    <div class="flex h-screen antialiased bg-white docs">

        {{-- sidebar --}}
        <div class="fixed top-0 left-0 hidden w-64 h-screen py-4 pr-3 overflow-y-auto bg-white border-r border-gray-200 select-none categories lg:flex lg:flex-col">
            <h1 class="px-5 text-sm font-bold text-black">Wave<span class="ml-1 text-xs font-medium text-blue-500 uppercase">docs</span></h1>

            <div class="relative flex items-center w-full pl-4 mt-5 mb-5 text-gray-400">
                <svg class="absolute z-20 w-4 h-4 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" class="block search w-full z-10 py-1.5 pt-2 w-full pl-8 pr-4 leading-normal rounded-md text-xs focus:border-transparent focus:outline-none focus:ring-4 border border-gray-100 focus:ring-blue-500 focus:ring-opacity-40 bg-gray-50 text-gray-400" placeholder="Search the docs">
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


            <div class="flex flex-col items-start justify-between mt-auto">

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



        <div class="w-full ml-64">
            @if($home)
                <div class="w-full max-w-4xl mx-auto">
                    <div class="max-w-lg mt-8 -mb-8">
                        <img src="../../wave/img/docs/2.0/wave-docs.png" class="w-full mb-0 ml-0">
                    </div>
                </div>
            @endif
            <div class="max-w-4xl px-5 py-0 pb-20 mx-auto prose prose-xl lg:pt-10 lg:prose-xl lg:px-0">
                {!! Illuminate\Support\Str::markdown($file) !!}
            </div>
        </div>

    </div>

    <script src="/wave/docs/assets/js/app.js"></script>
    <!-- Before the closing </body> -->
    <script src="https://cdn.jsdelivr.net/npm/docsearch.js@2.6.3/dist/cdn/docsearch.min.js"></script>
    <script>
    docsearch({
        // Your apiKey and indexName will be given to you once
        // we create your config
        apiKey: 'f0f57285cd570a2c59a70087c0d3ed69',
        indexName: 'media',
        appId: 'XH97EKBGNS',
        //appId: '<APP_ID>', // Should be only included if you are running DocSearch on your own.
        // Replace inputSelector with a CSS selector
        // matching your search input
        inputSelector: '.search',
        // Set debug to true to inspect the dropdown
        debug: false,
    });
    </script>
    <script>hljs.initHighlightingOnLoad();</script>

    <script>

        // window.onbeforeunload = function() {
        //     var myDiv = document.createElement("div");
        //     myDiv.className = 'fixed inset-0 z-50 w-screen h-screen transition duration-200 ease-out bg-white opacity-0';
        //     document.body.appendChild(myDiv);
        //     console.log('woah');

        //     setTimeout(function(){
        //         myDiv.classList.remove('opacity-0');
        //     }, 10);
        // };

    </script>

</body>
</html>
