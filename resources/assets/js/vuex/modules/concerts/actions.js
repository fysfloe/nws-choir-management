import axios from 'axios';
import paths from '../../../api';

const namespace = 'concerts';

export default {
    fetch({ commit }, filters) {
        return axios.get(paths.concerts, {params: filters})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    show({ commit }, id) {
        return axios.get(`${paths.concerts}/${id}`)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    delete({}, id) {
        axios.delete(`${paths.concerts}/${id}`)
            .then(() => this.dispatch(`${namespace}/fetch`))
            .catch();
    },
    edit({}, concert) {
        axios.put(`${paths.concerts}/${concert.id}`, concert)
            .then(() => this.dispatch(`${namespace}/fetch`));
    },
    add({}, concert) {
        axios.post(`${paths.concerts}`, concert)
            .then(() => this.dispatch(`${namespace}/fetch`));
    },
    participants({ commit }, id) {
        return axios.get(`${paths.concerts}/load_participants/${id}`)
            .then(response => commit('LOAD_PARTICIPANTS', response.data))
            .catch();
    },
    options({ commit }) {
        return axios.get(`${paths.concerts}/load_options`)
            .then(response => commit('LOAD_OPTIONS', response.data))
            .catch();
    }
}
