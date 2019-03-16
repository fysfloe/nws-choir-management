import axios from '../../../axios';
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
    edit({ commit }, rehearsal) {
        return axios.put(`${paths.rehearsals}/${rehearsal.id}`, rehearsal)
            .then(response => {
                commit('SHOW', response.data);
            });
    },
    add({ commit }, rehearsal) {
        return axios.post(`${paths.rehearsals}`, rehearsal)
            .then(response => {
                commit('SHOW', response.data);
            });
    },
    participants({ commit }, id) {
        return axios.get(`${paths.rehearsals}/load_participants/${id}`)
            .then(response => commit('LOAD_PARTICIPANTS', response.data))
            .catch();
    },
    accept({ commit }, { id, userId }) {
        let path = `${paths.rehearsals}/accept/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    decline({ commit }, { id, userId }) {
        let path = `${paths.rehearsals}/decline/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    }
}
