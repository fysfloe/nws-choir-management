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
                        :add-concert-route="`/admin/concert/create?project=${project.id}&semester=${project.semester.id}`"
                        :can-manage-concerts="user.canManageConcerts"
                        :user="user"
                ></concert-side-list>

                <rehearsal-side-list
                        class="mb-4"
                        :rehearsals="project.rehearsals"
                        :add-rehearsal-route="`/admin/rehearsal/create?project=${project.id}&semester=${project.semester.id}`"
                        :can-manage-rehearsals="user.canManageRehearsals"
                        :user="user"
                ></rehearsal-side-list>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            project: {
                type: Object,
                required: true
            },
            user: {
                type: Object,
                required: true
            }
        }
    }
</script>