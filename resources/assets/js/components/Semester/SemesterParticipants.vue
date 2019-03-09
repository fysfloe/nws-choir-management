<template>
    <div>
        <div class="loader" v-if="loading"></div>

        <div v-else>
            <div class="row clearfix my-3">
                <div class="col text-right" v-if="currentUser.canManageSemesters">
                    <a class="btn btn-default btn-sm" :href="`/admin/semesters/${semester.id}/addUser`" data-toggle="modal"
                       data-target="#mainModal">
                        <span class="oi oi-plus"></span> {{ $t('Add a participant') }}
                    </a>
                    <a class="btn btn-default btn-sm" :href="`/admin/semesters/export-participants/${semester.id}`">
                        <span class="oi oi-account-login"></span> {{ $t('Export') }}
                    </a>
                </div>
            </div>

            <user-list
                    :users="participants"
                    :can-manage-users="currentUser.canManageUsers"
                    :show-roles="false"
                    :voices="{}"
                    :fetch-users-action="`/semesters/load_participants/${semester.id}`"
                    :sort-options="{
                    firstname: $t('First Name'),
                    surname: $t('Surname'),
                    voice: $t('Voice'),
                    id: $t('Created at')
                }"
                    :remove-participants-route="`/semesters/${semester.id}`"
                    :actions="['removeParticipant', 'editProfile']"
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
            this.$store.dispatch('semesters/participants', this.semester.id).then(() => {
                this.loading = false;
            });
        }
    }
</script>