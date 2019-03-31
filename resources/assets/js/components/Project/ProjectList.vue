<template>
    <div>
        <header class="page-header">
            <h2>{{ $t('Projects') }}</h2>

            <div class="main-actions" v-if="currentUser.canManageProjects">
                <router-link class="btn btn-primary btn-sm" to="admin/projects/create">
                    <span class="oi oi-plus"></span> {{ $t('New Project') }}
                </router-link>
            </div>
        </header>

        <filters
                :fetch-items="fetchItems"
                :filters="filters"
                :active-filters="activeFilters"
                :remove-filter="removeFilter"
        ></filters>

        <loader v-if="loading"/>

        <div class="list-table" v-else-if="items.length > 0">
            <header class="row">
                <div :class="{'col-md-10': true, 'has-checkbox': currentUser.canManageProjects}">
                    <input v-if="currentUser.canManageProjects" type="checkbox" @click="checkAll" class="check-all" :checked="checkedAll">&nbsp;

                    <div class="dropdown list-actions" v-if="selectedItems.length > 0">
                        <a class="dropdown-toggle no-caret" href="#" role="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="oi oi-ellipses"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="actions">
                            <a v-if="hasAction('remove')" class="dropdown-item" href="/admin/projects/remove_mutli"
                               @click.prevent="postAction($event, true, $t('Do you really want to remove these projects?'))">
                                <span class="oi oi-box"></span> {{ $t('Remove') }}
                            </a>
                        </div>
                    </div>

                    {{ $t('Project') }}
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

            <ul class="projects">
                <router-link :to="`/projects/${project.id}`" v-for="project in items" :key="project.id">
                    <li class="row align-items-center">
                        <div class="col-md-11">
                            <div class="flex align-items-center">
                                <input v-if="currentUser.canManageProjects" type="checkbox" @click.stop="toggleItem($event, project.id)" :value="project.id" :checked="selectedItems.indexOf(project.id) !== -1">&nbsp;
                                <div class="avatar avatar-default">
                                    <span class="oi oi-musical-note"></span>
                                </div>
                                <div class="name">
                                    {{ project.title }}
                                    <small>
                                        <span class="text-success oi oi-check" :title="$t('You are attending!')" v-if="project.accepted === true"></span>
                                        <span class="text-danger oi oi-x" :title="$t('You are not attending.')" v-else-if="project.declined === true"></span>
                                        <span class="text-muted oi oi-question-mark" :title="$t('You did not answer yet.')" v-else></span>
                                    </small>
                                    <div>
                                        <small class="text-muted">
                                            <span class="oi oi-plus"></span> {{ project.created_at }}
                                            <span class="ml-2" v-if="currentUser.canManageProjects">
                                                <span class="oi oi-person"></span> {{ project.creator }}
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 actions" v-if="currentUser.canManageProjects">
                            <b-dropdown variant="link" no-caret>
                                <template slot="button-content">
                                    <span class="oi oi-ellipses"></span>
                                </template>
                                <router-link class="dropdown-item" @click.stop :to="`/admin/projects/edit/${project.id}`"
                                             v-if="hasAction('edit')">
                                    <span class="oi oi-pencil"></span> {{ $t('Edit') }}
                                </router-link>
                                <button @click.stop.prevent="remove(project.id)" class="btn btn-link dropdown-item">
                                    <span class="oi oi-box"></span> {{ $t('Remove') }}
                                </button>
                            </b-dropdown>
                        </div>
                    </li>
                </router-link>
            </ul>
        </div>
        <no-results v-else :action="currentUser.canManageProjects ? '/admin/projects/create' : ''" :button-text="$t('New Project')"></no-results>
    </div>
</template>

<script>
    import Filters from "../Filters";
    import Loader from "../Loader";
    import NoResults from "../NoResults";

    export default {
        components: {Loader, Filters, NoResults},
        props: {
            fetchAction: {
                type: String
            },
            sortOptions: {
                type: Object,
                default () {
                    return {
                        'title': this.$t('Title')
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
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                loading: false,
                activeFilters: {},
                selectedItems: [],
                filters: {
                    search: '',
                    sort: 'title',
                    dir: 'ASC'
                }
            }
        },
        computed: {
            checkedAll: {
                get () {
                    return this.selectedItems.length === this.items.length;
                }
            },
            items () {
                return this.$store.state.projects.items;
            },
            currentUser () {
                return this.$store.state.users.current;
            }
        },
        mounted() {
            this.fetchItems();
        },
        methods: {
            toggleItem: function (event, id) {
                if (event.target.checked) {
                    this.selectedItems.push(id);
                } else {
                    this.selectedItems.splice(this.selectedItems.indexOf(id), 1);
                }
            },
            checkAll: function (event) {
                if (event.target.checked) {
                    this.selectedItems = this.items.map(item => item.id);
                } else {
                    this.selectedItems = [];
                }
            },
            postAction: function (event, confirm, confirmMessage) {
                if (confirm) {
                    this.$dialog.confirm(confirmMessage)
                        .then(dialog => {
                            let route = event.target.getAttribute('href');

                            this.$http.post(route, {users: this.selectedItems, _token: this.csrf})
                                .then(response => {
                                    this.fetchItems();
                                }, response => {
                                    console.log(response);
                                });
                        });
                }
            },
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
                this.$store.dispatch('projects/fetch', this.filters)
                    .then(() => {
                        this.loading = false;
                    });
            },
            removeFilter: function (key) {
                delete this.activeFilters[key];
                if (typeof this.filters[key] === 'string') {
                    this.filters[key] = '';
                } else if (this.filters[key].constructor === Array) {
                    this.filters[key] = [];
                }

                this.fetchItems();
            },
            hasAction: function (name) {
                return this.actions.indexOf(name) !== -1
            },
            remove (id) {
                this.$dialog.confirm(this.$t('Do you really want to remove this project?'))
                    .then(() => {
                        this.$store.dispatch(`projects/delete`, id);
                    })
            }
        }
    }
</script>
