<template>
    <div>
        <div class="loader" v-if="loading"></div>

        <div v-else>
            <div class="row clearfix my-3">
                <div class="col">
                    <button type="button" class="btn btn-primary btn-sm" @click="changeView">
                        <span :class="{'oi': true, 'oi-list': view === 'table', 'oi-grid-three-up': view === 'list'}"></span>
                        <span v-if="view === 'table'">{{ $t('List view') }}</span>
                        <span v-else-if="view === 'list'">{{ $t('Table view') }}</span>
                    </button>
                </div>

                <div class="col text-right" v-if="currentUser.canManageProjects">
                    <a class="btn btn-default btn-sm" :href="`/admin/project/${project.id}/addUser`" data-toggle="modal"
                       data-target="#mainModal">
                        <span class="oi oi-plus"></span> {{ $t('Add a participant') }}
                    </a>
                    <a class="btn btn-default btn-sm" :href="`/admin/project/export-participants/${project.id}`">
                        <span class="oi oi-account-login"></span> {{ $t('Export') }}
                    </a>
                </div>
            </div>

            <user-list
                    v-show="view === 'list'"
                    :users="participants"
                    :can-manage-users="currentUser.canManageUsers"
                    :show-roles="false"
                    :voices="{}"
                    :fetch-users-action="`/projects/load_participants/${project.id}`"
                    :sort-options="{
                    firstname: $t('First Name'),
                    surname: $t('Surname'),
                    voice: $t('Voice'),
                    id: $t('Created at')
                }"
                    :set-voice-route="`/projects/set_voice/${project.id}`"
                    :remove-participants-route="`/projects/${project.id}`"
                    :actions="['removeParticipant', 'setVoice', 'editProfile']"
                    :rehearsals="[]"
                    :concerts="[]"
            ></user-list>

            <project-grid
                    v-show="view === 'table'"
                    :rehearsals="[]"
                    :concerts="[]"
                    :users="participants"
            ></project-grid>
        </div>
    </div>
</template>

<script>
    import UserList from "../UserList";
    import ProjectGrid from "./ProjectGrid";
    export default {
        components: {ProjectGrid, UserList},
        computed: {
            project () {
                return this.$store.state.projects.project;
            },
            currentUser () {
                return this.$store.state.users.current;
            },
            participants () {
                return this.project.participants;
            }
        },
        data() {
            return {
                view: 'list',
                loading: true
            }
        },
        methods: {
            changeView() {
                if (this.view === 'list') {
                    this.view = 'table';
                } else {
                    this.view = 'list';
                }
            }
        },
        mounted () {
            this.$store.dispatch('projects/participants', this.project.id).then(() => {
                this.loading = false;
            });
        }
    }
</script>