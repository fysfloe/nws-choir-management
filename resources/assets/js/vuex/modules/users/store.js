import mutations from './mutations';
import actions from './actions';

export default {
    namespaced: true,
    state: {
        current: {},
        items: []
    },
    mutations: mutations,
    actions: actions
};
