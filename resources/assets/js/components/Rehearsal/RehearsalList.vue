<template>
    <div>
        <loader v-if="loading"/>

        <div class="list-table" v-else-if="items.length > 0">
            <header class="row">
                <div class="col-md-10">
                    {{ $t('Rehearsal') }}
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
                    {{ $t('Total') + ': ' + items.length }}
                </div>
            </header>

            <ul class="rehearsals">
                <a :href="`/rehearsals/${rehearsal.id}`" v-for="rehearsal in items" :key="rehearsal.id">
                    <li class="row align-items-center">
                        <div class="col-md-8">
                            <div class="flex align-items-center">
                                <div class="avatar avatar-default">
                                    <span>{{ rehearsal.date.day }}</span>
                                </div>
                                <div class="name">
                                    {{ rehearsal.title }}
                                    <div>
                                        <small class="text-muted">
                                            <span class="oi oi-clock"></span> {{ rehearsal.start_time }} – {{ rehearsal.end_time }}
                                            <span class="ml-2 oi oi-map-marker"></span> {{ rehearsal.place }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <accept-decline
                                    namespace="rehearsals"
                                    :id="rehearsal.id"
                                    :accepted="rehearsal.accepted"
                                    :declined="rehearsal.declined"
                                    :deadline="rehearsal.deadline"
                            >
                            </accept-decline>
                        </div>
                        <div class="col-md-1 actions" v-if="currentUser.canManageRehearsals">
                            <a class="dropdown-toggle no-caret" href="#" :id="`singleActions${rehearsal.id}`" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="oi oi-ellipses"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" :aria-labelledby="`singleActions${rehearsal.id}`">
                                <a class="dropdown-item" :href="`/admin/rehearsal/edit/${rehearsal.id}`"
                                   v-if="hasAction('edit')">
                                    <span class="oi oi-pencil"></span> {{ $t('Edit') }}
                                </a>
                                <form method="POST" class="form-inline" :action="`/admin/rehearsals/delete/${rehearsal.id}`" v-if="hasAction('remove')">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button type="submit" v-confirm="$t('Do you really want to remove this rehearsal?')" class="btn btn-link dropdown-item">
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
    import AcceptDecline from "../AcceptDecline";
    import Loader from "../Loader";

    export default {
        components: {Loader, AcceptDecline},
        props: {
            sortOptions: {
                type: Object,
                default () {
                    return {
                        'date': this.$t('Date')
                    };
                }
            },
            actions: {
                type: Array
            },
            actionParameters: {
                type: Object,
                default () {
                    return {};
                }
            }
        },
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                loading: false,
                activeFilters: {},
                filters: {
                    sort: 'date',
                    dir: 'ASC'
                }
            }
        },
        computed: {
            items () {
                return this.$store.state.rehearsals.items;
            },
            currentUser () {
                return this.$store.state.users.current;
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
            changeSort: function(sort) {
                this.filters.sort = sort;

                this.fetchItems();
            },
            fetchItems: function () {
                this.loading = true;

                this.$store.dispatch('rehearsals/fetch', Object.assign(this.filters, {project_id: this.$route.params.id})).then(() => {
                    this.loading = false;
                });
            },
            hasAction: function (name) {
                return this.actions.indexOf(name) !== -1
            }
        }
    }
</script>
