<template>
    <div>
        <header class="page-header">
            <h2>{{ $t('Semesters') }}</h2>

            <div class="main-actions" v-if="currentUser.canManageSemesters">
                <router-link class="btn btn-primary btn-sm" to="/admin/semesters/create">
                    {{ $t('New Semester') }}
                </router-link>
            </div>
        </header>

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
                <router-link :to="`/semesters/${semester.id}`" v-for="semester in items" :key="semester.id">
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
                                    :accept-route="`/semesters/accept/${semester.id}`"
                                    :decline-route="`/semesters/decline/${semester.id}`"
                                    :accepted="semester.accepted"
                                    :declined="semester.declined"
                            >
                            </accept-decline>
                        </div>
                        <div class="col-md-1 actions" v-if="currentUser.canManageSemesters">
                            <a class="dropdown-toggle no-caret" href="#" :id="`singleActions${semester.id}`"
                               role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="oi oi-ellipses"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right"
                                 :aria-labelledby="`singleActions${semester.id}`">
                                <a class="dropdown-item" :href="`/admin/semesters/${semester.id}/edit`"
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
                </router-link>
            </ul>
        </div>
        <div v-else class="no-results">{{ $t('No results found.') }}</div>
    </div>
</template>

<script>
    import AcceptDecline from "../AcceptDecline";
    export default {
        components: {AcceptDecline},
        props: {
            sortOptions: {
                type: Object,
                default () {
                    return {
                        'start_date': this.$t('Start Date')
                    };
                }
            },
            actions: {
                type: Array,
                default () {
                    return ['remove', 'edit']
                }
            }
        },
        computed: {
            currentUser () {
                return this.$store.state.users.current;
            },
            items () {
                return this.$store.state.semesters.items;
            }
        },
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                loading: false,
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

                this.$store.dispatch('semesters/fetch', this.filters)
                    .then(() => {
                        this.loading = false;
                    });
            },
            hasAction: function (name) {
                return this.actions.indexOf(name) !== -1
            }
        }
    }
</script>