<template>
    <form action="/profile/update" method="POST">
        <header class="page-header">
            <div v-if="currentUser.id === user.id">
                <h2>{{ $t('Edit Profile') }}</h2>
                <router-link class="btn btn-default btn-sm" to="/profile/changePassword">
                    {{ $t('Change Password') }}
                </router-link>
            </div>
            <h2 v-else>{{ $t('Edit Profile') }}: <span class="text-muted">{{ user.firstname }} {{ user.surname }}</span></h2>
        </header>

        <div class="row">
            <div class="col">
                <h3>{{ $t('Personal Data') }}</h3>

                <div class="row">
                    <form-group
                            class="col"
                            :value="user.firstname"
                            name="firstname"
                            type="text"
                            :label="$t('First Name')"
                    ></form-group>

                    <form-group
                            class="col"
                            :value="user.surname"
                            name="surname"
                            type="text"
                            :label="$t('Surname')"
                    ></form-group>
                </div>

                <form-group
                        :value="user.username"
                        name="username"
                        type="text"
                        :label="$t('Username')"
                ></form-group>

                <form-group
                        :value="user.birthdate"
                        name="birthdate"
                        type="date"
                        :label="$t('Birthdate')"
                ></form-group>

                <form-group
                        :value="user.citizenship"
                        name="citizenship"
                        type="select"
                        :label="$t('Citizenship')"
                        :options="countries"
                ></form-group>

                <form-group
                        v-if="currentUser.canManageUsers"
                        :value="user.non_singing"
                        name="non_singing"
                        type="checkbox"
                        :label="$t('This user is not a singer.')"
                        :info="$t('This user will not appear in the lists for rehearsals, concerts and projects.')"
                ></form-group>

                <form-group
                        v-if="currentUser.canManageUsers"
                        :value="user.voice_id"
                        name="voice_id"
                        type="select"
                        :label="$t('Voice')"
                        :info="$t('This is your primary voice.')"
                        :options="voices"
                ></form-group>

                <div class="form-group">
                    <picture-input
                            ref="avatar"
                            name="avatar"
                            id="avatar"
                            radius="50"
                            width="150"
                            height="150"
                            accept="image/jpeg,image/png,image/jpg"
                            size="10"
                            button-class="btn-sm btn-primary"
                            :prefill="user.avatar ? `/storage/avatars/${user.avatar}` : ''"
                    >
                    </picture-input>
                </div>
            </div>

            <div class="col">
                <h3>{{ $t('Contact Data') }}</h3>

                <form-group
                        :value="user.email"
                        name="email"
                        type="email"
                        :label="$t('E-Mail Address')"
                ></form-group>

                <form-group
                        :value="user.phone"
                        name="phone"
                        type="text"
                        :label="$t('Phone')"
                ></form-group>

                <h3>{{ $t('Address') }}</h3>

                <form-group
                        :value="user.street"
                        name="street"
                        type="text"
                        :label="$t('Street')"
                ></form-group>

                <div class="row">
                    <form-group
                            class="col-4"
                            :value="user.zip"
                            name="zip"
                            type="number"
                            :label="$t('ZIP')"
                    ></form-group>

                    <form-group
                            class="col-8"
                            :value="user.city"
                            name="city"
                            type="text"
                            :label="$t('City')"
                    ></form-group>
                </div>

                <form-group
                        :value="user.country_id"
                        name="country_id"
                        type="select"
                        :label="$t('Country')"
                        :options="countries"
                ></form-group>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">{{ $t('Save Profile') }}</button>
        </div>
    </form>
</template>

<script>
    import FormGroup from "../FormGroup";
    import { mapState } from 'vuex';
    import PictureInput from 'vue-picture-input';

    export default {
        name: 'user-profile-form',
        components: {FormGroup},
        computed: {
            ...mapState({
                currentUser: state => state.users.current,
            }),
            user () {
                return this.$store.state.users.current;
            }
        },
        data () {
            return {
                countries: {},
                voices: {}
            }
        }
    }
</script>

<style scoped>

</style>