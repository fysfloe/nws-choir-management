<template>
    <div>
        <add-participants-modal
                type="rehearsal"
        ></add-participants-modal>

        <loader v-if="loading"/>

        <div v-else>
            <div class="row clearfix my-3">
                <div class="col text-right" v-if="currentUser.canManageProjects">
                    <button :disabled="rehearsal.other_users && rehearsal.other_users.length === 0"
                            class="btn btn-default btn-sm" v-b-modal.addParticipantsModal>
                        <span class="oi oi-plus"></span> {{ $t('Add a participant') }}
                    </button>
                    <a class="btn btn-default btn-sm" :href="`/admin/rehearsal/export-participants/${rehearsal.id}`">
                        <span class="oi oi-account-login"></span> {{ $t('Export') }}
                    </a>
                </div>
            </div>

            <user-list
                    type="rehearsal"
                    :users="participants"
                    :show-roles="false"
                    :sort-options="{
                        firstname: $t('First Name'),
                        surname: $t('Surname'),
                        voice: $t('Voice'),
                        id: $t('Created at')
                    }"
                    :actions="['removeParticipant', 'editProfile']"
                    :with-attendance-confirmation="true"
            ></user-list>
        </div>
    </div>
</template>

<script>
    import UserList from "../UserList";
    import Loader from "../Loader";
    import AddParticipantsModal from "../AddParticipantsModal";

    export default {
        components: {Loader, UserList, AddParticipantsModal},
        computed: {
            rehearsal() {
                return this.$store.state.rehearsals.rehearsal;
            },
            currentUser() {
                return this.$store.state.users.current;
            },
            participants() {
                return this.rehearsal.participants;
            }
        },
        data() {
            return {
                view: 'list',
                loading: true
            }
        },
        mounted() {
            this.$store.dispatch('rehearsals/participants', {id: this.rehearsal.id}).then(() => {
                this.loading = false;
            });
        }
    }
</script>