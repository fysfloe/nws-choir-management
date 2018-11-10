<template>
    <div class="btn-group accept-decline" role="group" :aria-label="texts.acceptOrDecline">
        <button @click="accept" :class="{ 'btn-success': hasAccepted, 'btn-default': !hasAccepted, 'btn': true }" data-toggle="tooltip" :title="hasAccepted ? texts.attending : texts.accept">
            <span :class="{ 'oi': true, 'oi-check': true }"></span>
        </button>
        <button @click="decline" :class="{ 'btn-danger': hasDeclined, 'btn-default': !hasDeclined, 'btn': true }" data-toggle="tooltip" :title="hasDeclined ? texts.notAttending : texts.decline">
            <span :class="{ 'oi': true, 'oi-x': true }"></span>
        </button>
    </div>
</template>

<script>
export default {
    props: ['acceptRoute', 'declineRoute', 'accepted', 'declined', 'texts'],
    data() {
        return {
            hasAccepted: this.accepted,
            hasDeclined: this.declined,
            loading: {
                accept: false,
                decline: false
            }
        }
    },
    methods: {
        accept: function (event) {
            event.preventDefault();

            if (!this.hasAccepted) {
                this.showCommentField = true;
                this.loading.accept = true;

                this.$http.get(this.acceptRoute).then(response => {
                    this.loading.accept = false;
                    this.hasAccepted = true;
                    this.hasDeclined = false;
                }, response => {
                    this.loading.accept = false;

                    this.$emit('alert', response);
                });
            }
        },
        decline: function (event) {
            event.preventDefault();

            if (!this.hasDeclined) {
                this.showCommentField = true;
                this.loading.decline = true;

                this.$http.get(this.declineRoute).then(response => {
                    this.loading.decline = false;
                    this.hasDeclined = true;
                    this.hasAccepted = false;
                }, response => {
                    this.loading.decline = false;
                });
            }
        }
    }
}
</script>
