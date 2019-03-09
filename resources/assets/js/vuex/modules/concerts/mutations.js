export default {
    FETCH(state, concerts) {
        state.items = concerts;
    },
    SHOW(state, concert) {
        state.concert = concert;
    },
    LOAD_PARTICIPANTS(state, users) {
        state.concert.participants = users;
    },
    LOAD_OPTIONS(state, options) {
        state.options = options;
    }
}
