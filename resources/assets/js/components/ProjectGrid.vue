<template>
    <div class="project-grid">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th scope="col">{{ $t('User') }}</th>
                    <th scope="col" v-for="date in dates" class="text-center">
                        <small :title="date.type === 'rehearsal' ? $t('Rehearsal') : $t('Concert')"
                               data-toggle="tooltip">
                            <span :class="{'oi': true, 'oi-audio': date.type === 'rehearsal', 'oi-musical-note': date.type === 'concert'}"></span>
                            {{ new Date(date.date).toLocaleDateString() }}
                        </small>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users">
                    <td scope="row">{{ user.firstname }} {{ user.surname }}</td>
                    <td v-for="date in dates"
                        :class="{'text-center': true, 'table-danger': hasDeclined(user, date), 'table-success': hasAccepted(user, date)}">
                        <span class="oi oi-question-mark muted"
                              v-if="!hasAccepted(user, date) && !hasDeclined(user, date)"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'rehearsals': {
                type: Array,
                required: true
            },
            'concerts': {
                type: Array,
                required: true
            },
            'users': {
                type: Array,
                required: true
            }
        },
        computed: {
            dates: {
                get() {
                    this.rehearsals.map((rehearsal) => {
                        rehearsal.date = rehearsal.date.date;
                        rehearsal.type = 'rehearsal';
                    });

                    this.concerts.map(concert => {
                        concert.type = 'concert';
                    });

                    return this.rehearsals.concat(this.concerts).sort((a, b) => {
                        return new Date(a.date) - new Date(b.date);
                    });
                }
            }
        },
        methods: {
            hasAccepted(user, date) {
                if (date.type === 'rehearsal') {
                    return user.rehearsals.filter(rehearsal => rehearsal.id === date.id && rehearsal.pivot.accepted && rehearsal.pivot.confirmed).length > 0;
                }

                if (date.type === 'concert') {
                    return user.concerts.filter(concert => concert.id === date.id && concert.pivot.accepted).length > 0;
                }
            },
            hasDeclined(user, date) {
                if (date.type === 'rehearsal') {
                    return user.rehearsals.filter(rehearsal => rehearsal.id === date.id && (!rehearsal.pivot.accepted || !rehearsal.pivot.confirmed)).length > 0;
                }

                if (date.type === 'concert') {
                    return user.concerts.filter(concert => concert.id === date.id && !concert.pivot.accepted).length > 0;
                }
            }
        }
    }
</script>