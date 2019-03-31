<template>
    <div>
        <loader v-if="loading"/>

        <div v-else>
            <header class="page-header">
                <h2>
                    {{ project.title }}
                </h2>

                <accept-decline
                        namespace="projects"
                        :id="project.id"
                        :accepted="project.accepted"
                        :declined="project.declined"
                        :deadline="project.deadline"
                >
                </accept-decline>

                <div class="main-actions" v-if="currentUser.canManageProjects">
                    <router-link class="btn btn-primary btn-sm" :to="`/admin/projects/edit/${project.id}`">
                        <span class="oi oi-pencil"></span> {{ $t('Edit') }}
                    </router-link>
                    <button class="btn btn-danger btn-sm" @click="remove">
                        <span class="oi oi-trash"></span> {{ $t('Delete') }}
                    </button>
                </div>
            </header>

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/projects/${project.id}`">{{ $t('Info') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/projects/${project.id}/rehearsals`">{{ $t('Rehearsals') }}
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="`/projects/${project.id}/comments`">{{ $t('Comments') }}
                    </router-link>
                </li>
                <li class="nav-item" v-if="currentUser.canManageProjects">
                    <router-link class="nav-link" :to="`/projects/${project.id}/participants`">{{ $t('Participants') }}</router-link>
                </li>
            </ul>

            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import Comments from "../Comment/Comments";
    import ProjectDetails from "./ProjectDetails";
    import AcceptDecline from "../AcceptDecline";
    import RehearsalList from "../Rehearsal/RehearsalList";
    import ProjectParticipants from "./ProjectParticipants";
    import Loader from "../Loader";

    export default {
        components: {ProjectParticipants, RehearsalList, AcceptDecline, Comments, ProjectDetails, Loader},
        data () {
            return {
                loading: true
            }
        },
        computed: {
            currentUser () {
                return this.$store.state.users.current;
            },
            project () {
                return this.$store.state.projects.project;
            }
        },
        mounted () {
            this.$store.dispatch('projects/show', this.$route.params.id)
                .then(() => {
                    this.loading = false;
                });
        },
        methods: {
            remove () {
                this.$dialog.confirm(this.$t('Do you really want to delete this project?'))
                    .then(() => {
                        this.$store.dispatch('projects/delete', this.project.id)
                            .then(() => {
                                this.$router.push(`/projects`);
                            });
                    })
                    .catch(function () {
                    });
            }
        }
    }
</script>