import axios from '../../../axios';
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
    edit({ commit }, semester) {
        return axios.put(`${paths.semesters}/${semester.id}`, semester)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch(`${namespace}/fetch`)
            });
    },
    add({ commit }, semester) {
        return axios.post(`${paths.semesters}`, semester)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch(`${namespace}/fetch`);
            });
    },
    participants({ commit }, id) {
        return axios.get(`${paths.semesters}/load_participants/${id}`)
            .then(response => commit('LOAD_PARTICIPANTS', response.data))
            .catch();
    },
    options({ commit }) {
        return axios.get(`${paths.semesters}/options`)
            .then(response => commit('OPTIONS', response.data))
            .catch();
    },
    accept({ commit }, { id, userId }) {
        let path = `${paths.semesters}/accept/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    decline({ commit }, { id, userId }) {
        let path = `${paths.semesters}/decline/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    }
}
