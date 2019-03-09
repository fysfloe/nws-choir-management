import axios from 'axios';
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
    }
}
