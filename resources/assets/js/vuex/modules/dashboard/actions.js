import axios from 'axios';

export default {
    fetch({ commit }) {
        return axios.get('/api/dashboard')
            .then(response => commit('FETCH', response.data))
            .catch();
    }
}
