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
                    <a class="btn btn-default btn-sm" @click.prevent="exportParticipants" href>
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
                    :filters="filters"
            ></user-list>
        </div>
    </div>
</template>

<script>
    import UserList from "../UserList";
    import Loader from "../Loader";
    import AddParticipantsModal from "../AddParticipantsModal";
    import {mapState} from "vuex";

    export default {
        components: {Loader, UserList, AddParticipantsModal},
        computed: {
            participants () {
                return this.concert.participants;
            },
            ...mapState({
                concert: state => state.concerts.concert,
                currentUser: state => state.users.current,
                filters: state => state.concerts.participant_filters
            })
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
        },
        methods: {
            exportParticipants () {
                let filters = JSON.parse(JSON.stringify(this.filters));

                filters.voices = filters.voices.map(option => option.value);
                filters.concerts = filters.concerts.map(option => option.value);

                this.$store.dispatch('concerts/exportParticipants', {concert: this.concert, filters: filters});
            }
        }
    }
</script>
