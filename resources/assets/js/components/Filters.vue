<template>
    <div class="filters">
        <div id="filtersInner" class="collapse show">
            <form class="filter-form" @submit.prevent="filter">
                <div class="form-group">
                    <label for="search" class="control-label">{{ $t('Search') }}</label>
                    <input name="search" id="search" v-model="filters.search" class="form-control">
                </div>

                <div class="form-group">
                    <label for="voices" class="control-label">{{ $t('Voices') }}</label>
                    <select @change="addValue(event, filters.voices)" name="voices[]" id="voices" class="form-control" multiple="multiple" v-model="filters.voices">
                        <option v-for="(voice, key) in voices" :key="key" :value="key">{{ voice }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="concerts" class="control-label">{{ $t('Concerts') }}</label>
                    <select name="concerts[]" id="concerts" class="form-control" multiple="multiple" v-model="filters.concerts">
                        <option v-for="(concert, key) in concerts" :key="key" :value="key">{{ concert }}</option>
                    </select>
                </div>

                <div class="form-group age-filter">
                    <label for="age" class="control-label">{{ $t('Age') }}</label>
                    <input type="number" name="age-from" min="10" max="110" class="form-control" v-model="filters.ageFrom" placeholder="Min">
                    <input type="number" name="age-to" min="10" max="110" class="form-control" v-model="filters.ageTo" placeholder="Max">
                </div>

                <div class="form-group">
                    &nbsp;<br>
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
    props: ['voices', 'concerts', 'fetchUsers', 'filters', 'activeFilters', 'removeFilter'],
    methods: {
        filter: function () {
            this.filters.voices = $('select[name="voices[]"]').val();
            this.filters.concerts = $('select[name="concerts[]"]').val();
            this.fetchUsers();
        },
        _removeFilter: function (key) {
            this.removeFilter(key);
        }
    },
}
</script>
