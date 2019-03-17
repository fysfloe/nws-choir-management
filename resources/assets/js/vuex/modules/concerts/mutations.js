export default {
    FETCH(state, concerts) {
        state.items = concerts;
    },
    SHOW(state, concert) {
        state.concert = concert;
    },
    PARTICIPANTS(state, users) {
        state.concert.participants = users;
    },
    OPTIONS(state, options) {
        state.options = options;
    }
}
