<template>
    <div>
        <set-voice-modal
                :type="type"
        ></set-voice-modal>
        <multi-set-voice-modal
                :type="type"
        ></multi-set-voice-modal>

        <filters
                :voices="voices"
                :concerts="concerts"
                :fetch-items="fetchUsers"
                :filters="filters"
                :active-filters="activeFilters"
                :remove-filter="removeFilter"
        ></filters>

        <loader v-if="loading"/>

        <div class="list-table" v-else-if="_users.length > 0">
            <header class="row">
                <div class="col-md-10 has-checkbox">
                    <input type="checkbox" @click="checkAll" class="check-all" :checked="checkedAll">&nbsp;

                    <div class="dropdown list-actions" v-if="selectedUsers.length > 0">
                        <a class="dropdown-toggle no-caret" href="#" role="button" id="userActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="oi oi-ellipses"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="userActions">
                            <a v-if="hasAction('archive')" class="dropdown-item" href="/admin/users/multiArchive" @click.prevent="postUserAction($event, true, $t('Do you really want to archive these users?'))">
                                <span class="oi oi-box"></span> {{ $t('Archive') }}
                            </a>
                            <a v-b-modal.multiSetVoiceModal v-if="hasAction('setVoice')" class="dropdown-item">
                                <span class="oi oi-pulse"></span> {{ $t('Set voice') }}
                            </a>
                            <a v-if="showRoles && hasAction('setRole')" class="dropdown-item" data-href="/admin/role/set" :href="`/admin/role/set?${urlEncodeArray(selectedUsers, 'users')}`" data-toggle="modal" data-target="#mainModal">
                                <span class="oi oi-key"></span> {{ $t('Set role') }}
                            </a>
                            <a v-if="hasAction('removeParticipant')" class="dropdown-item"
                               @click.prevent="removeParticipants">
                                <span class="oi oi-minus"></span> {{ $t('Remove participants') }}
                            </a>
                        </div>
                    </div>

                    {{ $t('User') }}
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm" @click="changeSortDir">
                            <span :class="{'oi': true, 'oi-sort-ascending': filters.dir === 'ASC', 'oi-sort-descending': filters.dir === 'DESC'}"></span>
                        </button>

                        <button class="btn btn-default btn-sm dropdown-toggle dropdown-toggle-split" type="button" id="sortOrder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ sortOptions[this.filters.sort] }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortOrder">
                            <a @click="changeSort(key)" class="dropdown-item" href="#" :key="key" v-for="(sortOption, key) in sortOptions">{{ sortOption }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 row-count">
                    {{ $t('Total') + ': ' + _users.length }}
                </div>
            </header>

            <ul class="users">
                <li class="row align-items-center" v-for="user in _users" :key="user.id">
                    <div :class="'col-md-' + (withAttendanceConfirmation ? '8' : '11')">
                        <div class="flex align-items-center">
                            <input type="checkbox" @click="toggleUser($event, user.id)" :value="user.id" :checked="selectedUsers.indexOf(user.id) !== -1">&nbsp;
                            <div class="avatar" v-if="user.avatar">
                                <img :src="'/storage/avatars/' + user.avatar" :alt="user.firstname + ' ' + user.surname">
                            </div>
                            <div class="avatar avatar-default" v-else>
                                <span class="oi oi-person"></span>
                            </div>
                            <div class="name">
                                {{ user.firstname }} {{ user.surname }}
                                <div>
                                    <a href="#" @click.prevent="$store.commit('users/SHOW', user)"
                                       v-b-modal.setVoiceModal>
                                        <span class="badge badge-secondary badge-pill" v-if="user.voice">
                                            {{ user.voice.name }}
                                        </span>
                                        <small v-else class="badge badge-light badge-pill text-muted">({{ $t('None set') }})</small>
                                    </a>
                                    <a v-if="showRoles" :href="'/admin/role/set/' + user.id" data-toggle="modal" data-target="#mainModal">
                                        <span v-if="user.roles && user.roles.length > 0" v-for="role in user.roles" :key="role.id" class="badge badge-info">
                                            {{ role.name }}
                                        </span>
                                        <small v-else class="badge badge-light text-muted">({{ $t('None set') }})</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="withAttendanceConfirmation">
                        <attendance
                                :user-id="user.id"
                                :type="type"
                        ></attendance>
                    </div>
                    <div class="col-md-1 user-actions" v-if="permission">
                        <a class="dropdown-toggle no-caret" href="#" :id="`singleUserActions${user.id}`" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="oi oi-ellipses"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" :aria-labelledby="`singleUserActions${user.id}`">
                            <router-link class="dropdown-item" :to="`/profile/edit/${user.id}`"
                                         v-if="hasAction('editProfile')">
                                <span class="oi oi-pencil"></span> {{ $t('Edit profile') }}
                            </router-link>
                            <form method="POST" class="form-inline" :action="`/admin/users/${user.id}`" v-if="hasAction('archive')">
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="submit" v-confirm="$t('Do you really want to archive this user?')" class="btn btn-link dropdown-item">
                                    <span class="oi oi-box"></span> {{ $t('Archive') }}
                                </button>
                                <input type="hidden" name="_token" :value="csrf">
                            </form>
                            <button type="submit" @click="removeParticipant(user.id)"
                                    class="btn btn-link dropdown-item">
                                <span class="oi oi-minus"></span> {{ $t('Remove participant') }}
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <no-results v-else></no-results>
    </div>
</template>

<script>
    import Attendance from "./Attendance";
    import Filters from "./Filters";
    import {mapState} from 'vuex';
    import SetVoiceModal from "./User/SetVoiceModal";
    import MultiSetVoiceModal from "./User/MultiSetVoiceModal";
    import Loader from "./Loader";
    import NoResults from "./NoResults";

    export default {
        components: {Loader, MultiSetVoiceModal, SetVoiceModal, Filters, Attendance, NoResults},
        props: {
            type: {
                type: String,
                default: ''
            },
            users: {
                type: Array
            },
            showRoles: {
                type: Boolean
            },
            sortOptions: {
                type: Object,
                default () {
                    return {
                        firstname: this.$t('Firstname'),
                        surname: this.$t('Surname'),
                        voice: this.$t('Voice'),
                        id: this.$t('Created at')
                    };
                }
            },
            withAttendanceConfirmation: {
                type: Boolean
            },
            removeUserRoute: {
                type: String
            },
            promises: {
                type: Array
            },
            denials: {
                type: Array
            },
            actions: {
                type: Array,
                default () {
                    return ['setVoice', 'editProfile', 'setRole', 'archive']
                }
            }
        },
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                loading: false,
                activeFilters: {},
                showSetVoiceModal: false
            }
        },
        computed: {
            ...mapState({
                concerts: state => {
                    let optionsArray = [];

                    for (let option in state.concerts.options) {
                        optionsArray.push({
                            label: state.concerts.options[option],
                            value: option
                        });
                    }

                    return optionsArray;
                },
                voices: state => {
                    let optionsArray = [];

                    for (let option in state.voices.options) {
                        optionsArray.push({
                            label: state.voices.options[option],
                            value: option
                        });
                    }

                    return optionsArray;
                },
                filters: state => state.users.filters,
                selectedUsers: state => state.users.selected,
                currentUser: state => state.users.current
            }),
            checkedAll: {
                get() {
                    return this.selectedUsers.length === this._users.length;
                }
            },
            _users () {
                return this.users ? this.users : this.$store.state.users.items;
            },
            resource() {
                return this.$store.state[`${this.type}s`][this.type];
            },
            permission () {
                if (this.type) {
                    let permission = 'canManage' + this.type.charAt(0).toUpperCase() + this.type.slice(1);

                    return this.currentUser[permission];
                } else {
                    return this.currentUser.canManageUsers;
                }
            }
        },
        mounted() {
            if (!this.users) {
                this.$store.dispatch('users/fetch');
            }

            this.$store.dispatch('voices/options');
            this.$store.dispatch('concerts/options');
        },
        methods: {
            toggleUser: function (event, id) {
                if (event.target.checked) {
                    this.selectedUsers.push(id);
                    this.$store.dispatch('users/select', this.selectedUsers);
                } else {
                    this.selectedUsers.splice(this.selectedUsers.indexOf(id), 1);
                    this.$store.dispatch('users/select', this.selectedUsers);
                }
            },
            removeParticipants() {
                this.$dialog.confirm(this.$t('Do you really want to remove these participants?'))
                    .then(() => {
                        if (this.type) {
                            this.$store.dispatch(`${this.type}s/removeParticipants`, {
                                id: this.resource.id,
                                userIds: this.selectedUsers
                            });
                        }

                        this.$store.commit('users/DESELECT');
                    })
            },
            removeParticipant(id) {
                this.$dialog.confirm(this.$t('Do you really want to remove this user?'))
                    .then(() => {
                        if (this.type) {
                            this.$store.dispatch(`${this.type}s/removeParticipants`, {
                                id: this.resource.id,
                                userIds: [id]
                            });
                        }

                        this.$store.commit('users/DESELECT');
                    })
            },
            checkAll: function (event) {
                if (event.target.checked) {
                    this.$store.dispatch('users/select', this._users.map(user => user.id));
                } else {
                    this.$store.dispatch('users/select', []);
                }
            },
            changeSortDir: function () {
                if (this.filters.dir === 'ASC') {
                    this.filters.dir = 'DESC';
                } else {
                    this.filters.dir = 'ASC';
                }

                this.fetchUsers();
            },
            changeSort: function (sort) {
                this.filters.sort = sort;

                this.fetchUsers();
            },
            fetchUsers: function () {
                this.loading = true;

                let filters = JSON.parse(JSON.stringify(this.filters));

                filters.voices = filters.voices.map(option => option.value);
                filters.concerts = filters.concerts.map(option => option.value);

                if (this.type) {
                    this.$store.dispatch(`${this.type}s/participants`, {id: this.resource.id, filters: filters})
                        .then(() => this.loading = false)
                } else {
                    this.$store.dispatch('users/fetch', filters)
                        .then(() => this.loading = false);
                }
            },
            removeFilter: function (key) {
                delete this.activeFilters[key];
                if (typeof this.filters[key] === 'string') {
                    this.filters[key] = '';
                } else if (this.filters[key].constructor === Array) {
                    this.filters[key] = [];
                }

                this.fetchUsers();
            },
            hasAccepted: function (id) {
                return this.promises.filter(user => {
                    return user.id === id;
                }).length > 0;
            },
            hasDeclined: function (id) {
                return this.denials.filter(user => {
                    return user.id === id;
                }).length > 0;
            },
            hasAction: function (name) {
                return this.actions.indexOf(name) !== -1
            }
        }
    }
</script>
