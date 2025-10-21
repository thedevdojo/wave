@if ($paginator->hasPages())
    <div>
        <span class="relative z-0 inline-flex shadow-sm">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <div class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border rounded-l-md text-zinc-400 border-zinc-300 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border rounded-l-md text-zinc-700 border-zinc-300 hover:text-zinc-800 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 bg-white border text-zinc-700 border-zinc-300">
                    ...
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <div class="relative items-center hidden px-4 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border text-zinc-400 border-zinc-300 md:inline-flex focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-700">
                                {{ $page }}
                            </div>
                        @else
                            <a href="{{ $url }}" class="relative items-center hidden px-4 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border text-zinc-700 border-zinc-300 md:inline-flex hover:text-zinc-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-700">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border rounded-r-md text-zinc-700 border-zinc-300 hover:text-zinc-800 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @else
                <div class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border rounded-r-md text-zinc-500 border-zinc-300 hover:text-zinc-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            @endif
        </span>
    </div>
@endif
