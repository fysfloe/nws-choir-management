<template>
    <b-modal @ok="submit" id="addParticipantsModal" :title="$t('Add a participant')">
        <form-group
                v-model="selectedUsers"
                name="user_id"
                type="multiselect"
                :label="$t('User')"
                :options="users"
                validate="required"
        ></form-group>
    </b-modal>
</template>

<script>
    import FormGroup from "./FormGroup";

    export default {
        name: 'add-participants-modal',
        components: {FormGroup},
        props: {
            type: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                selectedUsers: []
            }
        },
        computed: {
            users() {
                return this.$store.state[`${this.type}s`][this.type].other_users ? this.$store.state[`${this.type}s`][this.type].other_users.map((user) => {
                    return {
                        label: user.firstname + ' ' + user.surname,
                        value: user.id
                    };
                }) : []
            },
            resource() {
                return this.type ? this.$store.state[`${this.type}s`][this.type] : null;
            }
        },
        created() {
            this.$store.dispatch(`${this.type}s/otherUsers`, this.resource.id)
        },
        methods: {
            submit() {
                this.$store.dispatch(`${this.type}s/addParticipants`, {
                    id: this.resource.id,
                    userIds: this.selectedUsers.map(user => user.value)
                })
            }
        }
    }
</script>

<style scoped>

</style>