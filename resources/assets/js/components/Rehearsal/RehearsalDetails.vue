<template>
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="details">
                    <h3>{{ $t('Details') }}</h3>
                    <ul class="margin-top no-list-style">
                        <li>
                            <span class="oi oi-calendar" data-toggle="tooltip" :title="$t('Date')"></span>&nbsp;
                            {{ rehearsal.title }}
                        </li>
                        <li>
                            <span class="oi oi-clock" data-toggle="tooltip" :title="$t('Time')"></span>&nbsp;
                            {{ rehearsal.start_time }}
                            –{{ rehearsal.end_time }}
                        </li>
                        <li>
                            <span class="oi oi-map-marker" data-toggle="tooltip" :title="$t('Place')"></span>&nbsp;
                            {{ rehearsal.place }}
                        </li>
                    </ul>
                </div>

                <div class="description">
                    <h3>{{ $t('Description') }}</h3>
                    <span v-if="rehearsal.description">{{ rehearsal.description }}</span>
                    <small v-else class="text-muted">{{ $t('No description added.') }}</small>
                </div>
            </div>

            <div class="col-4 side-box">
                <div v-if="rehearsal.project" class="mb-4">
                    <h3>{{ $t('Project') }}</h3>
                    <a :href="`/project/${rehearsal.project.id}`">{{ rehearsal.project.title }}</a>
                </div>

                <rehearsal-side-list
                        v-if="rehearsal.other_rehearsals"
                        class="mb-4"
                        :rehearsals="rehearsal.other_rehearsals"
                        :add-rehearsal-route="`/admin/rehearsal/create?project=${rehearsal.project.id}&semester=${rehearsal.semester_id}`"
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
            rehearsal: {
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