export default {
    FETCH(state, semesters) {
        state.items = semesters;
    },
    SHOW(state, semester) {
        state.semester = semester;
    },
    LOAD_PARTICIPANTS(state, users) {
        state.semester.participants = users;
    },
    OPTIONS(state, options) {
        state.options = options;
    }
}
