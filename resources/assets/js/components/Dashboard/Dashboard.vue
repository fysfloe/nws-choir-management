<template>
    <div>
        <loader v-if="loading"/>

        <div v-else>
            <header class="page-header">
                <h2>{{ $t('Dashboard') }}</h2>
            </header>
            <div class="row dashboard">
                <div class="col-8">
                    <h3>
                        <span class="oi oi-calendar"></span> {{ $t('Current Semester') }}
                        <small v-if="semester" class="text-muted">{{ semester.name }}</small>
                    </h3>
                    <div v-if="semester">
                        <div v-if="semester.accepted || semester.declined" :class="{'margin-bottom alert alert-sm': true, 'alert-success': semester.accepted, 'alert-danger': semester.declined}">
                            <span v-if="semester.accepted">{{ $t('You are attending!') }}</span>
                            <span v-else>{{ $t('You are not attending.') }}</span>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4 class="margin-top">
                                    <span class="oi oi-project"></span>&nbsp;
                                    {{ $t('Projects') }}
                                    <small>(<router-link to="/projects">{{ $t('Show all') }}</router-link>)</small>
                                </h4>
                                <ul v-if="semester.projects.length > 0" class="concerts">
                                    <li v-for="project in semester.projects">
                                        <div class="row">
                                            <div class="col">
                                                <span :class="{'accepted-sign oi oi-media-record text-muted': true, 'text-success': userAcceptedProject(project), 'text-danger': userDeniedProject(project)}"
                                                ></span>&nbsp;
                                                <router-link :to="`/projects/${project.id}`">{{ project.title }}</router-link>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <small v-else class="text-muted">{{ $t('No projects this semester.') }}</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4 class="margin-top">
                                    <span class="oi oi-musical-note"></span>&nbsp;
                                    {{ $t('Concerts') }}
                                    <small>(<router-link to="/concerts">{{ $t('Show all') }}</router-link>)</small>
                                </h4>
                                <ul v-if="semester.concerts.length > 0" class="concerts">
                                    <li v-for="concert in semester.concerts">
                                            <span :class="{'accepted-sign oi oi-media-record text-muted': true, 'text-success': userAcceptedConcert(concert), 'text-danger': userDeniedConcert(concert)}"
                                            ></span>&nbsp;
                                        <router-link :to="`/concerts/${concert.id}`">{{ concert.title }}</router-link><br>
                                        <small>
                                            <span class="oi oi-calendar text-muted"></span>&nbsp;{{ concert.date }}&nbsp;
                                            <span class="oi oi-clock text-muted"></span>&nbsp;{{ concert.start_time }}&nbsp;
                                        </small><br>
                                        <small v-if="concert.project"><strong class="text-muted">{{ concert.project.title }}</strong></small><br/>
                                    </li>
                                </ul>
                                <small v-else class="text-muted">{{ $t('No concerts this semester.') }}</small>
                            </div>
                            <div class="col">
                                <h4 class="margin-top">
                                    <span class="oi oi-infinity"></span>&nbsp;
                                    {{ $t('Rehearsals') }}
                                    <small>(<router-link to="/rehearsals">{{ $t('Show all') }}</router-link>)</small>
                                </h4>
                                <ul v-if="semester.rehearsals.length > 0" class="rehearsals">
                                    <li v-for="rehearsal in semester.rehearsals">
                                                <span :class="{'accepted-sign oi oi-media-record text-muted': true, 'text-success': userAcceptedRehearsal(rehearsal), 'text-danger': userDeniedRehearsal(rehearsal)}"
                                                ></span>&nbsp;
                                        <small>{{ rehearsal.title }}</small>
                                        <router-link :to="`/rehearsals/${rehearsal.id}`">
                                            <span class="oi oi-eye"></span>
                                        </router-link><br>
                                        <small v-if="rehearsal.project"><strong class="text-muted">{{ rehearsal.project }}</strong></small><br/>
                                    </li>
                                </ul>
                                <small v-else class="text-muted">{{ $t('No (more) rehearsals this semester.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 side-box">
                    <div>
                        <h3><span class="oi oi-task"></span> {{ $t('To do') }}</h3>
                        <ul v-if="hasTodos" class="todos">
                            <li v-if="semester && !userAnsweredSemester">
                                <p>
                                    {{ $t('You didn\'t tell us yet, if you are attending this semester.') }}
                                </p>
                                <accept-decline
                                        :accept-route="`/semesters/accept/${semester.id}`"
                                        :decline-route="`/semesters/decline/${semester.id}`"
                                >
                                </accept-decline>
                            </li>
                        </ul>
                        <small v-else class="text-muted">{{ $t('Nothing to do for now!') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import Loader from "../Loader";

    export default {
        name: 'dashboard',
        components: {Loader},
        mounted () {
            this.$store.dispatch('dashboard/fetch')
                .then(() => {
                    this.loading = false;
                });
        },
        data () {
            return {
                loading: true
            }
        },
        computed: {
            ...mapState({
                user: state => state.users.current,
                semester: state => state.dashboard.semester
            }),
            hasTodos () {
                return false;
            },
        },
        methods: {
            userAcceptedProject (project) {
                return false;
            },
            userDeniedProject (project) {
                return false;
            },
            userAcceptedConcert (concert) {
                return false;
            },
            userDeniedConcert (concert) {
                return false;
            },
            userAcceptedRehearsal (rehearsal) {
                return false;
            },
            userDeniedRehearsal (rehearsal) {
                return false;
            },
        }
    }
</script>

<style scoped>

</style>