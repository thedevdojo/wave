@extends('theme::layouts.app')

@section('content')


<div class="relative px-8 pt-8 pb-20 mx-auto max-w-7xl xl:px-5 sm:px-6 lg:pt-10 lg:pb-28">
    <div class="relative mx-auto max-w-7xl">
		<div class="flex flex-col justify-start">
            <h2 class="text-base font-semibold leading-7 text-indigo-600">Latest Articles</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">From The Blog</p>
			<p class="mt-3 text-xl leading-7 text-zinc-500 sm:mt-4">
				Check out some of our latest blog posts below.
			</p>
            <ul class="inline-block flex self-start px-3 py-2 mt-7 w-auto text-xs font-medium text-zinc-600 bg-white rounded-full border border-zinc-100">
				<li class="mr-4 font-bold text-blue-600 uppercase">Categories:</li>
				@foreach($categories as $cat)
					<li class="@if(isset($category) && isset($category->slug) && ($category->slug == $cat->slug)){{ 'text-blue-700' }}@endif"><a href="{{ route('wave.blog.category', $cat->slug) }}">{{ $cat->name }}</a></li>
					@if(!$loop->last)
						<li class="mx-2">&middot;</li>
					@endif
				@endforeach
			</ul>
		</div>
        
        <div class="grid gap-5 mx-auto mt-7 sm:grid-cols-2 lg:grid-cols-3">

			<!-- Loop Through Posts Here -->
			@foreach($posts as $post)
			<article id="post-{{ $post->id }}" class="flex overflow-hidden flex-col rounded-lg shadow-lg" typeof="Article">

				<meta property="name" content="{{ $post->title }}">
				<meta property="author" typeof="Person" content="admin">
				<meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
				<meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

                <div class="flex-shrink-0">
					<a href="{{ $post->link() }}">
                    	<img class="object-cover w-full h-48" src="{{ $post->image() }}" alt="">
					</a>
                </div>
                <div class="flex relative flex-col flex-1 justify-between p-6 bg-white">
                    <div class="flex-1">
                        <a href="{{ $post->link() }}" class="block">
                            <h3 class="mt-2 text-xl font-semibold leading-7 text-zinc-900">
                                {{ $post->title }}
                            </h3>
                        </a>
                        <a href="{{ $post->link() }}" class="block">
                            <p class="mt-3 text-base leading-6 text-zinc-500">
								{{ substr(strip_tags($post->body), 0, 200) }}@if(strlen(strip_tags($post->body)) > 200){{ '...' }}@endif
                            </p>
                        </a>
                    </div>
                    <p class="inline-block relative self-start px-2 py-1 mt-4 text-xs font-medium leading-5 text-zinc-400 uppercase bg-zinc-100 rounded">
                            <a href="{{ route('wave.blog.category', $post->category->slug) }}" class="text-zinc-700 hover:underline" rel="category">
								{{ $post->category->name }}
                            </a>
                        </p>
                </div>

                <div class="flex items-center p-6 bg-zinc-50">
                        <div class="flex-shrink-0">
                            <a href="#">
                                <img class="w-10 h-10 rounded-full" src="{{ $post->user->avatar() }}" alt="">
                            </a>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium leading-5 text-zinc-900">
                                Written by <a href="#" class="hover:underline">{{ $post->user->name }}</a>
                            </p>
                            <div class="flex text-sm leading-5 text-zinc-500">
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
