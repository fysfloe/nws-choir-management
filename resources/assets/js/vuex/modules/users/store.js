import mutations from './mutations';
import actions from './actions';
import { getField, updateField } from 'vuex-map-fields';

export default {
    namespaced: true,
    state: {
        current: {},
        items: [],
        user: {
            address: {}
        },
        filters: {
            search: '',
            voices: [],
            concerts: [],
            ageFrom: '',
            ageTo: '',
            sort: 'surname',
            dir: 'ASC'
        },
        selected: []
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
