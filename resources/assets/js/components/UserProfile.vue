<template>
    <div class="user-profile">
        <div class="overlay"></div>
        <div class="profile" v-click-outside="close">
            <header>
                <a href="#" class="close" @click.prevent="close">
                    <span class="oi oi-x"></span>
                </a>
                <avatar
                        :src="`/storage/avatars/${user.avatar}`"
                        :img="user.avatar"
                        icon="oi-person"
                        :alt="user.firstname + ' ' + user.surname"
                ></avatar>
            </header>

            <section>
                <div class="name">
                    <h2>
                        {{ user.firstname }} {{ user.surname }}
                    </h2>
                    <div>
                        <a :href="setVoiceRoute + '/' + user.id" data-toggle="modal" data-target="#mainModal">
                        <span class="badge badge-secondary badge-pill" v-if="user.voice">
                            {{ user.voice.name }}
                        </span>
                            <small v-else class="badge badge-light badge-pill text-muted">({{ $t('None set') }})</small>
                        </a>
                        <a :href="'/admin/role/set/' + user.id" data-toggle="modal" data-target="#mainModal">
                        <span v-if="user.roles && user.roles.length > 0" v-for="role in user.roles" :key="role.id"
                              class="badge badge-info">
                            {{ role.display_name }}
                        </span>
                            <small v-else class="badge badge-light text-muted">({{ $t('None set') }})</small>
                        </a>
                    </div>
                </div>

                <div>
                    <h3>{{ $t('Projects') }}</h3>
                    <ul class="projects" v-if="user.projects.length > 0">
                        <a :href="`/projects/${project.id}`" v-for="project in user.projects">
                            <li>
                                {{ project.title }}
                            </li>
                        </a>
                    </ul>
                    <small class="text-muted" v-else>{{ $t('This user didn\'t participate in any projects yet.') }}
                    </small>
                </div>

                <div class="row numbers">
                    <div class="col">
                        <div class="big-number">{{ user.concerts.length }}</div>
                        {{ $t('Concerts') }}
                    </div>
                    <div class="col">
                        <div class="big-number">{{ user.rehearsals.length }}</div>
                        {{ $t('Rehearsals') }}
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import ClickOutside from 'vue-click-outside'

    export default {
        props: {
            'user': {
                type: Object,
                required: true
            },
            'setVoiceRoute': {
                type: String
            },
            'close': {
                type: Function,
                required: true
            }
        },
        directives: {
            ClickOutside
        }
    }
</script>