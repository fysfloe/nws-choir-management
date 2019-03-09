import mutations from './mutations';
import actions from './actions';

export default {
    namespaced: true,
    state: {
        items: [],
        semester: {},
        options: {}
    },
    mutations: mutations,
    actions: actions
};
