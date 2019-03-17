export default {
    FETCH(state, users) {
        state.items = users;
    },
    CURRENT_USER(state, user) {
        state.current = user;
    },
    SHOW(state, user) {
        state.user = user;
    },
    SELECT(state, users) {
        state.selected = users;
    }
}
