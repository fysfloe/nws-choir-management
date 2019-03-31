<template>
    <div class="project-grid">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th scope="col">{{ $t('User') }}</th>
                    <th scope="col" v-for="date in grid" class="text-center">
                        <small :title="date.type === 'rehearsal' ? $t('Rehearsal') : $t('Concert')"
                               data-toggle="tooltip">
                            <span :class="{'oi': true, 'oi-audio': date.type === 'rehearsal', 'oi-musical-note': date.type === 'concert'}"></span>
                            {{ date.date }}
                        </small>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in participants">
                    <td scope="row">{{ user.firstname }} {{ user.surname }}</td>
                    <td v-for="date in grid"
                        :class="{'text-center': true, 'table-danger': hasDeclined(user, date), 'table-success': hasAccepted(user, date), 'table-warning': isExcused(user, date)}">
                        <span class="oi oi-question-mark muted"
                              v-if="!hasAccepted(user, date) && !hasDeclined(user, date) && !isExcused(user, date)"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        computed: {
            ...mapState({
                grid: state => state.projects.project.grid,
                participants: state => state.projects.project.participants
            })
        },
        mounted() {
            this.$store.dispatch('projects/grid', this.$route.params.id);
        },
        methods: {
            hasAccepted(user, date) {
                return date.participants.filter(participant => participant.id === user.id && participant.accepted === true).length > 0;
            },
            hasDeclined(user, date) {
                return date.participants.filter(participant => participant.id === user.id && participant.accepted === false).length > 0;
            },
            isExcused(user, date) {
                return date.participants.filter(participant => participant.id === user.id && participant.excused === true).length > 0;
            }
        }
    }
</script>