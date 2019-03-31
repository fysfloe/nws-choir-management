import mutations from './mutations';
import actions from './actions';
import {getField, updateField} from "vuex-map-fields";

export default {
    namespaced: true,
    state: {
        items: [],
        rehearsal: {
            participants: [],
            other_users: []
        }
    },
    mutations: {
        ...mutations,
        updateField
    },
    getters: {
        getField,
        participant: state => id => {
            return state.rehearsal.participants.filter(user => user.id === id)[0]
        }
    },
    actions: actions
};
