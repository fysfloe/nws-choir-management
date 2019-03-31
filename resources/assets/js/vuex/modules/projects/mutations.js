export default {
    FETCH(state, projects) {
        state.items = projects;
    },
    SHOW(state, project) {
        state.project = project;
    },
    PARTICIPANTS(state, users) {
        Vue.set(state.project, 'participants', users);
    },
    OPTIONS(state, options) {
        Vue.set(state, 'options', options);
    },
    GRID(state, grid) {
        Vue.set(state.project, 'grid', grid);
    },
    OTHER_USERS(state, users) {
        Vue.set(state.project, 'other_users', users);
    }
}
