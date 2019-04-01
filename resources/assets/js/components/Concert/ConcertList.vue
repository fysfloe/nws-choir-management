<template>
    <div>
        <header class="page-header">
            <h2>{{ $t('Concerts') }}</h2>

            <div class="main-actions" v-if="currentUser.canManageConcerts">
                <router-link class="btn btn-primary btn-sm" to="/admin/concerts/create">
                    <span class="oi oi-plus"></span> {{ $t('New Concert') }}
                </router-link>
            </div>
        </header>

        <loader v-if="loading"/>

        <div class="list-table" v-else-if="items.length > 0">
            <header class="row">
                <div class="col-md-10">
                    {{ $t('Concert') }}
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

            <ul class="concerts">
                <router-link :to="`/concerts/${concert.id}`" v-for="concert in items" :key="concert.id">
                    <li class="row align-items-center">
                        <div class="col-md-8">
                            <div class="flex align-items-center">
                                <div class="avatar avatar-default">
                                    <span class="oi oi-musical-note"></span>
                                </div>
                                <div class="name">
                                    {{ concert.title }}
                                    <div>
                                        <small class="text-muted">
                                            <span class="oi oi-calendar"></span> {{ concert.date }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <accept-decline
                                    namespace="concerts"
                                    :id="concert.id"
                                    :accepted="concert.accepted"
                                    :declined="concert.declined"
                                    :deadline="concert.deadline"
                            >
                            </accept-decline>
                        </div>
                        <div class="col-md-1 actions" v-if="currentUser.canManageConcerts">
                            <b-dropdown variant="link" no-caret>
                                <template slot="button-content">
                                    <span class="oi oi-ellipses"></span>
                                </template>
                                <router-link class="dropdown-item" @click.stop :to="`/admin/concerts/edit/${concert.id}`"
                                             v-if="hasAction('edit')">
                                    <span class="oi oi-pencil"></span> {{ $t('Edit') }}
                                </router-link>
                                <button @click.stop.prevent="remove(concert.id)" class="btn btn-link dropdown-item">
                                    <span class="oi oi-box"></span> {{ $t('Remove') }}
                                </button>
                            </b-dropdown>
                        </div>
                    </li>
                </router-link>
            </ul>
        </div>
        <no-results v-else :action="currentUser.canManageConcerts ? '/admin/concerts/create' : ''" :button-text="$t('New Concert')"></no-results>
    </div>
</template>

<script>
    import AcceptDecline from "../AcceptDecline";
    import Loader from "../Loader";
    import NoResults from "../NoResults";

    export default {
        components: {Loader, AcceptDecline, NoResults},
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
                return this.$store.state.concerts.items;
            }
        },
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                loading: true,
                activeFilters: {},
                filters: {
                    sort: 'date',
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

                this.$store.dispatch('concerts/fetch', this.filters)
                    .then(() => {
                        this.loading = false;
                    });
            },
            hasAction: function (name) {
                return this.actions.indexOf(name) !== -1
            },
            remove (id) {
                this.$dialog.confirm(this.$t('Do you really want to remove this concert?'))
                    .then(() => {
                        this.$store.dispatch(`concerts/delete`, id);
                    })
            }
        }
    }
</script>
