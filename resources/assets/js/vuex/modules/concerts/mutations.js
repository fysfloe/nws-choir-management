export default {
    FETCH(state, concerts) {
        Vue.set(state, 'items', concerts);
    },
    SHOW(state, concert) {
        Vue.set(state, 'concert', concert);
    },
    PARTICIPANTS(state, users) {
        Vue.set(state.concert, 'participants', users);
    },
    OPTIONS(state, options) {
        Vue.set(state, 'options', options);
    },
    OTHER_USERS(state, users) {
        Vue.set(state.concert, 'other_users', users);
    }
}
