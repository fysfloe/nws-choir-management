import axios from '../../../axios';
import paths from '../../../api';

export default {
    fetch({ commit }, filters) {
        return axios.get(paths.users, {params: filters})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    add({ commit }, user) {
        return axios.post(`${paths.users}`, user)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch('users/fetch');
            });
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
    },
    select({ commit }, users) {
        commit('SELECT', users);
    },
    setVoice({ commit }, params) {
        axios.post(`${paths.voices}/set_multi`, params)
            .then(() => this.dispatch('users/fetch'));
    },
    delete({}, id) {
        axios.delete(`${paths.users}/${id}`)
            .then(() => this.dispatch('users/fetch'))
            .catch();
    },
    export ({}, filters) {
        axios({
            method: 'post',
            url: '/admin/users/export',
            responseType: 'arraybuffer',
            data: filters
        }).then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'chorganizer_users_' + new Date(Date.now()).toLocaleDateString() + '.xlsx');
            document.body.appendChild(link);
            link.click();
        }).catch(error => console.log(error));
    }
}
