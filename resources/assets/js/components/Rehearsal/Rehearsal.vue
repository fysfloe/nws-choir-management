<template>
    <div>
        <div class="loader" v-if="loading"></div>

        <div v-else>
            <header class="page-header">
                <h2>
                    {{ rehearsal.title }}
                </h2>

                <accept-decline
                        namespace="rehearsals"
                        :id="rehearsal.id"
                        :accepted="rehearsal.accepted"
                        :declined="rehearsal.declined"
                        :deadline="rehearsal.deadline"
                >
                </accept-decline>

                <div class="main-actions" v-if="currentUser.canManageRehearsals">
                    <router-link class="btn btn-primary btn-sm" :to="`/admin/rehearsals/edit/${rehearsal.id}`">
                        <span class="oi oi-pencil"></span> {{ $t('Edit') }}
                    </router-link>
                    <button class="btn btn-danger btn-sm" @click="remove">
                        <span class="oi oi-trash"></span> {{ $t('Delete') }}
                    </button>
                </div>
            </header>

            <rehearsal-details></rehearsal-details>
        </div>
    </div>
</template>

<script>
    import RehearsalDetails from "./RehearsalDetails";
    import { mapState } from 'vuex';
    import AcceptDecline from "../AcceptDecline";

    export default {
        name: 'rehearsal',
        components: {RehearsalDetails, AcceptDecline},
        computed: {
            ...mapState({
                rehearsal: state => state.rehearsals.rehearsal,
                currentUser: state => state.users.current
            })
        },
        data () {
            return {
                loading: true
            }
        },
        mounted () {
            this.$store.dispatch('rehearsals/show', this.$route.params.id)
                .then(() => {
                    this.loading = false;
                });
        },
        methods: {
            remove () {
                this.$dialog.confirm(this.$t('Do you really want to delete this rehearsal?'))
                    .then(() => {
                        this.$store.dispatch('rehearsals/delete', this.rehearsal.id)
                            .then(() => {
                                this.$router.push(`/projects/${this.rehearsal.project.id}`);
                                this.flashInfo(this.$t('Rehearsal deleted.'));
                            });
                    })
                    .catch(function () {
                    });
            }
        }
    }
</script>

<style scoped>

</style>