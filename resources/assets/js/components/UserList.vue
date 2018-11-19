<template>
    <div>
        <filters
        :texts="texts"
        :voices="voices"
        :concerts="concerts"
        :fetch-users="fetchUsers"
        :filters="filters"
        :active-filters="activeFilters"
        :remove-filter="removeFilter"
        ></filters>

        <div class="loader" v-if="loading"></div>

        <div class="list-table" v-else-if="_users.length > 0">
            <header class="row">
                <div class="col-md-10 has-checkbox">
                    <input type="checkbox" class="check-all" name="check-all-users" data-controls="users[]">&nbsp;

                    <div class="dropdown list-actions">
                        <a class="dropdown-toggle no-caret" href="#" role="button" id="userActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="oi oi-ellipses"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="userActions">
                            <a class="dropdown-item" v-confirm="texts.actions.confirmArchiveMulti" href="/admin/users/multi-archive">
                                <span class="oi oi-box"></span> {{ texts.actions.archive }}
                            </a>
                            <a class="dropdown-item" data-href="/admin/voice/set" href="/admin/voice/set" data-toggle="modal" data-target="#mainModal">
                                <span class="oi oi-pulse"></span> {{ texts.actions.setVoice }}
                            </a>
                            <a v-if="showRoles" class="dropdown-item" data-href="/admin/role/set" href="/admin/role/set" data-toggle="modal" data-target="#mainModal">
                                <span class="oi oi-key"></span> {{ texts.actions.setRole }}
                            </a>
                        </div>
                    </div>

                    {{ texts.headings.user }}
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm" @click="changeSortDir">
                            <span :class="{'oi': true, 'oi-sort-ascending': filters.dir === 'ASC', 'oi-sort-descending': filters.dir === 'DESC'}"></span>
                        </button>

                        <button class="btn btn-default btn-sm dropdown-toggle dropdown-toggle-split" type="button" id="sortOrder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ sortOptions[this.filters.sort] }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortOrder">
                            <a @click="changeSort(key)" class="dropdown-item" href="#" v-for="(sortOption, key) in sortOptions">{{ sortOption }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 row-count">
                    {{ texts.total + ': ' + _users.length }}
                </div>
            </header>

            <ul class="users">
                <li class="row align-items-center" v-for="user in _users" :key="user.id">
                    <div :class="'col-md-' + (withAttendanceConfirmation || withAcceptDecline ? '8' : '11')">
                        <div class="flex align-items-center">
                            <input type="checkbox" name="users[]" :value="user.id">&nbsp;
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
                                        <small v-else class="badge badge-light badge-pill text-muted">({{ texts.noneSet }})</small>
                                    </a>
                                    <a v-if="showRoles" :href="'/admin/role/set/' + user.id" data-toggle="modal" data-target="#mainModal">
                                        <span v-if="user.roles && user.roles.length > 0" v-for="role in user.roles" :key="role.id" class="badge badge-info">
                                            {{ role.name }}
                                        </span>
                                        <small v-else class="badge badge-light text-muted">({{ texts.noneSet }})</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="withAttendanceConfirmation">
                        <attendance 
                            :routes="attendanceRoutes"
                            :user="user"
                            :texts="texts"
                        ></attendance>
                    </div>
                    <div class="col-md-3" v-if="withAcceptDecline">
                                <accept-decline
                                    :accept-route="acceptDeclineRoutes['accept'] + '/' + user.id"
                                    :decline-route="acceptDeclineRoutes['decline'] + '/' + user.id"
                                    :accepted="hasAccepted(user.id)"
                                    :declined="hasDeclined(user.id)"
                                    :texts="texts"
                                >
                                </accept-decline>
                    </div>
                    <div class="col-md-1 user-actions">
                        <a class="dropdown-toggle no-caret" href="#" :id="`singleUserActions${user.id}`" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="oi oi-ellipses"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" :aria-labelledby="`singleUserActions${user.id}`">
                            <a class="dropdown-item" :href="`/profile/edit/${user.id}`">
                                <span class="oi oi-pencil"></span> {{ texts.editProfile }}
                            </a>
                            <form method="POST" class="form-inline" :action="`/admin/users/${user.id}`">
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="submit" v-confirm="texts.actions.confirmArchive" class="btn btn-link dropdown-item">
                                    <span class="oi oi-box"></span> {{ texts.actions.archive }}
                                </button>
                                <input type="hidden" name="_token" :value="csrf">
                            </form>
                            <form method="POST" class="form-inline" :action="removeUserRoute">
                                <input name="_method" type="hidden" value="DELETE">
                                <input type="hidden" name="user_id" :value="user.id">
                                <button type="submit" v-confirm="texts.actions.confirmRemoveUser" class="btn btn-link dropdown-item">
                                    <span class="oi oi-minus"></span> {{ texts.actions.removeUser }}
                                </button>
                                <input type="hidden" name="_token" :value="csrf">
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div v-else class="no-results">{{ texts.noUsers }}</div>
    </div>
</template>

<script>
export default {
    props: {
        'texts': {
            type: Object
        }, 
        'concerts': {
            type: Array
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
        'promises': {
            type: Array
        }, 
        'denials': {
            type: Array
        }
    },
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            loading: false,
            _users: [],
            activeFilters: {},
            filters: {
                search: '',
                voices: [],
                concerts: [],
                ageFrom: '',
                ageTo: '',
                sort: 'surname',
                dir: 'ASC'
            }
        }
    },
    created() {
        this._users = this.users;
    },
    methods: {
        changeSortDir: function () {
            if (this.filters.dir === 'ASC') {
                this.filters.dir = 'DESC';
            } else {
                this.filters.dir = 'ASC';
            }

            this.fetchUsers();
        },
        changeSort: function(sort) {
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
            }, response => {})
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
        }
    }
}
</script>
