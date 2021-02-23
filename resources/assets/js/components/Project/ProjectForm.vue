<template>
    <div>
        <b-breadcrumb :items="breadcrumbs"></b-breadcrumb>

        <resource-form
                namespace="projects"
                resource-key="project"
                :resource="project"
        >

            <template v-slot:editTitle>
                <span class="light">{{ $t('Edit') }}:</span> {{ title }}
            </template>

            <template v-slot:createTitle>
                {{ $t('Create Project') }}
            </template>

            <template v-slot:form>
                <div class="row">
                    <div class="col">
                        <form-group
                                :label="$t('Project title')"
                                v-model="title"
                                name="title"
                                type="text"
                                validate="required"
                        ></form-group>

                        <form-group
                                :label="$t('Description')"
                                v-model="description"
                                name="description"
                                type="ckeditor"
                        ></form-group>

                        <form-group
                                :label="$t('Semester')"
                                v-model="semester_id"
                                name="semester_id"
                                type="select"
                                :options="semesters"
                                validate="required"
                        ></form-group>

                        <form-group
                                :label="$t('This is a main project in the semester.')"
                                v-model="is_main"
                                name="is_main"
                                type="checkbox"
                                :info="$t('People that attend the semester will automatically participate in this project as well.')"
                        ></form-group>

                        <form-group
                                :label="$t('Deadline')"
                                v-model="deadline"
                                name="deadline"
                                type="datetime"
                                validate="required"
                        ></form-group>
                    </div><!-- .col -->
                </div><!-- .row -->
            </template>
            <template v-slot:submitButton>
                {{ $t('Save Project') }}
            </template>
        </resource-form>
    </div>
</template>

<script>
    import FormGroup from '../FormGroup';
    import { mapState } from 'vuex';
    import { mapFields } from 'vuex-map-fields';
    import ResourceForm from '../ResourceForm';

    export default {
        name: 'project-form',
        components: {ResourceForm, FormGroup},
        computed: {
            ...mapState({
                semesters: state => state.semesters.options,
                project: state => state.projects.project
            }),
            ...mapFields('projects', {
                title: 'project.title',
                description: 'project.description',
                is_main: 'project.is_main',
                semester_id: 'project.semester_id',
                deadline: 'project.deadline'
            }),
            breadcrumbs () {
                return [
                    {
                        text: this.$t('Dashboard'),
                        to: '/'
                    },
                    {
                        text: this.$t('Projects'),
                        to: '/projects'
                    },
                    {
                        text: this.$t('New Project'),
                        active: true
                    }
                ]
            }
        },
        mounted () {
            this.$store.dispatch('semesters/options');

            if (!this.$route.params.id) {
                this.$store.commit('projects/SHOW', {
                    title: '',
                    description: '',
                    is_main: false,
                    semester_id: null,
                    deadline: null
                });

                for (let property in this.$route.query) {
                    this.project[property] = this.$route.query[property];
                }
            }
        }
    }
</script>

<style scoped>

</style>
