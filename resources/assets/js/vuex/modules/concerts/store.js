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
        concert: {
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
        participant: state => id => {
            return state.concert.participants.filter(user => user.id === id)[0]
        },
        getField
    },
    actions: actions
};
