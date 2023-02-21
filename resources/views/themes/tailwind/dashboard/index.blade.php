@extends('theme::layouts.app')


@section('content')


<div class="max-w-6xl py-12 mx-auto">
        <div class="grid gap-8 md:grid-cols-2 lg:gap-12">
            <a href="#_" class="flex flex-col p-6 space-y-6 transition-all duration-500 bg-white border border-indigo-100 rounded-lg shadow hover:shadow-xl lg:p-8 lg:flex-row lg:space-y-0 lg:space-x-6">
                <div class="flex items-center justify-center w-16 h-16 border border-cyan-200 shadow-inner bg-gradient-to-br from-cyan-50 to-cyan-200 rounded-xl lg:h-20 lg:w-20">
                    <svg class="w-10 h-10 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" class=""></path>
</svg>

                </div>
                <div class="flex-1">
                    <h5 class="mt-1 mb-2 text-xl font-bold lg:text-2xl 2xl:text-cyan-800">Blog</h5>
                    <p class="mb-6 text-lg text-gray-600">New ideas, products and discussions.</p>
                    <span class="flex items-center text-lg font-bold 2xl:text-cyan-800">
                        Check out the community!
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>
            <a href="#_" class="flex flex-col p-6 space-y-6 transition-all duration-500 bg-white border border-indigo-100 rounded-lg shadow hover:shadow-xl lg:p-8 lg:flex-row lg:space-y-0 lg:space-x-6">
                <div class="flex items-center justify-center w-16 h-16 border border-indigo-200 shadow-inner bg-gradient-to-br from-cyan-50 to-cyan-200 rounded-xl lg:h-20 lg:w-20">
                    <svg class="w-10 h-10 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" class=""></path></svg>
                </div>
                <div class="flex-1">
                    <h5 class="mt-1 mb-2 text-xl font-bold lg:text-2xl 2xl:text-cyan-800">Products</h5>
                    <p class="mb-6 text-lg text-gray-600">We're always working on something new!</p>
                    <span class="flex items-center text-lg font-bold 2xl:text-cyan-800">
                        Check out what we're up to
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>
        </div>
    </div>

@endsection
