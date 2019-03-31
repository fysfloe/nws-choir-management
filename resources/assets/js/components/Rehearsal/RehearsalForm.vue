<template>
    <resource-form
            namespace="rehearsals"
            resource-key="rehearsal"
            :resource="rehearsal"
    >
        <template v-slot:editTitle>
            <span class="light">{{ $t('Edit') }}:</span> {{ title }}
        </template>

        <template v-slot:createTitle>
            {{ $t('Create Rehearsal') }}
        </template>

        <template v-slot:form>
            <div class="row">
                <div class="col">
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
                            :label="$t('Deadline')"
                            v-model="deadline"
                            name="deadline"
                            type="datetime"
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

                    <form-group
                            :label="$t('Semester')"
                            v-model="semester_id"
                            name="semester_id"
                            type="select"
                            :options="semesters"
                            validate="required"
                    ></form-group>
                </div><!-- .col -->
            </div><!-- .row -->
        </template>
        <template v-slot:submitButton>
            {{ $t('Save Rehearsal') }}
        </template>
    </resource-form>
</template>

<script>
    import FormGroup from '../FormGroup';
    import { mapState } from 'vuex';
    import { mapFields } from 'vuex-map-fields';
    import ResourceForm from '../ResourceForm';

    export default {
        name: 'rehearsal-form',
        components: {ResourceForm, FormGroup},
        computed: {
            ...mapState({
                projects: state => state.projects.options,
                semesters: state => state.semesters.options,
                rehearsal: state => state.rehearsals.rehearsal
            }),
            ...mapFields('rehearsals', {
                title: 'rehearsal.title',
                date: 'rehearsal.date',
                start_time: 'rehearsal.start_time',
                end_time: 'rehearsal.end_time',
                place: 'rehearsal.place',
                deadline: 'rehearsal.deadline',
                semester_id: 'rehearsal.semester_id',
                project_id: 'rehearsal.project_id',
                description: 'rehearsal.description'
            })
        },
        mounted () {
            this.$store.dispatch('projects/options');
            this.$store.dispatch('semesters/options');

            if (!this.$route.params.id) {
                this.$store.commit('rehearsals/SHOW', {
                    title: '',
                    description: '',
                    project_id: null,
                    semester_id: null,
                    place: '',
                    deadline: null,
                    date: null,
                    start_time: null,
                    end_time: null
                });

                for (let property in this.$route.query) {
                    this.rehearsal[property] = this.$route.query[property];
                }
            } else {
                this.$store.dispatch('rehearsals/show', this.$route.params.id);
            }
        }
    }
</script>

<style scoped>

</style>