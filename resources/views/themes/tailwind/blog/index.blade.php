@extends('theme::layouts.app')

@section('content')


<div class="relative px-8 pt-8 pb-20 mx-auto xl:px-5 max-w-7xl sm:px-6 lg:pt-10 lg:pb-28">
    <div class="absolute inset-0">
        <div class="bg-white h-1/3 sm:h-2/3"></div>
    </div>
    <div>
    <ul class="flex self-start inline w-auto px-3 py-1 mt-3 text-xs font-medium text-gray-600 bg-blue-100 rounded-md">
				<li class="mr-4 font-bold text-blue-600 uppercase">Categories:</li>
				@foreach($categories as $cat)
					<li class="@if(isset($category) && isset($category->slug) && ($category->slug == $cat->slug)){{ 'text-blue-700' }}@endif"><a href="{{ route('wave.blog.category', $cat->slug) }}">{{ $cat->name }}</a></li>
					@if(!$loop->last)
						<li class="mx-2">&middot;</li>
					@endif
				@endforeach
			</ul>
    </div>
    <div class="relative mx-auto max-w-7xl">
		<div class="flex flex-col justify-start">
        <section class="w-full bg-white">
    <div class="px-10 pt-16 pb-16 ml-auto mr-auto max-w-7xl md:px-24 lg:px-12 lg:py-20">
        <div class="grid gap-5 lg:grid-cols-2">
            <div class="flex flex-col justify-center md:pr-8 xl:pr-0 lg:max-w-lg">
                <div class="flex items-center justify-center w-16 h-16 mb-5 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div class="max-w-xl mb-6">
                    <div class="mb-6">
                        <p class="inline font-sans text-5xl font-bold tracking-tight text-gray-900 sm:text-6xl sm:leading-none">Welcome to the Blog! </p>
                        <p class="block font-sans text-5xl font-bold tracking-tight text-purple-700 sm:text-6xl sm:leading-none">Check back often.</p>
                    </div>
                    <p class="text-base text-gray-700 md:text-lg">Beep Boop Bop</p>
                </div>
                <div class="max-w-xl mb-6">
                    <p class="relative">
                        <a href="#_" class="inline-flex flex-col items-center font-semibold text-purple-700 transition-colors duration-200 cursor-pointer group">
                            <span class="flex items-center w-full">
                                <span>Learn more</span>
                                <svg class="inline-block w-3 ml-2" fill="currentColor" viewBox="0 0 12 12"><path d="M9.707,5.293l-5-5A1,1,0,0,0,3.293,1.707L7.586,6,3.293,10.293a1,1,0,1,0,1.414,1.414l5-5A1,1,0,0,0,9.707,5.293Z"></path></svg>
                            </span>
                            <span class="w-full h-0.5 translate-y-2 group-hover:translate-y-1 duration-200 ease-out transition opacity-0 group-hover:opacity-100 block bg-purple-600"></span>
                        </a>
                    </p>
                </div>
            </div>
            <div class="flex justify-center w-full lg:items-center">
                <div class="flex flex-col items-end pr-3">
                    <img src="https://cdn.devdojo.com/images/july2021/content-19-1.jpg" class="object-cover w-full h-full mb-6 rounded shadow-lg lg:h-48 xl:h-56 lg:w-48 xl:w-56">
                    <img src="https://cdn.devdojo.com/images/july2021/content-19-2.jpg" class="object-cover w-full h-full rounded shadow-lg lg:h-32 xl:h-40 lg:w-32 xl:w-40">
                </div>
                <div class="pl-3">
                    <img src="https://cdn.devdojo.com/images/july2021/content-19-3.jpg" class="object-cover w-full h-full rounded shadow-lg lg:h-64 xl:h-80 lg:w-64 xl:w-80">
                </div>
            </div>
        </div>
    </div>
</section>
		</div>
        <div class="grid gap-5 mx-auto mt-12 sm:grid-cols-2 lg:grid-cols-3">

			<!-- Loop Through Posts Here -->
			@foreach($posts as $post)
			<article id="post-{{ $post->id }}" class="flex flex-col overflow-hidden rounded-lg shadow-lg" typeof="Article">

				<meta property="name" content="{{ $post->title }}">
				<meta property="author" typeof="Person" content="admin">
				<meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
				<meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

                <div class="flex-shrink-0">
					<a href="{{ $post->link() }}">
                    	<img class="object-cover w-full h-48" src="{{ $post->image() }}" alt="">
					</a>
                </div>
                <div class="relative flex flex-col justify-between flex-1 p-6 bg-white">
                    <div class="flex-1">
                        <a href="{{ $post->link() }}" class="block">
                            <h3 class="mt-2 text-xl font-semibold leading-7 text-gray-900">
                                {{ $post->title }}
                            </h3>
                        </a>
                        <a href="{{ $post->link() }}" class="block">
                            <p class="mt-3 text-base leading-6 text-gray-500">
								{{ substr(strip_tags($post->body), 0, 200) }}@if(strlen(strip_tags($post->body)) > 200){{ '...' }}@endif
                            </p>
                        </a>
                    </div>
                    <p class="relative self-start inline-block px-2 py-1 mt-4 text-xs font-medium leading-5 text-gray-400 uppercase bg-gray-100 rounded">
                            <a href="{{ route('wave.blog.category', $post->category->slug) }}" class="text-gray-700 hover:underline" rel="category">
								{{ $post->category->name }}
                            </a>
                        </p>
                </div>

                <div class="flex items-center p-6 bg-gray-50">
                        <div class="flex-shrink-0">
                            <a href="#">
                                <img class="w-10 h-10 rounded-full" src="{{ $post->user->avatar() }}" alt="">
                            </a>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium leading-5 text-gray-900">
                                Written by <a href="#" class="hover:underline">{{ $post->user->name }}</a>
                            </p>
                            <div class="flex text-sm leading-5 text-gray-500">
								on <time datetime="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}" class="ml-1">{{ Carbon\Carbon::parse($post->created_at)->toFormattedDateString() }}</time>
                            </div>
                        </div>
                    </div>

            </article>
			@endforeach
			<!-- End Post Loop Here -->

        </div>
    </div>

	<div class="flex justify-center my-10">
		{{ $posts->links('theme::partials.pagination') }}
		<!--li class="uk-active"><span aria-current="page" class="page-numbers current">1</span></li>
		<li><a class="page-numbers" href="https://demo.yootheme.com/themes/wordpress/2017/copper-hill/?paged=2&amp;page_id=92">2</a></li>
		<li><a class="next page-numbers" href="https://demo.yootheme.com/themes/wordpress/2017/copper-hill/?paged=2&amp;page_id=92"><span uk-pagination-next="" class="uk-pagination-next uk-icon"><svg width="7" height="12" viewBox="0 0 7 12" xmlns="http://www.w3.org/2000/svg" ratio="1"><polyline fill="none" stroke="#000" stroke-width="1.2" points="1 1 6 6 1 11"></polyline></svg></span></a></li-->
	</ul>

</div>

@endsection
