<template>
    <div>
        <loader v-if="loading"/>

        <div v-else>
            <b-breadcrumb :items="breadcrumbs"></b-breadcrumb>

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

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/rehearsals/${rehearsal.id}`">{{ $t('Info') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/rehearsals/${rehearsal.id}/comments`">{{ $t('Comments') }}
                    </router-link>
                </li>
                <li class="nav-item" v-if="currentUser.canManageRehearsals">
                    <router-link class="nav-link" :to="`/rehearsals/${rehearsal.id}/participants`">{{ $t('Participants')
                        }}
                    </router-link>
                </li>
            </ul>

            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import RehearsalDetails from "./RehearsalDetails";
    import {mapState} from 'vuex';
    import AcceptDecline from "../AcceptDecline";
    import Loader from "../Loader";

    export default {
        name: 'rehearsal',
        components: {Loader, RehearsalDetails, AcceptDecline},
        computed: {
            ...mapState({
                rehearsal: state => state.rehearsals.rehearsal,
                currentUser: state => state.users.current
            }),
            breadcrumbs () {
                return [
                    {
                        text: this.$t('Dashboard'),
                        to: '/'
                    },
                    {
                        text: this.$t('Projects'),
                        to: '/projects'
                    },
                    {
                        text: this.rehearsal.project.title,
                        to: '/projects/' + this.rehearsal.project.id
                    },
                    {
                        text: this.rehearsal.title,
                        active: true
                    }
                ]
            }
        },
        data () {
            return {
                loading: true
            }
        },
        beforeRouteUpdate (to, from, next) {
            this.$store.dispatch('rehearsals/show', to.params.id)
              .then(() => {
                this.loading = false;
                next();
              });
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
