export default {
    FETCH(state, rehearsals) {
        state.items = rehearsals;
    },
    SHOW(state, rehearsal) {
        state.rehearsal = rehearsal;
    },
    LOAD_PARTICIPANTS(state, users) {
        state.rehearsal.participants = users;
    }
}
