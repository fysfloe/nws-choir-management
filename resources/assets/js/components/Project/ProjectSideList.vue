<template>
    <div>
        <h3>{{ $t('Projects') }}</h3>
        <ul class="projects" v-if="projects.length > 0">
            <router-link :to="`/projects/${project.id}`" v-for="project in projects" :key="project.id">
                <li>
                    <span>
                        {{ project.title }}
                    </span>
                    <accept-decline
                            class="text-right"
                            namespace="projects"
                            :id="project.id"
                            :accepted="project.accepted"
                            :declined="project.declined"
                            :deadline="project.deadline"
                            :show-dot="true"
                    >
                    </accept-decline>
                </li>
            </router-link>
        </ul>

        <small v-else class="text-muted">{{ $t('No projects found that belong to the semester.') }}</small>

        <router-link v-if="user.canManageProjects" class="btn btn-primary btn-sm mt-2" :to="addProjectRoute">
            {{ $t('Add a project') }}
        </router-link>
    </div>
</template>

<script>
    import AcceptDecline from "../AcceptDecline";
    import { mapState } from 'vuex';

    export default {
        name: 'project-side-list',
        components: {AcceptDecline},
        props: {
            projects: {
                type: Array,
                required: true
            },
            addProjectRoute: {
                type: String
            }
        },
        computed: {
            ...mapState({
                user: state => state.users.current
            })
        }
    }
</script>