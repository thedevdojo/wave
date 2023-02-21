<div class="modal fade" tabindex="-1" role="dialog" data-modal="{{ $key }}" data-close-modal>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title">{!! $title !!}</h5>
                <button type="button" class="btn-close" aria-label="Close" data-close-modal></button>
            </div>
            <form action="{{ $route }}" method="POST">
                @csrf
                @if (isset($method))
                    @method($method)
                @endif

                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-close-modal>{{ trans('forum::general.cancel') }}</button>
                    {{ $actions }}
                </div>
            </form>
        </div>
    </div>
</div>