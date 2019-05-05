<template>
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="description">
                    <h3>{{ $t('Description') }}</h3>
                    <span v-if="concert.description" v-html="concert.description"></span>
                    <small v-else class="text-muted">{{ $t('No description added.') }}</small>
                </div>
            </div>

            <div class="col-4 side-box">
                <div v-if="concert.project" class="mb-4">
                    <h3>{{ $t('Project') }}</h3>
                    <router-link :to="`/projects/${concert.project.id}`">{{ concert.project.title }}</router-link>
                </div>

                <h3>{{ $t('Date') }}</h3>
                <div class="mb-4">
                    <span class="oi oi-calendar text-muted"></span> {{ concert.date|moment('DD.MM.YYYY') }}&nbsp;
                    <span class="oi oi-clock text-muted"></span> {{ concert.start_time }} – {{ concert.end_time }}
                </div>

                <rehearsal-side-list
                        class="mb-4"
                        :rehearsals="concert.rehearsals"
                        :add-rehearsal-route="`/admin/rehearsals/create` + (concert.project ? `?project_id=${concert.project.id}&` : '?') + `semester_id=${concert.semester_id}`"
                        :can-manage-rehearsals="currentUser.canManageRehearsals"
                        :user="currentUser"
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
    import RehearsalSideList from "../Rehearsal/RehearsalSideList";

    export default {
        components: {RehearsalSideList},
        computed: {
            concert () {
                return this.$store.state.concerts.concert;
            },
            currentUser() {
                return this.$store.state.users.current;
            }
        }
    }
</script>