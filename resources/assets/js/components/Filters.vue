<template>
    <div class="filters">
        <div id="filtersInner" class="collapse show">
            <form class="filter-form" @submit.prevent="filter">
                <div class="filter-form-fields">
                    <div class="form-group" v-if="filters.hasOwnProperty('search')">
                        <label for="search" class="control-label">{{ $t('Search') }}</label>
                        <input name="search" id="search" v-model="filters.search" class="form-control">
                    </div>

                    <div class="form-group" v-if="filters.hasOwnProperty('voices')">
                        <label for="voices" class="control-label">{{ $t('Voices') }}</label>
                        <multiselect selectLabel="" deselectLabel="" :options="voices" name="voices[]" id="voices" label="label" track-by="value" multiple="multiple" v-model="filters.voices"></multiselect>
                    </div>

                    <div class="form-group" v-if="filters.hasOwnProperty('concerts')">
                        <label for="concerts" class="control-label">{{ $t('Concerts') }}</label>
                        <multiselect selectLabel="" deselectLabel="" :options="concerts" name="concerts[]" id="concerts" label="label" track-by="value" multiple="multiple" v-model="filters.concerts"></multiselect>
                    </div>

                    <div class="form-group age-filter" v-if="filters.hasOwnProperty('ageFrom') || filters.hasOwnProperty('ageTo')">
                        <label for="age" class="control-label">{{ $t('Age') }}</label>
                        <div class="input-group">
                            <input type="number" name="age-from" min="10" max="110" class="form-control" v-model="filters.ageFrom" placeholder="Min">
                            <input type="number" name="age-to" min="10" max="110" class="form-control" v-model="filters.ageTo" placeholder="Max">
                        </div>
                    </div>

                    <div class="form-group" v-if="filters.hasOwnProperty('accepted')">
                        <label for="accepted" class="control-label">{{ $t('Answer') }}</label>
                        <select name="accepted" id="accepted" class="form-control" v-model="filters.accepted">
                            <option value="1">{{ $t('Accepted') }}</option>
                            <option value="0">{{ $t('Declined') }}</option>
                            <option value="not answered">{{ $t('Not answered') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">{{ $t('Apply filters') }}</button>
                </div>
            </form>
        </div>

        <ul class="active-filters" v-if="Object.keys(activeFilters).length > 0">
            <li v-for="(val, key) in activeFilters" :key="key" v-if="key !== 'sort' && key !== 'dir'" @click="_removeFilter(key)" class="badge badge-pill badge-default" :data-field="key">
                <strong>{{ key }}</strong>:
                {{ key === 'voices' ? voices[val] : val }}
            </li>
        </ul>
        <span v-else class="text-muted">{{ $t('No active filters') }}</span>

        <a class="show-filters" data-toggle="collapse" href="#filtersInner" aria-expanded="false" aria-controls="filtersInner">
            {{ $t('Show/hide filters') }}
        </a>
    </div>
</template>

<script>
    export default {
        props: ['voices', 'concerts', 'fetchItems', 'filters', 'activeFilters', 'removeFilter'],
        methods: {
            filter: function () {
                this.fetchItems();
            },
            _removeFilter: function (key) {
                this.removeFilter(key);
            }
        }
    }
</script>
