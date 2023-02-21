@extends ('forum::master', ['breadcrumbs_append' => [trans('forum::posts.view')]])

@section ('content')
    <div id="post">
        <div class="d-flex flex-row justify-content-between mb-3">
            <h2 class="flex-grow-1">{{ trans('forum::posts.view') }} ({{ $thread->title }})</h2>
            <a href="{{ Forum::route('thread.show', $thread) }}" class="btn btn-secondary btn-lg">{{ trans('forum::threads.view') }}</a>
        </div>

        <hr>

        @include ('forum::post.partials.list', ['post' => $post, 'single' => true])
    </div>
@stop
