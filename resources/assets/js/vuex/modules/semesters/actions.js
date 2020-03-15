import axios from '../../../axios';
import paths from '../../../api';

const namespace = 'semesters';
const path = paths.semesters;

export default {
    fetch({ commit }, filters) {
        return axios.get(path, {params: filters})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    show({ commit }, id) {
        return axios.get(`${path}/${id}`)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    delete({}, id) {
        axios.delete(`${path}/${id}`)
            .then(() => this.dispatch(`${namespace}/fetch`))
            .catch();
    },
    edit({ commit }, semester) {
        return axios.put(`${path}/${semester.id}`, semester)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch(`${namespace}/fetch`)
            });
    },
    add({ commit }, semester) {
        return axios.post(`${path}`, semester)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch(`${namespace}/fetch`);
            });
    },
    participants({ commit }, { id, filters }) {
        return axios.get(`${path}/${id}/participants`, {params: filters})
            .then(response => commit('PARTICIPANTS', response.data))
            .catch();
    },
    otherUsers({commit}, id) {
        return axios.get(`${path}/${id}/other_users`)
            .then(response => commit('OTHER_USERS', response.data))
            .catch();
    },
    addParticipants({}, {id, userIds}) {
        return axios.post(`${path}/${id}/add_participants`, {users: userIds})
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                this.dispatch(`${namespace}/otherUsers`, id);
            })
    },
    removeParticipants({}, {id, userIds}) {
        return axios.delete(`${path}/${id}/remove_participants`, {data: {users: userIds}})
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                this.dispatch(`${namespace}/otherUsers`, id);
            })
    },
    options({ commit }) {
        return axios.get(`${path}/options`)
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
    },
    exportParticipants({}, {semester, filters}) {
        axios({
            method: 'post',
            url: `/admin/semester/export-participants/${semester.id}`,
            responseType: 'arraybuffer',
            data: filters
        }).then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `${semester.name}_participants.xlsx`);
            document.body.appendChild(link);
            link.click();
        }).catch(error => console.log(error));
    }
}
