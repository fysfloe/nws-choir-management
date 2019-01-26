<template>
    <div>
        <filters
                :voices="voices"
                :concerts="concerts"
                :fetch-items="fetchUsers"
                :filters="filters"
                :active-filters="activeFilters"
                :remove-filter="removeFilter"
        ></filters>

        <div class="loader" v-if="loading"></div>

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
                            <a v-if="hasAction('setVoice')" class="dropdown-item" data-href="/admin/voice/set" :href="`/admin/voice/set?${urlEncodeArray(selectedUsers, 'users')}`" data-toggle="modal" data-target="#mainModal">
                                <span class="oi oi-pulse"></span> {{ $t('Set voice') }}
                            </a>
                            <a v-if="showRoles && hasAction('setRole')" class="dropdown-item" data-href="/admin/role/set" :href="`/admin/role/set?${urlEncodeArray(selectedUsers, 'users')}`" data-toggle="modal" data-target="#mainModal">
                                <span class="oi oi-key"></span> {{ $t('Set role') }}
                            </a>
                            <a v-if="hasAction('removeParticipant')" class="dropdown-item" :href="removeParticipantsRoute" @click.prevent="postUserAction($event, true, $t('Do you really want to remove these participants?'))">
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
                    <div :class="'col-md-' + (withAttendanceConfirmation || withAcceptDecline ? '8' : '11')">
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
                                    <a :href="setVoiceRoute + '/' + user.id" data-toggle="modal" data-target="#mainModal">
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
                                :routes="attendanceRoutes"
                                :user="user"
                        ></attendance>
                    </div>
                    <div class="col-md-3" v-if="withAcceptDecline">
                        <accept-decline
                                :accept-route="acceptDeclineRoutes['accept'] + '/' + user.id"
                                :decline-route="acceptDeclineRoutes['decline'] + '/' + user.id"
                                :accepted="hasAccepted(user.id)"
                                :declined="hasDeclined(user.id)"
                        >
                        </accept-decline>
                    </div>
                    <div class="col-md-1 user-actions">
                        <a class="dropdown-toggle no-caret" href="#" :id="`singleUserActions${user.id}`" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="oi oi-ellipses"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" :aria-labelledby="`singleUserActions${user.id}`">
                            <a class="dropdown-item" :href="`/profile/edit/${user.id}`" v-if="hasAction('editProfile')">
                                <span class="oi oi-pencil"></span> {{ $t('Edit profile') }}
                            </a>
                            <form method="POST" class="form-inline" :action="`/admin/users/${user.id}`" v-if="hasAction('archive')">
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="submit" v-confirm="$t('Do you really want to archive this user?')" class="btn btn-link dropdown-item">
                                    <span class="oi oi-box"></span> {{ $t('Archive') }}
                                </button>
                                <input type="hidden" name="_token" :value="csrf">
                            </form>
                            <form method="POST" class="form-inline" :action="removeUserRoute" v-if="hasAction('removeParticipant')">
                                <input name="_method" type="hidden" value="DELETE">
                                <input type="hidden" name="user_id" :value="user.id">
                                <button type="submit" v-confirm="$t('Do you really want to remove this user?')" class="btn btn-link dropdown-item">
                                    <span class="oi oi-minus"></span> {{ $t('Remove participant') }}
                                </button>
                                <input type="hidden" name="_token" :value="csrf">
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div v-else class="no-results">{{ $t('No users found.') }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            'concerts': {
                type: [Array, Object]
            },
            'rehearsals': {
                type: [Array, Object]
            },
            'users': {
                type: Array
            },
            'canManageUsers': {
                type: [Boolean, Number]
            },
            'showRoles': {
                type: Boolean
            },
            'fetchUsersAction': {
                type: String
            },
            'voices': {
                type: Object
            },
            'sortOptions': {
                type: Object
            },
            'setVoiceRoute': {
                type: String
            },
            'withAttendanceConfirmation': {
                type: Boolean
            },
            'attendanceRoutes': {
                type: Object
            },
            'withAcceptDecline': {
                type: Boolean
            },
            'acceptDeclineRoutes': {
                type: Object
            },
            'removeUserRoute': {
                type: String
            },
            'removeParticipantsRoute': {
                type: String
            },
            'promises': {
                type: Array
            },
            'denials': {
                type: Array
            },
            'actions': {
                type: Array
            },
            'filters': {
                type: Object,
                default: () => {
                    return {
                        search: '',
                        voices: [],
                        concerts: [],
                        ageFrom: '',
                        ageTo: '',
                        sort: 'surname',
                        dir: 'ASC',
                        accepted: '1'
                    }
                }
            }
        },
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                loading: false,
                _users: [],
                activeFilters: {},
                selectedUsers: []
            }
        },
        computed: {
            checkedAll: {
                get() {
                    return this.selectedUsers.length === this._users.length;
                }
            }
        },
        created() {
            this._users = this.users;
        },
        mounted() {
        },
        methods: {
            toggleUser: function (event, id) {
                if (event.target.checked) {
                    this.selectedUsers.push(id);
                } else {
                    this.selectedUsers.splice(this.selectedUsers.indexOf(id), 1);
                }
            },
            postUserAction: function (event, confirm, confirmMessage) {
                if (confirm) {
                    this.$dialog.confirm(confirmMessage)
                        .then(dialog => {
                            let route = event.target.getAttribute('href');

                            this.$http.post(route, {users: this.selectedUsers, _token: this.csrf})
                                .then(response => {
                                    this.fetchUsers();
                                }, response => {
                                    console.log(response);
                                });
                        });
                }
            },
            checkAll: function (event) {
                if (event.target.checked) {
                    this.selectedUsers = this._users.map(user => user.id);
                } else {
                    this.selectedUsers = [];
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

                for (let key in this.filters) {
                    if (this.filters[key].constructor === Array && this.filters[key].length > 0) {
                        this.activeFilters[key] = this.filters[key].join(', ');
                    } else if (typeof this.filters[key] === 'string' && this.filters[key].length > 0) {
                        this.activeFilters[key] = this.filters[key];
                    }
                }

                this.$http.get(this.fetchUsersAction, {params: this.filters}).then(response => {
                    this.loading = false;
                    this._users = response.body;
                }, response => {
                })
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
