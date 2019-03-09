<template>
    <div>
        <div class="loader" v-if="loading"></div>

        <div v-else>
            <div class="row clearfix my-3">
               <div class="col text-right" v-if="currentUser.canManageProjects">
                    <a class="btn btn-default btn-sm" :href="`/admin/concerts/${concert.id}/addUser`" data-toggle="modal"
                       data-target="#mainModal">
                        <span class="oi oi-plus"></span> {{ $t('Add a participant') }}
                    </a>
                    <a class="btn btn-default btn-sm" :href="`/admin/concerts/export-participants/${concert.id}`">
                        <span class="oi oi-account-login"></span> {{ $t('Export') }}
                    </a>
                </div>
            </div>

            <user-list
                    :users="participants"
                    :can-manage-users="currentUser.canManageUsers"
                    :show-roles="false"
                    :voices="{}"
                    :fetch-users-action="`/concerts/load_participants/${concert.id}`"
                    :sort-options="{
                    firstname: $t('First Name'),
                    surname: $t('Surname'),
                    voice: $t('Voice'),
                    id: $t('Created at')
                }"
                    :set-voice-route="`/concerts/set_voice/${concert.id}`"
                    :remove-participants-route="`/concerts/${concert.id}`"
                    :actions="['removeParticipant', 'setVoice', 'editProfile']"
                    :rehearsals="[]"
                    :concerts="[]"
            ></user-list>
        </div>
    </div>
</template>

<script>
    import UserList from "../UserList";
    export default {
        components: {UserList},
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
            this.$store.dispatch('concerts/participants', this.concert.id).then(() => {
                this.loading = false;
            });
        }
    }
</script>