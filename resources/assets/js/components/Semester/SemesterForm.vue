<template>
    <div>
        <div v-if="loading" class="loader"></div>
        <div v-else>
            <header class="page-header">
                <div v-if="isEdit">
                    <h2><span class="light">{{ $t('Edit') }}:</span> {{ semester.title }}</h2>
                </div>
                <div v-else>
                    <h2>{{ $t('Create Semester') }}</h2>
                </div>
            </header>
            <form>
                <div class="row">
                    <div class="col">
                        <form-group
                                :label="$t('Name')"
                                v-model="semester.name"
                                name="title"
                                type="text"
                                validate="required"
                        ></form-group>

                        <form-group
                                :label="$t('Start date')"
                                v-model="semester.start_date"
                                name="start_date"
                                type="date"
                                validate="required"
                        ></form-group>

                        <form-group
                                :label="$t('End date')"
                                v-model="semester.end_date"
                                name="end_date"
                                type="date"
                                validate="required"
                        ></form-group>

                        <form-group
                                :label="$t('Deadline')"
                                v-model="semester.deadline"
                                name="deadline"
                                type="datetime"
                                validate="required"
                        ></form-group>
                    </div><!-- .col -->
                </div><!-- .row -->

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" @click.prevent="submit">
                        {{ $t('Save Semester') }}
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
        name: 'semester-form',
        components: {FormGroup},
        data () {
            return {
                loading: true
            }
        },
        computed: {
            semester () {
                return this.$store.state.semesters.semester;
            },
            isEdit() {
                return !!this.$route.params.id;
            }
        },
        mounted () {
            if (this.isEdit) {
                this.$store.dispatch('semesters/show', this.$route.params.id).then(() => {
                    this.loading = false;
                });
            } else {
                this.$store.state.semesters.semester = {
                    name: '',
                    start_date: null,
                    end_date: null,
                    deadline: ''
                };
                this.loading = false;
            }
        },
        methods: {
            submit () {
                if (this.isEdit) {
                    this.$store.dispatch('semesters/edit', this.semester)
                        .then(() => {
                            this.$router.push(`/semesters/${this.$route.params.id}`);
                        });
                } else {
                    this.$store.dispatch('semesters/add', this.semester)
                        .then(() => {
                            this.$router.push(`/semesters/${this.$store.state.semesters.semester.id}`);
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>