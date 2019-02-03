<template>
    <div>
        <div class="row clearfix my-3">
            <div class="col">
                <button type="button" class="btn btn-primary btn-sm" @click="changeView">
                    <span :class="{'oi': true, 'oi-list': view === 'table', 'oi-grid-three-up': view === 'list'}"></span>
                    <span v-if="view === 'table'">{{ $t('List view') }}</span>
                    <span v-else-if="view === 'list'">{{ $t('Table view') }}</span>
                </button>
            </div>

            <div class="col text-right" v-if="canManageProjects">
                <a class="btn btn-default btn-sm" :href="`/admin/project/${projectId}/addUser`" data-toggle="modal"
                   data-target="#mainModal">
                    <span class="oi oi-plus"></span> {{ $t('Add a participant') }}
                </a>
                <a class="btn btn-default btn-sm" :href="`/admin/project/export-participants/${projectId}`">
                    <span class="oi oi-account-login"></span> {{ $t('Export') }}
                </a>
            </div>
        </div>

        <user-list
                v-show="view === 'list'"
                :users="users"
                :can-manage-users="canManageUsers"
                :show-roles="false"
                :voices="voices"
                :fetch-users-action="fetchUsersAction"
                :sort-options="{
                    firstname: $t('First Name'),
                    surname: $t('Surname'),
                    voice: $t('Voice'),
                    id: $t('Created at')
                }"
                :set-voice-route="setVoiceRoute"
                :remove-participants-route="removeParticipantsRoute"
                :actions="['removeParticipant', 'setVoice', 'editProfile']"
                :rehearsals="rehearsals"
                :concerts="concerts"
        ></user-list>

        <project-grid
                v-show="view === 'table'"
                :rehearsals="rehearsals"
                :concerts="concerts"
                :users="users"
        ></project-grid>
    </div>
</template>

<script>
    export default {
        props: {
            'projectId': {
                type: Number
            },
            'concerts': {
                type: [Array, Object]
            },
            'rehearsals': {
                type: [Array, Object]
            },
            'users': {
                type: Array
            },
            'canManageUsers': {
                type: [Boolean, Number]
            },
            'canManageProjects': {
                type: [Boolean, Number]
            },
            'fetchUsersAction': {
                type: String
            },
            'voices': {
                type: Object
            },
            'setVoiceRoute': {
                type: String
            },
            'removeParticipantsRoute': {
                type: String
            }
        },
        data() {
            return {
                view: 'list'
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
        }
    }
</script>