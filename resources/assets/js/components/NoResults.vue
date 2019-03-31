<template>
    <div class="no-results">
        <span class="oi oi-magnifying-glass"></span>
        <h3>{{ $t('Nothing to see here.') }}</h3>

        <router-link v-if="action" :to="action" class="btn btn-primary btn-sm">
            <span class="oi oi-plus"></span>
            {{ buttonText }}
        </router-link>
    </div>
</template>

<script>
    export default {
        name: "no-results",
        props: {
            action: {
                type: String
            },
            buttonText: {
                type: String
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../sass/variables";

    .no-results {
        display: block;
        padding: 2.5em;
        margin-top: 1em;
        text-align: center;
        background: $lightgrey;

        .oi-magnifying-glass {
            font-size: 4em;
            display: block;
            color: darken($lightgrey, 20);
            margin-bottom: 2rem;
        }

        h3 {
            border: none;
            color: $darkgrey;
            margin: 0;
        }

        .btn {
            margin-top: 1rem;
        }
    }
</style>