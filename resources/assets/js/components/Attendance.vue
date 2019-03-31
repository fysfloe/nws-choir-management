<template>
    <div class="btn-group attendance-buttons" role="group" :aria-label="$t('Accept or decline')">
        <button @click="confirm" :class="{ 'btn': true, 'btn-success': isConfirmed, 'btn-default': !isConfirmed }" data-toggle="tooltip" :title="$t('Present')">
            <span class="oi oi-check"></span>
        </button>
        <button @click="excuse" :class="{ 'btn': true, 'btn-warning': isExcused, 'btn-default': !isExcused }" data-toggle="tooltip" :title="$t('Excused')">
            <span class="oi oi-medical-cross"></span>
        </button>
        <button @click="setUnexcused" :class="{ 'btn': true, 'btn-danger': isUnexcused, 'btn-default': !isUnexcused }"
                data-toggle="tooltip" :title="$t('Unexcused')">
            <span class="oi oi-x"></span>
        </button>
    </div>
</template>
<script>

    export default {
        props: {
            userId: {
                type: Number,
                required: true
            },
            type: {
                type: String,
                required: true
            }
        },
        computed: {
            user() {
                //return this.$store.state.rehearsals.rehearsal.participants[0];
                return this.$store.getters[`${this.type}s/participant`](this.userId)
            },
            isConfirmed() {
                return this.user.confirmed === 1;
            },
            isExcused() {
                return this.user.excused === 1;
            },
            isUnexcused() {
                return this.user.confirmed === 0 && this.user.excused === 0;
            }
        },
        methods: {
            confirm() {
                this.$store.dispatch(`${this.type}s/confirm`, {id: this.$route.params.id, userId: this.user.id})
            },
            excuse() {
                this.$store.dispatch(`${this.type}s/excuse`, {id: this.$route.params.id, userId: this.user.id})
            },
            setUnexcused() {
                this.$store.dispatch(`${this.type}s/setUnexcused`, {id: this.$route.params.id, userId: this.user.id})
            }
        }
    }
</script>