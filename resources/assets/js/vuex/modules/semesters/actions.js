import axios from 'axios';
import paths from '../../../api';

const namespace = 'semesters';

export default {
    fetch({ commit }, filters) {
        return axios.get(paths.semesters, {params: filters})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    show({ commit }, id) {
        return axios.get(`${paths.semesters}/${id}`)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    delete({}, id) {
        axios.delete(`${paths.semesters}/${id}`)
            .then(() => this.dispatch(`${namespace}/fetch`))
            .catch();
    },
    edit({}, semester) {
        axios.put(`${paths.semesters}/${semester.id}`, semester)
            .then(() => this.dispatch(`${namespace}/fetch`));
    },
    add({}, semester) {
        axios.post(`${paths.semesters}`, semester)
            .then(() => this.dispatch(`${namespace}/fetch`));
    },
    participants({ commit }, id) {
        return axios.get(`${paths.semesters}/load_participants/${id}`)
            .then(response => commit('LOAD_PARTICIPANTS', response.data))
            .catch();
    },
    options({ commit }) {
        return axios.get(`${paths.semesters}/load_options`)
            .then(response => commit('LOAD_OPTIONS', response.data))
            .catch();
    }
}
