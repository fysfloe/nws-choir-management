<template>
    <resource-form
            namespace="concerts"
            resource-key="concert"
            :resource="concert"
    >
        <template v-slot:editTitle>
            <span class="light">{{ $t('Edit') }}:</span> {{ title }}
        </template>

        <template v-slot:createTitle>
            {{ $t('Create Concert') }}
        </template>

        <template v-slot:form>
            <div class="row">
                <div class="col">
                    <form-group
                            :label="$t('Concert title')"
                            v-model="title"
                            name="title"
                            type="text"
                            validate="required"
                    ></form-group>

                    <form-group
                            :label="$t('Description')"
                            v-model="description"
                            name="description"
                            type="textarea"
                    ></form-group>

                    <form-group
                            :label="$t('Place')"
                            v-model="place"
                            name="place"
                            type="text"
                            validate="required"
                    ></form-group>

                    <form-group
                            :label="$t('Project')"
                            v-model="project_id"
                            name="project_id"
                            type="select"
                            :options="projects"
                            validate="required"
                    ></form-group>
                </div><!-- .col -->
                <div class="col side-box">
                    <h3>
                        <span class="oi oi-calendar"></span>
                        {{ $t('Date') }}
                    </h3>

                    <form-group
                            :label="$t('Date')"
                            v-model="date"
                            name="date"
                            type="date"
                            validate="required"
                    ></form-group>

                    <form-group
                            :label="$t('Start time')"
                            v-model="start_time"
                            name="start_time"
                            type="time"
                            validate="required"
                    ></form-group>

                    <form-group
                            :label="$t('End time')"
                            v-model="end_time"
                            name="end_time"
                            type="time"
                            validate="required"
                    ></form-group>

                    <form-group
                            :label="$t('Deadline')"
                            v-model="deadline"
                            name="deadline"
                            type="datetime"
                            validate="required"
                    ></form-group>
                </div>
            </div><!-- .row -->
        </template>
        <template v-slot:submitButton>
            {{ $t('Save Concert') }}
        </template>
    </resource-form>
</template>

<script>
    import FormGroup from '../FormGroup';
    import { mapState } from 'vuex';
    import { mapFields } from 'vuex-map-fields';
    import ResourceForm from '../ResourceForm';

    export default {
        name: 'concert-form',
        components: {ResourceForm, FormGroup},
        computed: {
            ...mapState({
                projects: state => state.projects.options,
                concert: state => state.concerts.concert
            }),
            ...mapFields('concerts', [
                'concert.title',
                'concert.description',
                'concert.project_id',
                'concert.place',
                'concert.deadline',
                'concert.date',
                'concert.start_time',
                'concert.end_time',
            ])
        },
        mounted () {
            this.$store.dispatch('projects/options');

            if (!this.$route.params.id) {
                for (let property in this.$route.query) {
                    this.concert[property] = this.$route.query[property];
                }
            }
        }
    }
</script>

<style scoped>

</style>