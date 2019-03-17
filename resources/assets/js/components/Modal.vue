<template>
    <div class="modal fade show" id="mainModal">
        <div class="modal-dialog" role="document">
            <div class="modal-header">
                <h5 class="modal-title">
                    <slot name="title"></slot>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <slot name="body"></slot>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"><slot name="save"></slot></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><slot name="close"></slot></button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'modal'
    }
</script>

<style scoped>

</style>