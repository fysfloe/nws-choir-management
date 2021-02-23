<template>
    <div>
        <loader v-if="loading"/>

        <div v-else>
            <header class="page-header">
                <h2>
                    {{ $t('Concert') }}: {{ concert.title }}
                </h2>

                <accept-decline
                        namespace="concerts"
                        :id="concert.id"
                        :accepted="concert.accepted"
                        :declined="concert.declined"
                        :deadline="concert.deadline"
                >
                </accept-decline>

                <div class="main-actions" v-if="currentUser.canManageConcerts">
                    <router-link class="btn btn-primary btn-sm" :to="`/admin/concerts/edit/${concert.id}`">
                        <span class="oi oi-pencil"></span> {{ $t('Edit') }}
                    </router-link>
                    <button class="btn btn-danger btn-sm" @click="remove">
                        <span class="oi oi-trash"></span> {{ $t('Delete') }}
                    </button>
                </div>
            </header>

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/concerts/${concert.id}`">{{ $t('Info') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/concerts/${concert.id}/rehearsals`">{{ $t('Rehearsals') }}
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/concerts/${concert.id}/comments`">{{ $t('Comments') }}
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/concerts/${concert.id}/participants`">{{ $t('Participants')
                        }}
                    </router-link>
                </li>
            </ul>

            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import Comments from "../Comment/Comments";
    import ConcertDetails from "./ConcertDetails";
    import ConcertParticipants from "./ConcertParticipants";
    import RehearsalList from "../Rehearsal/RehearsalList";
    import AcceptDecline from "../AcceptDecline";
    import Loader from "../Loader";

    export default {
        components: {Loader, AcceptDecline, RehearsalList, Comments, ConcertDetails, ConcertParticipants},
        data () {
            return {
                loading: true
            }
        },
        computed: {
            currentUser () {
                return this.$store.state.users.current;
            },
            concert () {
                return this.$store.state.concerts.concert;
            }
        },
        beforeRouteUpdate (to, from, next) {
            this.$store.dispatch('concerts/show', to.params.id)
                .then(() => {
                    this.loading = false;
                    next();
                });
        },
        mounted () {
            this.$store.dispatch('concerts/show', this.$route.params.id)
                .then(() => {
                    this.loading = false;
                });
        },
        methods: {
            remove () {
                this.$dialog.confirm(this.$t('Do you really want to delete this concert?'))
                    .then(() => {
                        this.$store.dispatch('concerts/delete', this.concert.id)
                            .then(() => {
                                this.$router.push(`/concerts`);
                                this.flashInfo(this.$t('Concert deleted.'));
                            });
                    })
                    .catch(function () {
                    });
            }
        }
    }
</script>
