
<!-- Section 1 -->
<footer class="@if(Request::is('/')){{ 'bg-white' }}@else{{ 'bg-gray-50' }}@endif">
    <div class="px-8 pt-16 mx-auto lg:px-12 xl:px-16 max-w-7xl">
        <div class="flex flex-wrap items-start justify-between pb-20">
            <a href="#_" class="flex items-center w-auto text-lg font-bold md:w-1/6">
                @if(Voyager::image(theme('footer_logo')))
                    <img class="h-10" src="{{ Voyager::image(theme('footer_logo')) }}" alt="Company name">
                @else
                    <div class="relative flex items-center text-gray-500 leading-tighter">
                        <svg class="h-8 mt-1 fill-current" viewBox="0 0 164 145" xmlns="http://www.w3.org/2000/svg"><path d="M161.47 45.02c-.22-1-.46-2-.72-3v-.28l-.19-.27-.18-.66h-.14c-2.69-7.59-9.38-11.76-18.95-11.76-14.1 0-33.18 9-51.05 24.2l-2.3 2c-18.72-8.37-27.87-24.27-35.26-37.1C46.86 8.03 42.26.04 34.68.04c-3.8 0-7.73 2.05-12.29 6.39-.15.14-15.84 15.85-21.08 41.81a76.56 76.56 0 001.13 33.75v.19c.06.26.12.52.2.78v.16c.54 2.08 1.2 4.21 2 6.35l.25.69a81.55 81.55 0 00130.26 33.92l.75-.59v-.08a81.73 81.73 0 0025.51-43.44c.24-1.11.46-2.24.66-3.36a83.42 83.42 0 001.08-8.83 80.61 80.61 0 00-1.68-22.76zm-67.95 12c17-14.4 34.86-23 47.81-23 7.5 0 12.3 2.85 14.26 8.48l-.07.42.24.34c.06.46.08.91.11 1.36V46.9c0 .57-.09 1.12-.17 1.66v.25c-.08.46-.16.92-.28 1.36-.12.44-.23.74-.35 1.1-.05.14-.08.28-.14.41a13.28 13.28 0 01-2.42 4c-4.47 5.18-13.39 8-25.13 8-1.22 0-2.46 0-3.72-.1h-.46c-1.2-.06-2.42-.16-3.65-.28l-.64-.06c-1.21-.13-2.44-.28-3.68-.46l-.64-.1c-1.27-.19-2.53-.4-3.81-.64l-.47-.09c-1.31-.26-2.63-.53-4-.84h-.11c-1.33-.31-2.66-.65-4-1l-.54-.15c-1.28-.35-2.55-.73-3.81-1.13l-.67-.21c-1.25-.41-2.49-.83-3.73-1.28l-.26-.09.33-.23zM5.26 68.34a67.54 67.54 0 011-19.13 83.46 83.46 0 0119.57-39.12c3.53-3.36 6.53-5.07 8.9-5.07 4.67 0 8.63 6.89 13.65 15.6C55.46 32.93 65 49.51 83.79 58.79l-.14.12-.77.66c-9.74 8.39-20.7 17.82-31.24 25.22C43.55 69.34 32.39 60.48 21 60.48a19.21 19.21 0 00-15.12 7c-.19.26-.41.55-.62.86zm4.42 20.08c-.8-2.19-1.48-4.37-2-6.5l-.06-.25a5.18 5.18 0 01-.14-.53v-.2c-.06-.25-.09-.5-.13-.75-.04-.25-.08-.46-.1-.69-.02-.23 0-.48 0-.72v-.64-.73-.62c0-.2.08-.47.12-.7.04-.23.07-.42.12-.63.05-.21.12-.43.19-.65.07-.22.11-.43.19-.64.08-.21.16-.38.24-.57.08-.19.18-.45.29-.67.11-.22.17-.31.26-.47.09-.16.26-.49.41-.72a.86.86 0 01.07-.1c.23-.346.476-.68.74-1a14.22 14.22 0 0111.22-5.13c9.49 0 19.07 7.93 26.27 21.74l.21.39c-10.5 6.88-18.71 10.24-25 10.24-5.99-.05-10.18-3.14-12.9-9.48v.02zm72.1 50.66a76.7 76.7 0 01-13.33-1.17c-1.24-.22-2.46-.47-3.68-.75l-.7-.16c-1.17-.28-2.33-.58-3.49-.92l-.65-.19c-1.14-.34-2.27-.7-3.38-1.09l-.19-.06a83.04 83.04 0 01-3.42-1.31l-.67-.28c-1.1-.46-2.19-.94-3.26-1.45l-.55-.27c-1-.5-2.07-1-3.08-1.58-.09-.05-.19-.09-.28-.15-1.06-.58-2.1-1.19-3.13-1.81l-.61-.38c-1-.63-2-1.28-3-2l-.43-.3c-.94-.66-1.86-1.33-2.77-2-.1-.09-.22-.17-.32-.25-.947-.74-1.877-1.5-2.79-2.28l-.54-.46c-.9-.79-1.79-1.59-2.66-2.42l-.31-.31c-.82-.79-1.63-1.6-2.42-2.44l-.34-.35a76.17 76.17 0 01-2.38-2.69l-.46-.54c-.78-.93-1.53-1.87-2.27-2.83l-.2-.28c-.7-.92-1.37-1.87-2-2.82l-.33-.48c-.66-1-1.31-2-1.93-3l-.16-.27c8.08 2.92 19.11-.15 33.93-9.8.45.75.91 1.5 1.39 2.24l.33.5c.37.56.74 1.13 1.12 1.68l.53.75c.32.47.65.93 1 1.39l.63.83.94 1.26c.23.29.46.57.68.86l.94 1.17.73.87.95 1.12c.25.29.51.57.77.86l1 1.07.8.85 1 1 .83.83 1 1 .22.21c.082.088.169.172.26.25l.36.34 1 .91.94.85.94.83 1 .86c.3.25.6.51.91.75l1.06.86.89.7 1.12.85.85.63 1.18.84.83.58c.4.28.81.55 1.22.82l.81.52c.42.28.84.54 1.26.8l.79.48 1.31.77.76.44 1.35.74.74.39 1.38.7.72.35 1.42.67.7.31 1.45.62c.22.1.45.18.67.27l1.49.59.64.23c.51.19 1 .37 1.52.54l.61.2 1.55.48.58.17c.53.15 1.06.3 1.59.43l.53.13c.55.13 1.09.27 1.64.38l.47.1c.56.11 1.12.23 1.69.32.12 0 .25 0 .37.06.59.1 1.19.2 1.78.28h.21a49.06 49.06 0 006.14.4c.79 0 1.57 0 2.34-.07h.29a76.19 76.19 0 01-32.42 7.21v.02zm75.37-63.34c-.19 1.07-.4 2.14-.64 3.2a76.65 76.65 0 01-24.5 41.23l-.08.06c-.45.35-.91.66-1.37 1l-.6.4-.44.3c-.39.25-.8.48-1.2.71l-.79.45-.28.15c-.42.22-.85.42-1.28.62-.43.2-.73.35-1.11.5-.38.15-.9.35-1.36.52-.46.17-.74.28-1.12.4-.38.12-1 .28-1.46.41-.46.13-.75.22-1.13.3-.38.08-1.06.22-1.59.31-.37.07-.72.15-1.09.21-.61.09-1.23.14-1.84.2l-.95.11c-.94.06-1.9.1-2.87.1-.66 0-1.32 0-2-.05h-.65l-1.34-.09-.79-.09-1.21-.14-.84-.13-1.17-.19-.88-.17-1.13-.23-.9-.22c-.37-.09-.75-.17-1.12-.27l-.91-.25-1.11-.32-.92-.3c-.37-.11-.74-.23-1.1-.36-.36-.13-.61-.21-.92-.33l-1.1-.4-.91-.36-1.1-.45-.91-.4-1.1-.49-.9-.43-1.09-.54-.9-.46-1.08-.58-.89-.49-1.07-.62-.89-.53-1.06-.65-.87-.56-1.05-.69-.87-.59-1-.73-.85-.61-1-.77-.84-.65-1-.8c-.28-.22-.56-.44-.83-.67l-1-.84-.82-.7-.81-.72-.17-.15-.78-.72-1-.94-.72-.69-1-1-.66-.67-1.06-1.12-.58-.63-1.1-1.23-.5-.57c-.39-.45-.78-.91-1.16-1.37-.13-.16-.27-.32-.4-.49-.41-.5-.81-1-1.21-1.52l-.31-.38c-.43-.56-.85-1.13-1.27-1.7l-.19-.26c-.47-.64-.92-1.29-1.37-1.94v-.06c-.34-.49-.66-1-1-1.47-.48-.73-.95-1.47-1.4-2.22-.15-.24-.3-.47-.44-.71 10.87-7.59 22.09-17.24 32.06-25.82l2.71-2.33c12.69 5.09 26.26 7.89 38.38 7.89 13.42 0 23.45-3.39 29-9.81a18.24 18.24 0 001.27-1.66l.18-.26c.14-.22.3-.43.43-.65a76.24 76.24 0 01-1.08 19.17z" fill-rule="nonzero"/></svg>
                        <span class="ml-4 text-3xl font-thin tracking-wide">wave</span>
                    </div>
                @endif
            </a>
            <div class="grid w-full grid-cols-2 pt-2 mt-20 sm:grid-cols-4 gap-y-16 lg:gap-x-8 md:w-5/6 md:mt-0 md:pr-6">
                <div class="md:justify-self-end">
                    <h3 class="font-semibold text-black">Product</h3>
                    <ul class="mt-6 space-y-4 text-sm">
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Features</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Integrations</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Documentation</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Pricing</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="md:justify-self-end">
                    <h3 class="font-semibold text-black">About</h3>
                    <ul class="mt-6 space-y-4 text-sm">
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Our Story</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Company</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Our Team</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Work With Us</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="md:justify-self-end">
                    <h3 class="font-semibold text-black">Resources</h3>
                    <ul class="mt-6 space-y-4 text-sm">
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Help Center</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Developer API</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Our Blog</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Status</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Sitemap</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="md:justify-self-end">
                    <h3 class="font-semibold text-black">Contact</h3>
                    <ul class="mt-6 space-y-4 text-sm">
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Advertising</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Press</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Partners</span>
                            </a>
                        </li>
                        <li>
                            <a href="#_" class="relative inline-block text-black group">
                                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-1"></span>
                                <span>Email</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between py-10 border-t border-solid lg:flex-row border-gray">
            <ul class="flex flex-wrap space-x-5 text-xs">
                <li class="mb-6 text-center flex-full lg:flex-none lg:mb-0">&copy; {{ date('Y') }} {{ setting('site.title', 'Laravel Wave') }}, Inc. All rights reserved.</li>
                <li class="lg:ml-6">
                    <a href="#_" class="relative inline-block text-black group">
                        <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-0"></span>
                        <span>Privacy Policy</span>
                    </a>
                </li>
                <li class="ml-auto mr-auto text-center lg:ml-6 lg:mr-0">
                    <a href="#_" class="relative inline-block text-black group">
                        <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-0"></span>
                        <span>Disclaimers</span>
                    </a>
                </li>
                <li class="lg:ml-6">
                    <a href="#_" class="relative inline-block text-black group">
                        <span class="absolute bottom-0 w-full transition duration-150 ease-out transform -translate-y-1 border-b border-black opacity-0 group-hover:opacity-100 group-hover:translate-y-0"></span>
                        <span>Terms and Conditions</span>
                    </a>
                </li>
            </ul>

            <ul class="flex items-center mt-10 space-x-5 lg:mt-0">
                <li>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <span class="sr-only">GitHub</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <span class="sr-only">Dribbble</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>

@if(!auth()->guest() && auth()->user()->hasAnnouncements())
    @include('theme::partials.announcements')
@endif

<!-- Scripts -->
<script src="{{ asset('themes/' . $theme->folder . '/js/app.js') }}"></script>

@yield('javascript')

@if(setting('site.google_analytics_tracking_id', ''))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('site.google_analytics_tracking_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ setting("site.google_analytics_tracking_id") }}');
    </script>

@endif
