<template>
    <div class="loader" v-if="loading"></div>

    <form v-else>
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
                            v-model="firstname"
                            name="firstname"
                            type="text"
                            :label="$t('First Name')"
                    ></form-group>

                    <form-group
                            class="col"
                            v-model="surname"
                            name="surname"
                            type="text"
                            :label="$t('Surname')"
                    ></form-group>
                </div>

                <form-group
                        v-model="username"
                        name="username"
                        type="text"
                        :label="$t('Username')"
                ></form-group>

                <form-group
                        v-model="birthdate"
                        name="birthdate"
                        type="date"
                        :label="$t('Birthdate')"
                ></form-group>

                <form-group
                        v-model="country_id"
                        name="country_id"
                        type="select"
                        :label="$t('Citizenship')"
                        :options="countries"
                ></form-group>

                <form-group
                        v-if="currentUser.canManageUsers"
                        v-model="non_singing"
                        name="non_singing"
                        type="checkbox"
                        :label="$t('This user is not a singer.')"
                        :info="$t('This user will not appear in the lists for rehearsals, concerts and projects.')"
                ></form-group>

                <form-group
                        v-if="currentUser.canManageUsers"
                        v-model="voice_id"
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
                        v-model="email"
                        name="email"
                        type="email"
                        :label="$t('E-Mail Address')"
                ></form-group>

                <form-group
                        v-model="phone"
                        name="phone"
                        type="text"
                        :label="$t('Phone')"
                ></form-group>

                <h3>{{ $t('Address') }}</h3>

                <form-group
                        v-model="street"
                        name="street"
                        type="text"
                        :label="$t('Street')"
                ></form-group>

                <div class="row">
                    <form-group
                            class="col-4"
                            v-model="zip"
                            name="zip"
                            type="number"
                            :label="$t('ZIP')"
                    ></form-group>

                    <form-group
                            class="col-8"
                            v-model="city"
                            name="city"
                            type="text"
                            :label="$t('City')"
                    ></form-group>
                </div>

                <form-group
                        v-model="address_country_id"
                        name="country_id"
                        type="select"
                        :label="$t('Country')"
                        :options="countries"
                ></form-group>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" @click.prevent="submit">{{ $t('Save Profile') }}</button>
        </div>
    </form>
</template>

<script>
    import FormGroup from "../FormGroup";
    import { mapState } from 'vuex';
    import PictureInput from 'vue-picture-input'
    import { mapFields } from 'vuex-map-fields';
    import VueFlashMessage from 'vue-flash-message';

    export default {
        name: 'user-profile-form',
        components: {FormGroup, PictureInput, VueFlashMessage},
        computed: {
            ...mapState({
                currentUser: state => state.users.current,
                user: state => state.users.user,
                countries: state => state.countries.options,
                voices: state => state.voices.options
            }),
            ...mapFields('users', {
                firstname: 'user.firstname',
                surname: 'user.surname',
                username: 'user.username',
                birthdate: 'user.birthdate',
                country_id: 'user.country_id',
                non_singing: 'user.non_singing',
                voice_id: 'user.voice_id',
                email: 'user.email',
                phone: 'user.phone',
                street: 'user.address.street',
                zip: 'user.address.zip',
                city: 'user.address.city',
                address_country_id: 'user.address.country_id'
            }),
            /*street () {
                return this.$store.state.users.user.address ?
                    this.$store.state.users.user.address.street :
                    null;
            },
            zip () {
                return this.$store.state.users.user.address ?
                    this.$store.state.users.user.address.zip :
                    null;
            },
            city () {
                return this.$store.state.users.user.address ?
                    this.$store.state.users.user.address.city :
                    null;
            },
            address_country_id () {
                return this.$store.state.users.user.address ?
                    this.$store.state.users.user.address.country_id :
                    null;
            }*/
        },
        data () {
            return {
                loading: true
            }
        },
        mounted () {
            this.$store.dispatch('users/show', this.$route.params.id)
                .then(() => this.loading = false);

            if (Object.entries(this.countries).length === 0) {
                this.$store.dispatch('countries/options');
            }

            if (Object.entries(this.voices).length === 0) {
                this.$store.dispatch('voices/options');
            }
        },
        methods: {
            submit () {
                this.$store.dispatch('users/edit', this.user)
                    .then(() => {
                        this.flashSuccess(this.$t('Profile successfully saved.'));
                    });
            }
        }
    }
</script>

<style scoped>

</style>