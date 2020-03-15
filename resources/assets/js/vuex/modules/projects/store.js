import mutations from './mutations';
import actions from './actions';
import {getField, updateField} from "vuex-map-fields";

export default {
    namespaced: true,
    state: {
        items: [],
        participant_filters: {
            search: '',
            voices: [],
            concerts: [],
            ageFrom: '',
            ageTo: '',
            sort: 'surname',
            dir: 'ASC'
        },
        project: {
            participants: [],
            other_users: []
        },
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
