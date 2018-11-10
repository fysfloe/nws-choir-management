<template>
    <div class="btn-group attendance-buttons" role="group" :aria-label="texts.acceptOrDecline">
        <button @click="confirm" :class="{ 'btn': true, 'btn-success': isConfirmed, 'btn-default': !isConfirmed }" data-toggle="tooltip" :title="texts.present">
            <span class="oi oi-check"></span>
        </button>
        <button @click="excuse" :class="{ 'btn': true, 'btn-warning': isExcused, 'btn-default': !isExcused }" data-toggle="tooltip" :title="texts.excused">
            <span class="oi oi-medical-cross"></span>
        </button>
        <button @click="unexcuse" :class="{ 'btn': true, 'btn-danger': isUnexcused, 'btn-default': !isUnexcused }" data-toggle="tooltip" :title="texts.unexcused">
            <span class="oi oi-x"></span>
        </button>
    </div>
</template>
<script>
export default {
    props: ['routes', 'user', 'texts'],
    data() {
        return {
            isConfirmed: this.user.confirmed === 1,
            isExcused: this.user.excused === 1,
            isUnexcused: this.user.confirmed === 0 && this.user.excused === 0
        }
    },
    methods: {
        confirm: function () {
            this.$http.get(this.routes.confirm + '/' + this.user.id).then(response => {
                this.isExcused = false;
                this.isUnexcused = false;
                this.isConfirmed = true;
            }, response => {})
        },
        excuse: function () {
            this.$http.get(this.routes.excused + '/' + this.user.id).then(response => {
                this.isExcused = true;
                this.isUnexcused = false;
                this.isConfirmed = false;
            }, response => {})
        },
        unexcuse: function () {
            this.$http.get(this.routes.unexcused + '/' + this.user.id).then(response => {
                this.isExcused = false;
                this.isUnexcused = true;
                this.isConfirmed = false;
            }, response => {})
        }
    }
}
</script>