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
    participants({commit}, {id: id, filters: filters}) {
        return axios.get(`${paths.concerts}/${id}/participants`, {params: filters})
            .then(response => commit('PARTICIPANTS', response.data))
            .catch();
    },
    otherUsers({commit}, id) {
        return axios.get(`${paths.concerts}/${id}/other_users`)
            .then(response => commit('OTHER_USERS', response.data))
            .catch();
    },
    addParticipants({}, {id, userIds}) {
        return axios.post(`${paths.concerts}/${id}/add_participants`, {users: userIds})
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                this.dispatch(`${namespace}/otherUsers`, id);
            })
    },
    removeParticipants({}, {id, userIds}) {
        return axios.delete(`${paths.concerts}/${id}/remove_participants`, {data: {users: userIds}})
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                this.dispatch(`${namespace}/otherUsers`, id);
            })
    },
    options({ commit }) {
        return axios.get(`${paths.concerts}/options`)
            .then(response => commit('OPTIONS', response.data))
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
    },
    confirm({commit}, {id, userId}) {
        return axios.post(`${paths.concerts}/${id}/confirm/${userId}`)
            .then(() => this.dispatch(`${namespace}/participants`, {id: id}))
            .catch();
    },
    excuse({}, {id, userId}) {
        return axios.post(`${paths.concerts}/${id}/excuse/${userId}`)
            .then(() => this.dispatch(`${namespace}/participants`, {id: id}))
            .catch();
    },
    setUnexcused({}, {id, userId}) {
        return axios.post(`${paths.concerts}/${id}/set_unexcused/${userId}`)
            .then(() => this.dispatch(`${namespace}/participants`, {id: id}))
            .catch();
    }
}
