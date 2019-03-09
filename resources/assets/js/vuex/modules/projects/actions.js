import axios from 'axios';
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
    add({}, project) {
        return axios.post(`${paths.projects}`, project)
            .then(response => this.dispatch(`${namespace}/show`, response.data.id));
    },
    participants({ commit }, id) {
        return axios.get(`${paths.projects}/load_participants/${id}`)
            .then(response => commit('LOAD_PARTICIPANTS', response.data))
            .catch();
    }
}
