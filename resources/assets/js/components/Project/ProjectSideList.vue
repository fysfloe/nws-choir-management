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
                            :accept-route="`/projects/accept/${project.id}`"
                            :decline-route="`/projects/decline/${project.id}`"
                            :accepted="project.has_accepted"
                            :declined="project.has_declined"
                            :deadline="project.deadline"
                            :show-dot="true"
                    >
                    </accept-decline>
                </li>
            </router-link>
        </ul>

        <small v-else class="text-muted">{{ $t('No projects found that belong to the semester.') }}</small>

        <a v-if="canManageProjects" class="btn btn-primary btn-sm mt-2" :href="addProjectRoute">
            {{ $t('Add a project') }}
        </a>
    </div>
</template>

<script>
    import AcceptDecline from "../AcceptDecline";
    export default {
        name: 'project-side-list',
        components: {AcceptDecline},
        props: {
            projects: {
                type: Array,
                required: true
            },
            canManageProjects: {
                type: [Boolean, Number],
                default: false
            },
            addProjectRoute: {
                type: String
            },
            user: {
                type: Object
            }
        }
    }
</script>