<template>
    <div>
        <h3>{{ $t('Concerts') }}</h3>
        <ul class="concerts" v-if="concerts.length > 0">
            <a :href="`/concert/${concert.id}`" v-for="concert in concerts">
                <li>
                    <span>
                        <span class="oi oi-calendar text-muted"></span> {{ concert.title }} <br>
                        <span class="oi oi-clock text-muted"></span> {{ concert.start_time }} – {{ concert.end_time }}
                    </span>
                    <accept-decline
                            class="text-right"
                            :accept-route="`/concert/accept/${concert.id}`"
                            :decline-route="`/concert/decline/${concert.id}`"
                            :accepted="concert.has_accepted"
                            :declined="concert.has_declined"
                            :deadline="concert.deadline"
                            :show-dot="true"
                    >
                    </accept-decline>
                </li>
            </a>
        </ul>

        <small v-else class="text-muted">{{ $t('No concerts found that belong to the project.') }}</small>

        <a v-if="canManageConcerts" class="btn btn-primary btn-sm mt-2" :href="addConcertRoute">
            {{ $t('Add a concert') }}
        </a>
    </div>
</template>

<script>
    export default {
        props: {
            concerts: {
                type: Array,
                required: true
            },
            canManageConcerts: {
                type: [Boolean, Number],
                default: false
            },
            addConcertRoute: {
                type: String
            },
            user: {
                type: Object
            }
        }
    }
</script>