import axios from 'axios';
import paths from '../../../api';

const namespace = 'rehearsals';

export default {
    fetch({ commit }, params) {
        return axios.get(paths.rehearsals, {params: params})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    show({ commit }, id) {
        return axios.get(`${paths.rehearsals}/${id}`)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    delete({}, id) {
        axios.delete(`${paths.rehearsals}/${id}`)
            .then(() => this.dispatch(`${namespace}/fetch`))
            .catch();
    },
    edit({}, rehearsal) {
        axios.put(`${paths.rehearsals}/${rehearsal.id}`, rehearsal)
            .then(() => this.dispatch(`${namespace}/fetch`));
    },
    add({}, rehearsal) {
        axios.post(`${paths.rehearsals}`, rehearsal)
            .then(() => this.dispatch(`${namespace}/fetch`));
    },
    participants({ commit }, id) {
        return axios.get(`${paths.rehearsals}/load_participants/${id}`)
            .then(response => commit('LOAD_PARTICIPANTS', response.data))
            .catch();
    }
}
