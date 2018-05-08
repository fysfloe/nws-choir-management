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
                <div class="col-md-7 has-checkbox">
                    <input type="checkbox" class="check-all" name="check-all-users" data-controls="users[]">&nbsp;

                    <div class="dropdown list-actions">
                        <a class="dropdown-toggle" href="#" role="button" id="userActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </a>

                        <div class="dropdown-menu" aria-labelledby="userActions">
                            <a class="dropdown-item" @click="confirm(texts.confirmArchive)" data-href="/admin/users/multi-archive" href="/admin/users/multi-archive"><span class="oi oi-box"></span> {{ texts.actions.archive }}</a>
                            <a class="dropdown-item" data-href="admin/voice/set" href="/admin/voice/set" data-toggle="modal" data-target="#mainModal"><span class="oi oi-pulse"></span> {{ texts.actions.setVoice }}</a>
                            <a class="dropdown-item" data-href="/admin/role/set" href="/admin/role/set" data-toggle="modal" data-target="#mainModal"><span class="oi oi-key"></span> {{ texts.actions.setRole }}</a>
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
                <div class="col-md-2">
                    {{ texts.headings.voice }}
                    <a :class="{'list-sort': true, 'active': filters.sort === 'voice' && filters.dir === 'ASC'}" @click="sortBy('voice', 'ASC')">
                        <span class="oi oi-caret-top"></span>
                    </a>
                    <a :class="{'list-sort': true, 'active': filters.sort === 'voice' && filters.dir === 'DESC'}" @click="sortBy('voice', 'DESC')">
                        <span class="oi oi-caret-bottom"></span>
                    </a>
                </div>
                <div class="col-md-2">{{ texts.headings.role }}</div>
                <div class="col-md-1">&nbsp;</div>
            </header>

            <ul class="users">
                <li class="row" v-for="user in _users">
                    <div class="col-md-7">
                        <input type="checkbox" name="users[]" :value="user.id">&nbsp;
                        {{ user.firstname }} {{ user.surname }}
                        <a v-if="canManageUsers" :href="'/profile/edit/' + user.id" class="btn-link btn-sm">
                            <span class="oi oi-pencil" data-toggle="tooltip" :title="texts.editProfile"></span>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <span v-if="user.voice_id">
                            {{ voices[user.voice_id] }}
                        </span>
                        <small v-else class="text-muted">({{ texts.noneSet }})</small>

                        <a :href="'/voice/showSet/' + user.id" data-toggle="modal" data-target="#mainModal" class="btn-link btn-sm">
                            <span class="oi oi-pulse" data-toggle="tooltip" :title="texts.actions.setVoice"></span>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <span v-for="role in user.roles">{{ role.display_name }}</span>&nbsp;

                        <a :href="'/admin/role/set' + user.id" data-toggle="modal" data-target="#mainModal" class="btn-link btn-sm">
                            <span class="oi oi-key" data-toggle="tooltip" :title="texts.actions.setRole"></span>
                        </a>
                    </div>
                    <div class="col-md-1">
                        <form @submit="confirm(texts.actions.confirmArchive)" method="POST" class="form-inline" action="/admin/users/1">
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="submit" class="btn btn-primary btn-xs" data-toggle="tooltip" :title="texts.actions.archive">
                                <span class="oi oi-box"></span>
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
        <div v-else class="no-results">{{ texts.noUsers }}</div>
    </div>
</template>

<script>
export default {
    props: ['texts', 'voices', 'concerts', 'users', 'canManageUsers', 'roles'],
    data() {
        return {
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

            this.$http.get('/admin/load-users', {params: this.filters}).then(response => {
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
