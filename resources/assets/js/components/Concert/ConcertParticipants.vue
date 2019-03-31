<template>
    <div>
        <add-participants-modal
                type="concert"
        ></add-participants-modal>

        <loader v-if="loading"/>

        <div v-else>
            <div class="row clearfix my-3">
                <div class="col text-right" v-if="currentUser.canManageConcerts">
                    <button :disabled="concert.other_users && concert.other_users.length === 0"
                            class="btn btn-default btn-sm" v-b-modal.addParticipantsModal>
                        <span class="oi oi-plus"></span> {{ $t('Add a participant') }}
                    </button>
                    <a class="btn btn-default btn-sm" :href="`/admin/concerts/export-participants/${concert.id}`">
                        <span class="oi oi-account-login"></span> {{ $t('Export') }}
                    </a>
                </div>
            </div>

            <user-list
                    type="concert"
                    :users="participants"
                    :show-roles="false"
                    :actions="['removeParticipant', 'setVoice', 'editProfile']"
                    with-attendance-confirmation
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
            concert () {
                return this.$store.state.concerts.concert;
            },
            currentUser () {
                return this.$store.state.users.current;
            },
            participants () {
                return this.concert.participants;
            }
        },
        data() {
            return {
                loading: true
            }
        },
        mounted () {
            this.$store.dispatch('concerts/participants', {id: this.concert.id}).then(() => {
                this.loading = false;
            });
        }
    }
</script>