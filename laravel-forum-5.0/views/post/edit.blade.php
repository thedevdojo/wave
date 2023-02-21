@extends ('forum::master', ['breadcrumbs_append' => [trans('forum::posts.edit')]])

@section ('content')
    <div id="edit-post">
        <h2 class="flex-grow-1">{{ trans('forum::posts.edit') }} ({{ $thread->title }})</h2>

        <hr>

        @if ($post->parent)
            <h3>{{ trans('forum::general.response_to', ['item' => $post->parent->authorName]) }}...</h3>

            @include ('forum::post.partials.list', ['post' => $post->parent, 'single' => true])
        @endif

        <form method="POST" action="{{ Forum::route('post.update', $post) }}">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <textarea name="content" class="form-control">{{ old('content') !== null ? old('content') : $post->content }}</textarea>
            </div>

            <div class="text-end">
                <a href="{{ URL::previous() }}" class="btn btn-link">{{ trans('forum::general.cancel') }}</a>
                <button type="submit" class="btn btn-primary px-5">{{ trans('forum::general.save') }}</button>
            </div>
        </form>
    </div>
@stop
