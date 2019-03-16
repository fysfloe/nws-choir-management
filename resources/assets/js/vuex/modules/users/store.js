import mutations from './mutations';
import actions from './actions';
import { getField, updateField } from 'vuex-map-fields';

export default {
    namespaced: true,
    state: {
        current: {},
        items: [],
        user: {}
    },
    mutations: {
        ...mutations,
        updateField
    },
    getters: {
        getField
    },
    actions: actions
};
