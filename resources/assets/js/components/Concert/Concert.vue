<template>
    <div>
        <div class="loader" v-if="loading"></div>

        <div v-else>
            <header class="page-header">
                <h2>
                    {{ concert.title }}
                </h2>

                <accept-decline
                        :accept-route="`/concerts/accept/${concert.id}`"
                        :decline-route="`/concerts/decline/${concert.id}`"
                        :accepted="concert.accepted"
                        :declined="concert.declined"
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

            <b-tabs>
                <b-tab :title="$t('Info')">
                    <concert-details></concert-details>
                </b-tab>
                <b-tab :title="$t('Rehearsals')">
                    <rehearsal-list
                        :action-parameters="{
                            concert_id: concert.id
                        }"
                        :actions="['remove', 'edit']"
                    ></rehearsal-list>
                </b-tab>
                <b-tab :title="$t('Comments')">
                    <comments
                        :action-parameters="{
                            commentable_id: concert.id
                        }"
                    ></comments>
                </b-tab>
                <b-tab :title="$t('Participants')">
                    <concert-participants
                            :show-roles="false"
                    ></concert-participants>
                </b-tab>
            </b-tabs>

        </div>
    </div>
</template>

<script>
    import Comments from "../Comment/Comments";
    import ConcertDetails from "./ConcertDetails";
    import ConcertParticipants from "./ConcertParticipants";
    import RehearsalList from "../Rehearsal/RehearsalList";
    import AcceptDecline from "../AcceptDecline";

    export default {
        components: {AcceptDecline, RehearsalList, Comments, ConcertDetails, ConcertParticipants},
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
                            });
                    })
                    .catch(function () {
                    });
            }
        }
    }
</script>