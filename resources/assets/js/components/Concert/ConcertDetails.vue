<template>
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="description">
                    <h3>{{ $t('Description') }}</h3>
                    <span v-if="concert.description">{{ concert.description }}</span>
                    <small v-else class="text-muted">{{ $t('No description added.') }}</small>
                </div>
            </div>

            <div class="col-4 side-box">
                <div v-if="concert.project" class="mb-4">
                    <h3>{{ $t('Project') }}</h3>
                    <a :href="`/project/${concert.project.id}`">{{ concert.project.title }}</a>
                </div>

                <h3>{{ $t('Date') }}</h3>
                <div class="mb-4">
                    <span class="oi oi-calendar text-muted"></span> {{ concert.date }}&nbsp;
                    <span class="oi oi-clock text-muted"></span> {{ concert.start_time }} – {{ concert.end_time }}
                </div>

                <rehearsal-side-list
                        class="mb-4"
                        :rehearsals="concert.rehearsals"
                        :add-rehearsal-route="`/admin/rehearsal/create?project=${concert.project.id}&semester=${concert.semester_id}`"
                        :can-manage-rehearsals="user.canManageRehearsals"
                        :user="user"
                ></rehearsal-side-list>

                <div v-if="concert.place">
                    <h3>{{ $t('Place') }}</h3>
                    <div>
                        {{ concert.place }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            concert: {
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