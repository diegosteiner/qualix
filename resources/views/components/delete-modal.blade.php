<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}">{{ __('Wirklich löschen?') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $text }}
            </div>
            <div class="modal-footer">
                @component('components.form', ['method' => 'DELETE', 'route' => $route])
                    <button type="submit" class="btn btn-danger">{{ __('Löschen') }}</button>
                @endcomponent
            </div>
        </div>
    </div>
</div>