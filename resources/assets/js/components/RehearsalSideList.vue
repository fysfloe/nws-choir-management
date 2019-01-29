<template>
    <div>
        <h3>{{ $t('Rehearsals') }}</h3>
        <ul class="rehearsals" v-if="rehearsals.length > 0">
            <a :href="`/rehearsal/${rehearsal.id}`" v-for="rehearsal in rehearsals">
                <li>
                    <span>
                        <span class="oi oi-calendar text-muted"></span> {{ rehearsal.title }} <br>
                        <span class="oi oi-clock text-muted"></span> {{ rehearsal.start_time }} – {{ rehearsal.end_time }}
                    </span>
                    <accept-decline
                            class="text-right"
                            :accept-route="`/rehearsal/accept/${rehearsal.id}`"
                            :decline-route="`/rehearsal/decline/${rehearsal.id}`"
                            :accepted="rehearsal.has_accepted"
                            :declined="rehearsal.has_declined"
                            :deadline="rehearsal.deadline"
                            :show-dot="true"
                    >
                    </accept-decline>
                </li>
            </a>
        </ul>

        <small v-else class="text-muted">{{ $t('No rehearsals found that belong to the projects concert.') }}</small>

        <a v-if="canManageRehearsals" class="btn btn-primary btn-sm mt-2" :href="addRehearsalRoute">
            {{ $t('Add a rehearsal') }}
        </a>
    </div>
</template>

<script>
    export default {
        props: {
            rehearsals: {
                type: Array,
                required: true
            },
            canManageRehearsals: {
                type: [Boolean, Number],
                default: false
            },
            addRehearsalRoute: {
                type: String
            },
            user: {
                type: Object
            }
        }
    }
</script>