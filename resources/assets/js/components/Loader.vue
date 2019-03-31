<template>
    <div class="loader">
        <div class="loader-inner">
            <div></div>
            <div></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'loader'
    }
</script>

<style lang="scss" scoped>
    @import "../../sass/variables";

    .loader {
        width: 100%;
        text-align: center;
        padding: 15% 0;
    }

    .loader-inner {
        display: inline-block;
        position: relative;
        width: 5rem;
        height: 5rem;
    }

    .loader-inner div {
        position: absolute;
        border: 0.2rem solid $brand-primary;
        opacity: 1;
        border-radius: 50%;
        animation: loader-inner 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
    }

    .loader-inner div:nth-child(2) {
        animation-delay: -0.5s;
    }

    @keyframes loader-inner {
        0% {
            top: 2.05rem;
            left: 2.05rem;
            width: 0.25rem;
            height: 0.25rem;
            opacity: 1;
        }
        100% {
            top: 0.65rem;
            left: 0.65rem;
            width: 3rem;
            height: 3rem;
            opacity: 0;
        }
    }

</style>