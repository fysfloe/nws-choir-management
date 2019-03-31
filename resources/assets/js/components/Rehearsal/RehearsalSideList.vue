<template>
    <div>
        <h3>{{ $t('Rehearsals') }}</h3>
        <ul class="rehearsals" v-if="rehearsals.length > 0">
            <router-link :to="`/rehearsals/${rehearsal.id}`" v-for="rehearsal in rehearsals" :key="rehearsal.id">
                <li>
                    <span>
                        <span class="oi oi-calendar text-muted"></span> {{ rehearsal.title }} <br>
                        <span class="oi oi-clock text-muted"></span> {{ rehearsal.start_time }} – {{ rehearsal.end_time }}
                    </span>
                    <accept-decline
                            class="text-right"
                            namespace="rehearsals"
                            :id="rehearsal.id"
                            :accepted="rehearsal.accepted"
                            :declined="rehearsal.declined"
                            :deadline="rehearsal.deadline"
                            :show-dot="true"
                    >
                    </accept-decline>
                </li>
            </router-link>
        </ul>

        <small v-else class="text-muted">{{ $t('No rehearsals found that belong to the project.') }}</small>

        <router-link v-if="user.canManageRehearsals" class="btn btn-primary btn-sm mt-2" :to="addRehearsalRoute">
            {{ $t('Add a rehearsal') }}
        </router-link>
    </div>
</template>

<script>
    import AcceptDecline from "../AcceptDecline";
    import { mapState } from 'vuex';

    export default {
        components: {AcceptDecline},
        props: {
            rehearsals: {
                type: Array,
                required: true
            },
            addRehearsalRoute: {
                type: String
            }
        },
        computed: {
            ...mapState({
                user: state => state.users.current
            })
        }
    }
</script>