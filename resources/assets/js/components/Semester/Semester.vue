<template>
    <div>
        <loader v-if="loading"/>

        <div v-else>
            <header class="page-header">
                <h2>
                    {{ semester.name }}
                </h2>

                <accept-decline
                        namespace="semesters"
                        :id="semester.id"
                        :accepted="semester.accepted"
                        :declined="semester.declined"
                        :deadline="semester.deadline"
                >
                </accept-decline>

                <div class="main-actions" v-if="currentUser.canManageSemesters">
                    <router-link class="btn btn-primary btn-sm" :to="`/admin/semesters/edit/${semester.id}`">
                        <span class="oi oi-pencil"></span> {{ $t('Edit') }}
                    </router-link>
                    <button class="btn btn-danger btn-sm" @click="remove">
                        <span class="oi oi-trash"></span> {{ $t('Delete') }}
                    </button>
                </div>
            </header>

            <b-tabs>
                <b-tab :title="$t('Info')">
                    <semester-details></semester-details>
                </b-tab>
                <b-tab :title="$t('Participants')">
                    <semester-participants
                            :show-roles="false"
                    ></semester-participants>
                </b-tab>
            </b-tabs>

        </div>
    </div>
</template>

<script>
    import Comments from "../Comment/Comments";
    import SemesterDetails from "./SemesterDetails";
    import SemesterParticipants from "./SemesterParticipants";
    import AcceptDecline from "../AcceptDecline";
    import Loader from "../Loader";

    export default {
        components: {Loader, AcceptDecline, Comments, SemesterDetails, SemesterParticipants},
        data () {
            return {
                loading: true
            }
        },
        computed: {
            currentUser () {
                return this.$store.state.users.current;
            },
            semester () {
                return this.$store.state.semesters.semester;
            }
        },
        mounted () {
            this.$store.dispatch('semesters/show', this.$route.params.id)
                .then(() => {
                    this.loading = false;
                });
        },
        methods: {
            remove () {
                this.$dialog.confirm(this.$t('Do you really want to delete this semester?'))
                    .then(() => {
                        this.$store.dispatch('semesters/delete', this.semester.id)
                            .then(() => {
                                this.$router.push(`/semesters`);
                            });
                    })
                    .catch(function () {
                    });
            }
        }
    }
</script>