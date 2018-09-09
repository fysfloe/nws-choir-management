<template>
    <div class="accept-decline" :aria-label="texts.acceptOrDecline">
        <span @click="accept" :class="{ 'text-success': hasAccepted, 'text-muted': !hasAccepted }" data-toggle="tooltip" :title="hasAccepted ? texts.attending : texts.accept">
            <span :class="{ 'oi': true, 'oi-check': true }"></span>
        </span>
        <span @click="decline" :class="{ 'text-danger': hasDeclined, 'text-muted': !hasDeclined }" data-toggle="tooltip" :title="hasDeclined ? texts.notAttending : texts.decline">
            <span :class="{ 'oi': true, 'oi-x': true }"></span>
        </span>
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
                this.loading.accept = true;

                this.$http.get(this.acceptRoute).then(response => {
                    this.loading.accept = false;
                    this.hasAccepted = true;
                    this.hasDeclined = false;
                }, response => {
                    this.loading.accept = false;
                });
            }
        },
        decline: function (event) {
            event.preventDefault();

            if (!this.hasDeclined) {
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
