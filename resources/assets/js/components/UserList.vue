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
                    <a :class="{'list-sort': true, 'active': filters.sort === 'surname' && filters.dir === 'ASC'}" @click="sortBy('surname', 'ASC')">
                        <span class="oi oi-caret-top"></span>
                    </a>
                    <a :class="{'list-sort': true, 'active': filters.sort === 'surname' && filters.dir === 'DESC'}" @click="sortBy('surname', 'DESC')">
                        <span class="oi oi-caret-bottom"></span>
                    </a>
                </div>
                <div class="col-md-2 row-count">
                    {{ texts.total + ': ' + _users.length }}
                </div>
            </header>

            <ul class="users">
                <li class="row align-items-center" v-for="user in _users">
                    <div class="col-md-10">
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
                                    <a :href="'/voice/showSet/' + user.id" data-toggle="modal" data-target="#mainModal">
                                        <span class="badge badge-secondary badge-pill" v-if="user.voice">
                                            {{ user.voice.name }}
                                        </span>
                                        <small v-else class="badge badge-light badge-pill text-muted">({{ texts.noneSet }})</small>
                                    </a>
                                    <a v-if="showRoles" :href="'/admin/role/set/' + user.id" data-toggle="modal" data-target="#mainModal">
                                        <span v-if="user.roles && user.roles.length > 0" v-for="role in user.roles" class="badge badge-info">
                                            {{ role.name }}
                                        </span>
                                        <small v-else class="badge badge-light text-muted">({{ texts.noneSet }})</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">

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
                        </div>
                        <!-- <form @submit="confirm(texts.actions.confirmArchive)" method="POST" class="form-inline" :action="`/admin/users/${user.id}`">
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="submit" class="btn btn-primary">
                                <span class="oi oi-box"></span> {{ texts.actions.archive }}
                            </button>
                            <input type="hidden" name="_token" :value="csrf">
                        </form> -->
                    </div>
                </li>
            </ul>
        </div>
        <div v-else class="no-results">{{ texts.noUsers }}</div>
    </div>
</template>

<script>
export default {
    props: ['texts', 'concerts', 'users', 'canManageUsers', 'showRoles', 'fetchUsersAction', 'voices'],
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
                sort: 'id',
                dir: 'ASC'
            }
        }
    },
    created() {
        this._users = this.users;
    },
    methods: {
        sortBy: function(sort, dir) {
            this.filters.sort = sort;
            this.filters.dir = dir;

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
        }
    }
}
</script>
