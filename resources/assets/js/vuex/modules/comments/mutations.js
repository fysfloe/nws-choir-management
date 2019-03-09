export default {
    FETCH(state, projects) {
        state.items = projects;
    },
    SHOW(state, project) {
        state.project = project;
    },
    LOAD_PARTICIPANTS(state, users) {
        state.project.participants = users;
    }
}
