import axios from '../../../axios';
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
    edit({ commit }, concert) {
        return axios.put(`${paths.concerts}/${concert.id}`, concert)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch(`${namespace}/fetch`);
            });
    },
    add({ commit }, concert) {
        return axios.post(`${paths.concerts}`, concert)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch(`${namespace}/fetch`);
            });
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
    },
    accept({ commit }, { id, userId }) {
        let path = `${paths.concerts}/accept/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    decline({ commit }, { id, userId }) {
        let path = `${paths.concerts}/decline/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    }
}
