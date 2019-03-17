<template>
    <resource-form
            namespace="semesters"
            resource-key="semester"
            :resource="semester"
    >
        <template v-slot:editTitle>
            <span class="light">{{ $t('Edit') }}:</span> {{ title }}
        </template>

        <template v-slot:createTitle>
            {{ $t('Create Semester') }}
        </template>

        <template v-slot:form>
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
                title: 'semester.title',
                description: 'semester.description',
                start_date: 'semester.start_date',
                end_date: 'semester.end_date',
                deadline: 'semester.deadline'
            })
        }
    }
</script>

<style scoped>

</style>