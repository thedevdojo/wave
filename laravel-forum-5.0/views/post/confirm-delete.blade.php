@extends ('forum::master', ['breadcrumbs_append' => [trans_choice('forum::posts.delete', 1)]])

@section ('content')
    <div id="delete-post">
        <h2 class="flex-grow-1">{{ trans_choice('forum::posts.delete', 1) }}</h2>

        <hr>

        @include ('forum::post.partials.list', ['post' => $post, 'single' => true])

        <form method="POST" action="{{ Forum::route('post.delete', $post) }}">
            @csrf
            @method('DELETE')
            
            <div class="card mb-3">
                <div class="card-body">

                    @if (config('forum.general.soft_deletes'))
                        <div class="form-check" v-if="selectedPostAction == 'delete'">
                            <input class="form-check-input" type="checkbox" name="permadelete" value="1" id="permadelete">
                            <label class="form-check-label" for="permadelete">
                                {{ trans('forum::general.perma_delete') }}
                            </label>
                        </div>
                    @else
                        {{ trans('forum::general.generic_confirm') }}
                    @endif
                </div>
            </div>

            <div class="text-end">
                <a href="{{ URL::previous() }}" class="btn btn-link">{{ trans('forum::general.cancel') }}</a>
                <button type="submit" class="btn btn-danger px-5">{{ trans('forum::general.delete') }}</button>
            </div>
        </form>
    </div>
@stop
