import axios from '../../../axios';
import paths from '../../../api';

const namespace = 'rehearsals';

export default {
    fetch({ commit }, params) {
        return axios.get(paths.rehearsals, {params: params})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    show({ commit }, id) {
        return axios.get(`${paths.rehearsals}/${id}`)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    delete({}, id) {
        axios.delete(`${paths.rehearsals}/${id}`)
            .then(() => this.dispatch(`${namespace}/fetch`))
            .catch();
    },
    edit({ commit }, rehearsal) {
        return axios.put(`${paths.rehearsals}/${rehearsal.id}`, rehearsal)
            .then(response => {
                commit('SHOW', response.data);
            });
    },
    add({ commit }, rehearsal) {
        return axios.post(`${paths.rehearsals}`, rehearsal)
            .then(response => {
                commit('SHOW', response.data);
            });
    },
    participants({commit}, {id, filters}) {
        return axios.get(`${paths.rehearsals}/${id}/participants`, {params: filters})
            .then(response => commit('PARTICIPANTS', response.data))
            .catch();
    },
    otherUsers({commit}, id) {
        return axios.get(`${paths.rehearsals}/${id}/other_users`)
            .then(response => commit('OTHER_USERS', response.data))
            .catch();
    },
    addParticipants({commit}, {id, userIds}) {
        return axios.post(`${paths.rehearsals}/${id}/add_participants`, {users: userIds})
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                commit('RESET_FILTERS');
                this.dispatch(`${namespace}/otherUsers`, id);
            })
    },
    removeParticipants({}, {id, userIds}) {
        return axios.delete(`${paths.rehearsals}/${id}/remove_participants`, {data: {users: userIds}})
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                this.dispatch(`${namespace}/otherUsers`, id);
            })
    },
    accept({ commit }, { id, userId }) {
        let path = `${paths.rehearsals}/accept/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .catch();
    },
    decline({ commit }, { id, userId }) {
        let path = `${paths.rehearsals}/decline/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .catch();
    },
    confirm({commit}, {id, userId}) {
        return axios.post(`${paths.rehearsals}/${id}/confirm/${userId}`)
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                commit('RESET_FILTERS');
            })
            .catch();
    },
    excuse({commit}, {id, userId}) {
        return axios.post(`${paths.rehearsals}/${id}/excuse/${userId}`)
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                commit('RESET_FILTERS');
            })
            .catch();
    },
    setUnexcused({commit}, {id, userId}) {
        return axios.post(`${paths.rehearsals}/${id}/set_unexcused/${userId}`)
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                commit('RESET_FILTERS');
            })
            .catch();
    },
    exportParticipants({}, {rehearsal, filters}) {
        axios({
            method: 'post',
            url: `/admin/rehearsal/export-participants/${rehearsal.id}`,
            responseType: 'arraybuffer',
            data: filters
        }).then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `${rehearsal.date}_participants.xlsx`);
            document.body.appendChild(link);
            link.click();
        }).catch(error => console.log(error));
    }
}
