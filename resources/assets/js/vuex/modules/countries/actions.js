import axios from '../../../axios';
import paths from '../../../api';

export default {
    options({ commit }) {
        return axios.get(`${paths.countries}/options`)
            .then(response => commit('OPTIONS', response.data))
            .catch();
    }
}
