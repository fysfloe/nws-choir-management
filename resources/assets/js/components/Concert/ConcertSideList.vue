<template>
    <div>
        <h3>{{ $t('Concerts') }}</h3>
        <ul class="concerts" v-if="concerts.length > 0">
            <router-link :to="`/concerts/${concert.id}`" v-for="concert in concerts" :key="concert.id">
                <li>
                    <span>
                        {{ concert.title }}<br>
                        <span class="oi oi-calendar text-muted"></span> {{ $d(new Date(concert.date)) }} &nbsp;<span class="oi oi-clock text-muted"></span> {{ concert.start_time }} – {{ concert.end_time }}
                    </span>
                    <accept-decline
                            class="text-right"
                            namespace="concerts"
                            :id="concert.id"
                            :accepted="concert.accepted"
                            :declined="concert.declined"
                            :deadline="concert.deadline"
                            :show-dot="true"
                    >
                    </accept-decline>
                </li>
            </router-link>
        </ul>

        <small v-else class="text-muted">{{ $t('No concerts found that belong to the project.') }}</small>

        <router-link v-if="user.canManageConcerts" class="btn btn-primary btn-sm mt-2" :to="addConcertRoute">
            {{ $t('Add a concert') }}
        </router-link>
    </div>
</template>

<script>
    import AcceptDecline from "../AcceptDecline";
    import { mapState } from 'vuex';

    export default {
        components: {AcceptDecline},
        props: {
            concerts: {
                type: Array,
                required: true
            },
            addConcertRoute: {
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
