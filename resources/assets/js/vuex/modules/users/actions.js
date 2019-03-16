import axios from '../../../axios';
import paths from '../../../api';

export default {
    fetch({ commit }) {
        return axios.get(paths.users)
            .then(response => commit('FETCH', response.data), error => {})
            .catch();
    },
    getCurrent({ commit }) {
        return axios.get(paths.currentUser)
            .then(response => commit('CURRENT_USER', response.data), error => {})
            .catch();
    },
    logout({ commit }) {
        axios.post('/logout').then(() => window.location.reload, (response) => {});
    },
    show({ commit }, id) {
        return axios.get(`${paths.users}/${id}`)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    edit({}, user) {
        axios.put(`${paths.users}/${user.id}`, user)
            .then(() => this.dispatch('users/fetch'));
    }
}
