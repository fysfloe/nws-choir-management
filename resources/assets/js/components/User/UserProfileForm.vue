<template>
    <resource-form
            namespace="users"
            resource-key="user"
            :resource="user"
            :is-edit="!isCreate"
    >
        <template v-slot:editTitle>
            <span v-if="currentUser.id === user.id">
                {{ $t('Edit Profile') }}
                <router-link class="btn btn-default btn-sm" to="/profile/changePassword">
                    {{ $t('Change Password') }}
                </router-link>
            </span>
            <h2 v-else>{{ $t('Edit Profile') }}: <span class="text-muted">{{ user.firstname }} {{ user.surname }}</span></h2>
        </template>

        <template v-slot:createTitle>
            {{ $t('Create User') }}
        </template>

        <template v-slot:form>
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
                                validate="required"
                        ></form-group>

                        <form-group
                                class="col"
                                v-model="surname"
                                name="surname"
                                type="text"
                                :label="$t('Surname')"
                                validate="required"
                        ></form-group>
                    </div>

                    <form-group
                            v-model="username"
                            name="username"
                            type="text"
                            :label="$t('Username')"
                            validate="required"
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
                            validate="required"
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
                            validate="required|email"
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
        </template>

        <template v-slot:submitButton>
            {{ $t('Save User') }}
        </template>
    </resource-form>
</template>

<script>
    import FormGroup from "../FormGroup";
    import {mapState} from 'vuex';
    import PictureInput from 'vue-picture-input'
    import {mapFields} from 'vuex-map-fields';
    import ResourceForm from '../ResourceForm';

    export default {
        name: 'user-profile-form',
        components: {FormGroup, PictureInput, ResourceForm},
        props: {
            isCreate: {
                type: Boolean,
                default: false
            }
        },
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
            })
        },
        data () {
            return {
                loading: true
            }
        },
        mounted () {
            if (this.$route.params.id) {
                this.$store.dispatch('users/SHOW', this.$route.params.id)
                    .then(() => this.loading = false);
            } else {
                this.loading = false;
                this.$store.commit('users/SHOW', {
                    firstname: '',
                    surname: '',
                    username: '',
                    birthdate: '',
                    country_id: null,
                    non_singing: false,
                    voice_id: null,
                    email: '',
                    phone: '',
                    address: {
                        street: '',
                        zip: '',
                        city: '',
                        country_id: null
                    }
                });
            }

            if (Object.entries(this.countries).length === 0) {
                this.$store.dispatch('countries/options');
            }

            if (Object.entries(this.voices).length === 0) {
                this.$store.dispatch('voices/options');
            }
        },
        methods: {
            submit () {
                if (this.isCreate) {
                    this.$store.dispatch('users/add', this.user)
                        .then(() => {
                            this.flashSuccess(this.$t('User successfully created.'));
                        });
                } else {
                    this.$store.dispatch('users/edit', this.user)
                        .then(() => {
                            this.flashSuccess(this.$t('Profile successfully saved.'));
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>