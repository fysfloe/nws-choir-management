<template>
    <div>
        <add-participants-modal
                type="semester"
        ></add-participants-modal>

        <loader v-if="loading"/>

        <div v-else>
            <div class="row clearfix my-3">
                <div class="col text-right" v-if="currentUser.canManageSemesters">
                    <button :disabled="semester.other_users && semester.other_users.length === 0"
                            class="btn btn-default btn-sm" v-b-modal.addParticipantsModal>
                        <span class="oi oi-plus"></span> {{ $t('Add a participant') }}
                    </button>
                    <a class="btn btn-default btn-sm" :href="`/admin/semesters/export-participants/${semester.id}`">
                        <span class="oi oi-account-login"></span> {{ $t('Export') }}
                    </a>
                </div>
            </div>

            <user-list
                    type="semester"
                    :users="participants"
                    :show-roles="false"
                    :actions="['removeParticipant', 'editProfile']"
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
            semester () {
                return this.$store.state.semesters.semester;
            },
            currentUser () {
                return this.$store.state.users.current;
            },
            participants () {
                return this.semester.participants;
            }
        },
        data() {
            return {
                loading: true
            }
        },
        mounted () {
            this.$store.dispatch('semesters/participants', {id: this.semester.id}).then(() => {
                this.loading = false;
            });
        }
    }
</script>