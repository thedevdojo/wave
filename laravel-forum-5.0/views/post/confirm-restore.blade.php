@extends ('forum::master', ['breadcrumbs_append' => [trans_choice('forum::posts.restore', 1)]])

@section ('content')
    <div id="delete-post">
        <h2 class="flex-grow-1">{{ trans_choice('forum::posts.restore', 1) }}</h2>

        <hr>

        @include ('forum::post.partials.list', ['post' => $post, 'single' => true])

        <form method="POST" action="{{ Forum::route('post.restore', $post) }}">
            @csrf
            @method('POST')
            
            <div class="card mb-3">
                <div class="card-body">
                    {{ trans('forum::general.generic_confirm') }}
                </div>
            </div>

            <div class="text-end">
                <a href="{{ URL::previous() }}" class="btn btn-link">{{ trans('forum::general.cancel') }}</a>
                <button type="submit" class="btn btn-primary px-5">{{ trans('forum::general.restore') }}</button>
            </div>
        </form>
    </div>
@stop
