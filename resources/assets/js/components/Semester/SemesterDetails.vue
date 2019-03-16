<template>
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="description">
                    <h3>{{ $t('Description') }}</h3>
                    <span v-if="semester.description">{{ semester.description }}</span>
                    <small v-else class="text-muted">{{ $t('No description added.') }}</small>
                </div>
            </div>

            <div class="col-4 side-box">
                <project-side-list
                        class="mb-4"
                        :projects="semester.projects"
                        :add-project-route="`/admin/projects/create?semester_id=${semester.id}`"
                ></project-side-list>
            </div>
        </div>
    </div>
</template>

<script>
    import ProjectSideList from "../Project/ProjectSideList";

    export default {
        name: 'semester-details',
        components: {ProjectSideList},
        computed: {
            semester () {
                return this.$store.state.semesters.semester;
            },
            currentUser() {
                return this.$store.state.users.current;
            }
        }
    }
</script>