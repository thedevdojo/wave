@extends ('forum::master', ['breadcrumbs_append' => [trans('forum::general.new_reply')]])

@section ('content')
    <div id="create-post">
        <h2>{{ trans('forum::general.new_reply') }} ({{ $thread->title }})</h2>

        @if ($post !== null && !$post->trashed())
            <p>{{ trans('forum::general.replying_to', ['item' => $post->authorName]) }}:</p>

            @include ('forum::post.partials.quote')
        @endif

        <hr />

        <form method="POST" action="{{ Forum::route('post.store', $thread) }}">
            {!! csrf_field() !!}
            @if ($post !== null)
                <input type="hidden" name="post" value="{{ $post->id }}">
            @endif

            <div class="mb-3">
                <textarea name="content" class="form-control">{{ old('content') }}</textarea>
            </div>

            <div class="text-end">
                <a href="{{ URL::previous() }}" class="btn btn-link">{{ trans('forum::general.cancel') }}</a>
                <button type="submit" class="btn btn-primary px-5">{{ trans('forum::general.reply') }}</button>
            </div>
        </form>
    </div>
@stop
