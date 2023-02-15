@extends('theme::layouts.app')


@section('content')

<section class="bg-white">
    <div class="max-w-6xl py-12 mx-auto">
        <div class="grid gap-8 md:grid-cols-2 lg:gap-12 ">
            <a href="/#Blog" class="flex flex-col p-6 space-y-6 transition-all duration-500 bg-white border border-indigo-100 rounded-lg shadow hover:shadow-xl lg:p-8 lg:flex-row lg:space-y-0 lg:space-x-6">
                <div class="flex items-center justify-center w-16 h-16 border border-indigo-200 shadow-inner bg-gradient-to-br from-indigo-50 to-indigo-200 rounded-xl lg:h-20 lg:w-20">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </div>
                <div class="flex-1">
                    <h5 class="mt-1 mb-2 text-xl font-bold lg:text-2xl">Blog</h5>
                    <p class="mb-6 text-lg text-gray-600">Start with our blog, and join the community!</p>
                    <span class="flex items-center text-lg font-bold text-indigo-600">
                        Click here to read the blog
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>
            <a href="/#Projects" class="flex flex-col p-6 space-y-6 transition-all duration-500 bg-white border border-indigo-100 rounded-lg shadow hover:shadow-xl lg:p-8 lg:flex-row lg:space-y-0 lg:space-x-6">
                <div class="flex items-center justify-center w-16 h-16 border border-indigo-200 shadow-inner bg-gradient-to-br from-indigo-50 to-indigo-200 rounded-xl lg:h-20 lg:w-20">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                </div>
                <div class="flex-1">
                    <h5 class="mt-1 mb-2 text-xl font-bold lg:text-2xl">Products</h5>
                    <p class="mb-6 text-lg text-gray-600">Check out the latest Focus Forge projects</p>
                    <span class="flex items-center text-lg font-bold text-indigo-600">
                        Click here to see more
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

@endsection
