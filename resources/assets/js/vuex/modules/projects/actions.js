import axios from '../../../axios';
import paths from '../../../api';

const namespace = 'projects';

export default {
    fetch({ commit }, filters) {
        return axios.get(paths.projects, {params: filters})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    show({ commit }, id) {
        return axios.get(`${paths.projects}/${id}`)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    delete({}, id) {
        axios.delete(`${paths.projects}/${id}`)
            .then(() => this.dispatch(`${namespace}/fetch`))
            .catch();
    },
    edit({}, project) {
        axios.put(`${paths.projects}/${project.id}`, project)
            .then(() => this.dispatch(`${namespace}/fetch`));
    },
    add({ commit }, project) {
        return axios.post(`${paths.projects}`, project)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch(`${namespace}/show`, response.data.id)
            });
    },
    participants({ commit }, id) {
        return axios.get(`${paths.projects}/participants/${id}`)
            .then(response => commit('PARTICIPANTS', response.data))
            .catch();
    },
    options({ commit }) {
        return axios.get(`${paths.projects}/options`)
            .then(response => commit('OPTIONS', response.data))
            .catch();
    },
    accept({ commit }, { id, userId }) {
        let path = `${paths.projects}/accept/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    decline({ commit }, { id, userId }) {
        let path = `${paths.projects}/decline/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    }
}
