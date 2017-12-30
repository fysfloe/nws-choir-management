<div class="modal-header">
    <h5 class="modal-title">@yield('title')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @yield('body')
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary">@yield('save')</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@yield('close')</button>
</div>
