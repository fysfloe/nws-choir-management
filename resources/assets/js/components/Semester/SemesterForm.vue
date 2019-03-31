<template>
    <resource-form
            namespace="semesters"
            resource-key="semester"
            :resource="semester"
    >
        <template v-slot:editTitle>
            <span class="light">{{ $t('Edit') }}:</span> {{ name }}
        </template>

        <template v-slot:createTitle>
            {{ $t('Create Semester') }}
        </template>

        <template v-slot:form>
            <div class="row">
                <div class="col">
                    <form-group
                            :label="$t('Name')"
                            v-model="name"
                            name="title"
                            type="text"
                            validate="required"
                    ></form-group>

                    <form-group
                            :label="$t('Start date')"
                            v-model="start_date"
                            name="start_date"
                            type="date"
                            validate="required"
                    ></form-group>

                    <form-group
                            :label="$t('End date')"
                            v-model="end_date"
                            name="end_date"
                            type="date"
                            validate="required"
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
            {{ $t('Save Semester') }}
        </template>
    </resource-form>
</template>

<script>
    import FormGroup from "../FormGroup";
    import { mapState } from 'vuex';
    import { mapFields } from 'vuex-map-fields';
    import ResourceForm from '../ResourceForm';

    export default {
        name: 'semester-form',
        components: {FormGroup, ResourceForm},
        data () {
            return {
                loading: true
            }
        },
        computed: {
            ...mapState({
                semester: state => state.semesters.semester
            }),
            ...mapFields('semesters', {
                name: 'semester.name',
                start_date: 'semester.start_date',
                end_date: 'semester.end_date',
                deadline: 'semester.deadline'
            })
        },
        mounted () {
            if (!this.$route.params.id) {
                this.$store.commit('semesters/SHOW', {});
            }
        }
    }
</script>

<style scoped>

</style>