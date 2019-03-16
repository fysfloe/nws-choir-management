export default {
    FETCH(state, projects) {
        state.items = projects;
    },
    SHOW(state, project) {
        state.project = project;
    },
    PARTICIPANTS(state, users) {
        state.project.participants = users;
    },
    OPTIONS(state, options) {
        state.options = options;
    }
}
