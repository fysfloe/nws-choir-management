<template>
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="description">
                    <h3>{{ $t('Description') }}</h3>
                    <span v-if="project.description">{{ project.description }}</span>
                    <small v-else class="text-muted">{{ $t('No description added.') }}</small>
                </div>
            </div>

            <div class="col-4 side-box">
                <concert-side-list
                        class="mb-4"
                        :concerts="project.concerts"
                        :add-concert-route="`/admin/concert/create?project=${project.id}&semester=${project.semester_id}`"
                        :can-manage-concerts="currentUser.canManageConcerts"
                        :user="currentUser"
                ></concert-side-list>

                <rehearsal-side-list
                        class="mb-4"
                        :rehearsals="project.rehearsals"
                        :add-rehearsal-route="`/admin/rehearsal/create?project=${project.id}&semester=${project.semester_id}`"
                        :can-manage-rehearsals="currentUser.canManageRehearsals"
                        :user="currentUser"
                ></rehearsal-side-list>
            </div>
        </div>
    </div>
</template>

<script>
    import ConcertSideList from "../Concert/ConcertSideList";
    import RehearsalSideList from "../Rehearsal/RehearsalSideList";
    export default {
        components: {RehearsalSideList, ConcertSideList},
        computed: {
            project () {
                return this.$store.state.projects.project;
            },
            currentUser() {
                return this.$store.state.users.current;
            }
        }
    }
</script>