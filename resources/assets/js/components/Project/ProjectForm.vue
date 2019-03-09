<template>
    <div>
        <div v-if="loading" class="loader"></div>
        <div v-else>
            <header class="page-header">
                <div v-if="isEdit">
                    <h2><span class="light">{{ $t('Edit') }}:</span> {{ project.title }}</h2>
                </div>
                <div v-else>
                    <h2>{{ $t('Create Project') }}</h2>
                </div>
            </header>
            <form>
                <div class="row">
                    <div class="col">
                        <form-group
                                :label="$t('Project title')"
                                v-model="project.title"
                                name="title"
                                type="text"
                        ></form-group>

                        <form-group
                                :label="$t('Description')"
                                v-model="project.description"
                                name="description"
                                type="textarea"
                        ></form-group>

                        <form-group
                                :label="$t('Semester')"
                                v-model="project.semester_id"
                                name="semester_id"
                                type="select"
                                :options="semesters"
                        ></form-group>

                        <form-group
                                :label="$t('This is a main project in the semester.')"
                                v-model="project.is_main"
                                name="is_main"
                                type="checkbox"
                                :info="$t('People that attend the semester will automatically participate in this project as well.')"
                        ></form-group>

                        <form-group
                                :label="$t('Deadline')"
                                v-model="project.deadline"
                                name="deadline"
                                type="datetime-local"
                        ></form-group>
                    </div><!-- .col -->
                </div><!-- .row -->

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" @click.prevent="submit">
                        {{ $t('Save Project') }}
                    </button>
                </div>
            </form>
        </div>
        <div v-else></div>
    </div>
</template>

<script>
    import FormGroup from "../FormGroup";
    export default {
        name: 'project-form',
        components: {FormGroup},
        data () {
            return {
                loading: true
            }
        },
        computed: {
            project () {
                return this.$store.state.projects.project;
            },
            isEdit() {
                return !!this.$route.params.id;
            },
            semesters () {
                return this.$store.state.semesters.options;
            }
        },
        mounted () {
            if (this.isEdit) {
                this.$store.dispatch('projects/show', this.$route.params.id).then(() => {
                    this.loading = false;
                });
            } else {
                this.$store.state.projects.project = {
                    title: '',
                    semester_id: null,
                    is_main: false,
                    deadline: ''
                };
                this.loading = false;
            }
        },
        methods: {
            submit () {
                if (this.isEdit) {
                    this.$store.dispatch('projects/edit', this.project)
                        .then(() => {
                            this.$router.push(`/projects/${this.$route.params.id}`);
                        });
                } else {
                    this.$store.dispatch('projects/add', this.project)
                        .then(() => {
                            this.$router.push(`/projects/${this.$store.state.projects.project.id}`);
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>