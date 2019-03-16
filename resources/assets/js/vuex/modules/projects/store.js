import mutations from './mutations';
import actions from './actions';
import {getField, updateField} from "vuex-map-fields";

export default {
    namespaced: true,
    state: {
        items: [],
        project: {},
        options: {}
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
