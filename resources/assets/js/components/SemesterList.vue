<template>
    <div>
        <div class="loader" v-if="loading"></div>

        <div class="list-table" v-else-if="items.length > 0">
            <header class="row">
                <div class="col-md-10">
                    {{ $t('Semester') }}
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm" @click="changeSortDir">
                            <span :class="{'oi': true, 'oi-sort-ascending': filters.dir === 'ASC', 'oi-sort-descending': filters.dir === 'DESC'}"></span>
                        </button>

                        <button class="btn btn-default btn-sm dropdown-toggle dropdown-toggle-split" type="button"
                                id="sortOrder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ sortOptions[this.filters.sort] }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortOrder">
                            <a @click="changeSort(key)" class="dropdown-item" href="#" :key="key"
                               v-for="(sortOption, key) in sortOptions">{{ sortOption }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 row-count">
                    {{ $t('Total') + ': ' + items.length }}
                </div>
            </header>

            <ul class="semesters">
                <a :href="`/semester/${semester.id}`" v-for="semester in items" :key="semester.id">
                    <li class="row align-items-center">
                        <div class="col-md-8">
                            <div class="flex align-items-center">
                                <div class="avatar avatar-default">
                                    <span>{{ semester.name }}</span>
                                </div>
                                <div class="name">
                                    {{ semester.name }}
                                    <div>
                                        <small class="text-muted">
                                            <span class="oi oi-calendar"></span> {{ semester.start_date }} – {{
                                            semester.end_date }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <accept-decline
                                    :accept-route="`/semester/accept/${semester.id}`"
                                    :decline-route="`/semester/decline/${semester.id}`"
                                    :accepted="hasAccepted(semester)"
                                    :declined="hasDeclined(semester)"
                            >
                            </accept-decline>
                        </div>
                        <div class="col-md-1 actions" v-if="canManageSemesters">
                            <a class="dropdown-toggle no-caret" href="#" :id="`singleActions${semester.id}`"
                               role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="oi oi-ellipses"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right"
                                 :aria-labelledby="`singleActions${semester.id}`">
                                <a class="dropdown-item" :href="`admin/semester/edit/${semester.id}`"
                                   v-if="hasAction('edit')">
                                    <span class="oi oi-pencil"></span> {{ $t('Edit') }}
                                </a>
                                <form method="POST" class="form-inline"
                                      :action="`/admin/semesters/${semester.id}`" v-if="hasAction('remove')">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button type="submit" v-confirm="$t('Do you really want to remove this semester?')"
                                            class="btn btn-link dropdown-item">
                                        <span class="oi oi-box"></span> {{ $t('Remove') }}
                                    </button>
                                    <input type="hidden" name="_token" :value="csrf">
                                </form>
                            </div>
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div v-else class="no-results">{{ $t('No results found.') }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            'user': {
                type: Object,
                required: true
            },
            'canManageSemesters': {
                type: [Boolean, Number]
            },
            'fetchAction': {
                type: String
            },
            'sortOptions': {
                type: Object
            },
            'actions': {
                type: Array
            }
        },
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                loading: false,
                items: [],
                activeFilters: {},
                filters: {
                    sort: 'start_date',
                    dir: 'ASC'
                }
            }
        },
        mounted() {
            this.fetchItems();
        },
        methods: {
            changeSortDir: function () {
                if (this.filters.dir === 'ASC') {
                    this.filters.dir = 'DESC';
                } else {
                    this.filters.dir = 'ASC';
                }

                this.fetchItems();
            },
            changeSort: function (sort) {
                this.filters.sort = sort;

                this.fetchItems();
            },
            fetchItems: function () {
                this.loading = true;

                for (let key in this.filters) {
                    if (this.filters[key].constructor === Array && this.filters[key].length > 0) {
                        this.activeFilters[key] = this.filters[key].join(', ');
                    } else if (typeof this.filters[key] === 'string' && this.filters[key].length > 0) {
                        this.activeFilters[key] = this.filters[key];
                    }
                }

                this.$http.get(this.fetchAction, {params: this.filters}).then(response => {
                    this.loading = false;
                    this.items = response.body;
                }, response => {
                })
            },
            hasAction: function (name) {
                return this.actions.indexOf(name) !== -1
            },
            hasAccepted: function (semester) {
                return semester.promises.filter(user => {
                    return user.id === this.user.id;
                }).length > 0;
            },
            hasDeclined: function (semester) {
                return semester.denials.filter(user => {
                    return user.id === this.user.id;
                }).length > 0;
            }
        }
    }
</script>
