<template>
    <div class="filters">
        <div id="filtersInner" class="collapse show">
            <form class="filter-form" @submit.prevent="filter">
                <div class="form-group">
                    <label for="search" class="control-label">{{ texts.labels.search }}</label>
                    <input name="search" id="search" v-model="filters.search" class="form-control">
                </div>

                <div class="form-group">
                    <label for="voices" class="control-label">{{ texts.labels.voices }}</label>
                    <select @change="addValue(event, filters.voices)" name="voices[]" id="voices" class="form-control" multiple="multiple" v-model="filters.voices">
                        <option v-for="(voice, key) in voices" :value="key">{{ voice }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="concerts" class="control-label">{{ texts.labels.concerts }}</label>
                    <select name="concerts[]" id="concerts" class="form-control" multiple="multiple" v-model="filters.concerts">
                        <option v-for="(concert, key) in concerts" :value="key">{{ concert }}</option>
                    </select>
                </div>

                <div class="form-group age-filter">
                    <label for="age" class="control-label">{{ texts.labels.age }}</label>
                    <input type="number" name="age-from" min="10" max="110" class="form-control" v-model="filters.ageFrom" placeholder="Min">
                    <input type="number" name="age-to" min="10" max="110" class="form-control" v-model="filters.ageTo" placeholder="Max">
                </div>

                <div class="form-group">
                    &nbsp;<br>
                    <button type="submit" class="btn btn-primary btn-sm">{{ texts.applyFilters }}</button>
                </div>
            </form>
        </div>

        <ul class="active-filters" v-if="Object.keys(activeFilters).length > 0">
            <li v-for="(val, key) in activeFilters" @click="_removeFilter(key)" class="badge badge-pill badge-default" :data-field="key">
                <strong>{{ key }}</strong>:
                {{ val }}
            </li>
        </ul>
        <span v-else class="text-muted">{{ texts.noActiveFilters }}</span>

        <a class="show-filters" data-toggle="collapse" href="#filtersInner" aria-expanded="false" aria-controls="filtersInner">
            {{ texts.showHideFilters }}
        </a>
    </div>
</template>

<script>
export default {
    props: ['texts', 'voices', 'concerts', 'fetchUsers', 'filters', 'activeFilters', 'removeFilter'],
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
