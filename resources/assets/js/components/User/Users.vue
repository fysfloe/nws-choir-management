<template>
    <div>
        <header class="page-header">
            <h2>{{ $t('Users') }}</h2>

            <div class="main-actions" v-if="currentUser.canManageUsers">
                <router-link class="btn btn-primary btn-sm" :to="`/admin/users/create`">
                    <span class="oi oi-plus"></span> {{ $t('New User') }}
                </router-link>
            </div>
        </header>

        <user-list></user-list>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import UserList from '../UserList';

    export default {
        name: 'users',
        components: {UserList},
        computed: {
            ...mapState({
                currentUser: state => state.users.current
            })
        }
    }
</script>

<style scoped>

</style>