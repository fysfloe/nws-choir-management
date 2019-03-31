import mutations from './mutations';
import actions from './actions';
import {getField, updateField} from "vuex-map-fields";

export default {
    namespaced: true,
    state: {
        items: [],
        semester: {},
        options: {}
    },
    mutations: {
        ...mutations,
        updateField
    },
    actions: actions,
    getters: {
        getField
    }
};
