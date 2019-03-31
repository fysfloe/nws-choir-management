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
                            <div class="col dashboard-box">
                                <h4>
                                    {{ $t('Projects') }}
                                    <small>(<router-link to="/projects">{{ $t('Show all') }}</router-link>)</small>
                                </h4>
                                <ul v-if="semester.projects.length > 0" class="concerts">
                                    <router-link v-for="project in semester.projects" :to="`/projects/${project.id}`" :key="project.id">
                                        <li>
                                            <div class="row">
                                                <div class="col">
                                                    <span :class="{'accepted-sign oi oi-media-record': true, 'text-success': project.accepted, 'text-danger': project.declined, 'text-muted': !project.accepted && !project.declined}"></span>&nbsp;
                                                    {{ project.title }}
                                                </div>
                                            </div>
                                        </li>
                                    </router-link>
                                </ul>
                                <small v-else class="text-muted">{{ $t('No projects this semester.') }}</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col dashboard-box">
                                <h4>
                                    {{ $t('Concerts') }}
                                    <small>(<router-link to="/concerts">{{ $t('Show all') }}</router-link>)</small>
                                </h4>
                                <ul v-if="semester.concerts.length > 0" class="concerts">
                                    <router-link v-for="concert in semester.concerts" :to="`/concerts/${concert.id}`" :key="concert.id">
                                        <li>
                                            <div>
                                                <span :class="{'accepted-sign oi oi-media-record': true, 'text-success': concert.accepted, 'text-danger': concert.declined, 'text-muted': !concert.accepted && !concert.declined}"
                                                ></span>&nbsp;
                                                {{ concert.title }}
                                                <small v-if="concert.project"><strong class="text-muted">{{ concert.project }}</strong></small>
                                                <br>
                                                <small>
                                                    <span class="oi oi-calendar text-muted"></span>&nbsp;{{ concert.date|moment('DD.MM.YYYY') }}&nbsp;
                                                    <span class="oi oi-clock text-muted"></span>&nbsp;{{ concert.start_time }}&nbsp;
                                                </small>
                                            </div>
                                        </li>
                                    </router-link>
                                </ul>
                                <small v-else class="text-muted">{{ $t('No concerts this semester.') }}</small>
                            </div>
                            <div class="col dashboard-box">
                                <h4>{{ $t('Rehearsals') }}</h4>
                                <ul v-if="semester.rehearsals.length > 0" class="rehearsals">
                                    <router-link v-for="rehearsal in semester.rehearsals" :to="`/rehearsals/${rehearsal.id}`" :key="rehearsal.id">
                                        <li>
                                            <div>
                                                <span :class="{'accepted-sign oi oi-media-record': true, 'text-success': rehearsal.accepted, 'text-danger': rehearsal.declined, 'text-muted': !rehearsal.accepted && !rehearsal.declined}"></span>&nbsp;
                                                {{ rehearsal.title }}
                                                <small v-if="rehearsal.project"><strong class="text-muted">{{ rehearsal.project }}</strong></small>
                                                <br>
                                                <small>
                                                    <span class="oi oi-clock text-muted"></span>&nbsp;{{ rehearsal.start_time }} – {{ rehearsal.end_time }}
                                                </small>
                                            </div>
                                        </li>
                                    </router-link><br>
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
        }
    }
</script>

<style scoped lang="scss">
    @import "../../../sass/variables";

    .dashboard-box {
        background: $lightgrey;
        margin: 1em;
        padding: 1em;
        border-top: 0.1rem solid #149EB5;
    }
</style>