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
                    <a class="btn btn-default btn-sm" @click.prevent="exportParticipants" href>
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
                    :filters="filters"
            ></user-list>
        </div>
    </div>
</template>

<script>
    import UserList from "../UserList";
    import Loader from "../Loader";
    import AddParticipantsModal from "../AddParticipantsModal";
    import {mapState} from 'vuex';

    export default {
        components: {Loader, UserList, AddParticipantsModal},
        computed: {
            participants() {
                return this.rehearsal.participants;
            },
            ...mapState({
                currentUser: state => state.users.current,
                rehearsal: state => state.rehearsals.rehearsal,
                filters: state => state.rehearsals.participant_filters
            })
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
        },
        methods: {
            exportParticipants () {
                let filters = JSON.parse(JSON.stringify(this.filters));

                filters.voices = filters.voices.map(option => option.value);
                filters.concerts = filters.concerts.map(option => option.value);

                this.$store.dispatch('rehearsals/exportParticipants', {rehearsal: this.rehearsal, filters: filters});
            }
        }
    }
</script>
