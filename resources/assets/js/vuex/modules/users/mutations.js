export default {
    FETCH(state, users) {
        state.items = users;
    },
    CURRENT_USER(state, user) {
        state.current = user;
    }
}
