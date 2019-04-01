<template>
    <div>
        <add-participants-modal
                type="project"
        ></add-participants-modal>

        <loader v-if="loading"/>

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
                    <button :disabled="project.other_users && project.other_users.length === 0"
                            class="btn btn-default btn-sm" v-b-modal.addParticipantsModal>
                        <span class="oi oi-plus"></span> {{ $t('Add a participant') }}
                    </button>
                    <a class="btn btn-default btn-sm" :href="`/admin/project/export-participants/${project.id}`">
                        <span class="oi oi-account-login"></span> {{ $t('Export') }}
                    </a>
                </div>
            </div>

            <user-list
                    type="project"
                    v-show="view === 'list'"
                    :users="participants"
                    :show-roles="false"
                    :remove-participants-route="`/projects/${project.id}`"
                    :actions="['removeParticipant', 'setVoice', 'editProfile']"
            ></user-list>

            <project-grid
                    v-if="view === 'table'"
                    :rehearsals="project.rehearsals"
                    :concerts="project.concerts"
                    :users="participants"
            ></project-grid>
        </div>
    </div>
</template>

<script>
    import UserList from "../UserList";
    import ProjectGrid from "./ProjectGrid";
    import Loader from "../Loader";
    import AddParticipantsModal from '../AddParticipantsModal';

    export default {
        components: {Loader, ProjectGrid, UserList, AddParticipantsModal},
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
            this.$store.dispatch('projects/participants', {id: this.project.id}).then(() => {
                this.loading = false;
            });
        }
    }
</script>