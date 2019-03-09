import mutations from './mutations';
import actions from './actions';

export default {
    namespaced: true,
    state: {
        items: [],
        concert: {},
        options: {}
    },
    mutations: mutations,
    actions: actions
};
