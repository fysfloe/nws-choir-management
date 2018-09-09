<template>
    <div class="attendance-buttons">
        <a @click="confirm" :class="{ 'text-success': isConfirmed, 'text-muted': !isConfirmed }" data-toggle="tooltip" :title="texts.present">
            <span class="oi oi-check"></span>
        </a>
        <a @click="excuse" :class="{ 'text-warning': isExcused, 'text-muted': !isExcused }" data-toggle="tooltip" :title="texts.excused">
            <span class="oi oi-medical-cross"></span>
        </a>
        <a @click="unexcuse" :class="{ 'text-danger': isUnexcused, 'text-muted': !isUnexcused }" data-toggle="tooltip" :title="texts.unexcused">
            <span class="oi oi-x"></span>
        </a>
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