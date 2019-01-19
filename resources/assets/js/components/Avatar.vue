<template>
    <div class="avatar" v-if="img">
        <img :src="src" :alt="alt">
    </div>
    <div class="avatar avatar-default" v-else>
        <span :class="'oi ' + icon"></span>
    </div>
</template>

<script>
    export default {
        props: {
            src: {
                type: String,
                required: true
            },
            img: {
                type: String
            },
            icon: {
                type: String,
                required: true
            },
            alt: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>