export default {
    FETCH(state, semesters) {
        Vue.set(state, 'items', semesters);
    },
    SHOW(state, semester) {
        Vue.set(state, 'semester', semester);
    },
    PARTICIPANTS(state, users) {
        Vue.set(state.semester, 'participants', users);
    },
    OPTIONS(state, options) {
        Vue.set(state, 'options', options);
    },
    OTHER_USERS(state, users) {
        Vue.set(state.semester, 'other_users', users);
    }
}
