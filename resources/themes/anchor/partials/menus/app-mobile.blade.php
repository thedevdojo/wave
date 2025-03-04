<div x-show="mobileMenuOpen" x-transition:enter="duration-300 ease-out scale-100" x-transition:enter-start="opacity-50 scale-110" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition duration-75 ease-in scale-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-100" class="absolute inset-x-0 top-0 transition transform origin-top md:hidden">
    <div class="rounded-lg shadow-lg">
        <div class="bg-white rounded-lg divide-y-2 shadow-xs divide-zinc-50">
            <div class="px-8 pt-6 pb-8 space-y-6">
                <div class="flex justify-between items-center mt-1">
                    <div>
                        <svg viewBox="0 0 159 140" class="w-8 h-8" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient x1="27.743%" y1="20.907%" x2="82.132%" y2="59.652%" id="a">
                                    <stop stop-color="#0535AB" offset="0%" />
                                    <stop stop-color="#0539AE" stop-opacity=".93" offset="12%" />
                                    <stop stop-color="#0642B5" stop-opacity=".73" offset="35%" />
                                    <stop stop-color="#0752C1" stop-opacity=".42" offset="65%" />
                                    <stop stop-color="#0867D1" stop-opacity="0" offset="100%" />
                                </linearGradient>
                                <linearGradient x1="36.985%" y1="37.014%" x2="61.742%" y2="55.707%" id="b">
                                    <stop stop-color="#0867D1" offset="0%" />
                                    <stop stop-color="#096DD4" stop-opacity=".94" offset="10%" />
                                    <stop stop-color="#0B7CDB" stop-opacity=".78" offset="29%" />
                                    <stop stop-color="#0E96E6" stop-opacity=".52" offset="55%" />
                                    <stop stop-color="#12B8F6" stop-opacity=".17" offset="86%" />
                                    <stop stop-color="#14C9FE" stop-opacity="0" offset="100%" />
                                </linearGradient>
                            </defs>
                            <g fill-rule="nonzero" fill="none">
                                <path d="M86.24 56.02l3.49-3c30.11-25.54 60.59-31.2 66.26-12.82 5.76 30.19-38.94 34.48-69.75 15.82z" fill="#0535AB" />
                                <path d="M155.84 39.34c.06.29.11.59.15.88 4 27.35-36.74 29.53-69.76 15.78C43.53 38.21 46.8-17.51 21.94 6.13c0 0-15.19 15.15-20.3 40.44a74.25 74.25 0 001.15 32.77v.05c.07.29.14.57.22.86v.08c.6 2.31 1.32 4.58 2.13 6.82A79.07 79.07 0 00131.44 120c22.992-19.942 32.483-51.318 24.4-80.66z" fill="#0069FF" />
                                <path d="M157.48 74.06a78.71 78.71 0 01-26 45.94c-23.42 18.4-63.78.23-82.84-33.71C61.4 77.65 74.82 65.81 86.26 56c33.12 13.51 73.77 11.57 69.76-15.78 0-.28-.09-.57-.14-.85a78.62 78.62 0 011.6 34.69z" fill="url(#a)" />
                                <path d="M131.46 120.02A79.07 79.07 0 015.15 87.17c-.81-2.24-1.53-4.51-2.13-6.82v-.08c-.08-.29-.15-.57-.22-.86v-.07c-3.91-17.82 25.19-32.57 44.56 4.6.41.79.84 1.57 1.27 2.35 19.05 33.96 59.4 52.13 82.83 33.73z" fill="#14C9FE" />
                                <path d="M131.46 120.02A79.07 79.07 0 015.15 87.17c7.48 17.59 24.75 11.8 43.46-.86 19.07 33.94 59.43 52.11 82.85 33.71z" fill="url(#b)" opacity=".3" style="mix-blend-mode:multiply" />
                            </g>
                        </svg>
                    </div>
                    <div class="-mr-2">
                        <button @click="mobileMenuOpen = false" type="button" class="inline-flex justify-center items-center p-2 rounded-md transition duration-150 ease-in-out text-zinc-400 hover:text-zinc-500 hover:bg-zinc-100 focus:outline-none focus:bg-zinc-100 focus:text-zinc-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <nav class="grid row-gap-8">
                        <a href="{{ route('wave.dashboard') }}" class="flex items-center p-3 -m-3 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                Dashboard
                            </div>
                        </a>
                        <a href="{{ route('generator') }}" class="flex items-center p-3 -m-3 space-x-3 rounded-md transition duration-150 ease-in-out hover:bg-zinc-50">
                            <svg class="flex-shrink-0 w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <div class="text-base font-medium leading-6 text-zinc-900">
                                AI Generator
                            </div>
                        </a>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
