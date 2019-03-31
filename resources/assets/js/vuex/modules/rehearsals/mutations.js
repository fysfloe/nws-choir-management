export default {
    FETCH(state, rehearsals) {
        state.items = rehearsals;
    },
    SHOW(state, rehearsal) {
        state.rehearsal = rehearsal;
    },
    PARTICIPANTS(state, users) {
        Vue.set(state.rehearsal, 'participants', users);
    },
    OTHER_USERS(state, users) {
        Vue.set(state.rehearsal, 'other_users', users);
    }
}
