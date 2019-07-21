import axios from '../../../axios';
import paths from '../../../api';

const namespace = 'projects';

export default {
    fetch({ commit }, filters) {
        return axios.get(paths.projects, {params: filters})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    show({ commit }, id) {
        return axios.get(`${paths.projects}/${id}`)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    delete({}, id) {
        axios.delete(`${paths.projects}/${id}`)
            .then(() => this.dispatch(`${namespace}/fetch`))
            .catch();
    },
    edit({}, project) {
        axios.put(`${paths.projects}/${project.id}`, project)
            .then(() => this.dispatch(`${namespace}/fetch`));
    },
    add({ commit }, project) {
        return axios.post(`${paths.projects}`, project)
            .then(response => {
                commit('SHOW', response.data);
                this.dispatch(`${namespace}/show`, response.data.id)
            });
    },
    participants({commit}, {id, filters}) {
        return axios.get(`${paths.projects}/${id}/participants`, {params: filters})
            .then(response => commit('PARTICIPANTS', response.data))
            .catch();
    },
    otherUsers({commit}, id) {
        return axios.get(`${paths.projects}/${id}/other_users`)
            .then(response => commit('OTHER_USERS', response.data))
            .catch();
    },
    addParticipants({}, {id, userIds}) {
        return axios.post(`${paths.projects}/${id}/add_participants`, {users: userIds})
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                this.dispatch(`${namespace}/otherUsers`, id);
            })
    },
    removeParticipants({}, {id, userIds}) {
        return axios.delete(`${paths.projects}/${id}/remove_participants`, {data: {users: userIds}})
            .then(() => {
                this.dispatch(`${namespace}/participants`, {id: id});
                this.dispatch(`${namespace}/otherUsers`, id);
            })
    },
    options({ commit }) {
        return axios.get(`${paths.projects}/options`)
            .then(response => commit('OPTIONS', response.data))
            .catch();
    },
    accept({ commit }, { id, userId }) {
        let path = `${paths.projects}/accept/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    decline({ commit }, { id, userId }) {
        let path = `${paths.projects}/decline/${id}`;

        if (userId) {
            path += `/${userId}`;
        }

        return axios.post(path)
            .then(response => commit('SHOW', response.data))
            .catch();
    },
    grid({commit}, id) {
        return axios.get(`${paths.projects}/${id}/grid`)
            .then(response => commit('GRID', response.data))
            .catch();
    },
    setVoice({commit}, {id, userIds, voiceId}) {
        return axios.post(`${paths.projects}/${id}/set_voice`, {users: userIds, voice: voiceId})
            .then(() => this.dispatch(`${namespace}/participants`, {id: id}));
    },
    exportParticipants({}, {project, filters}) {
        axios({
            method: 'post',
            url: `/admin/project/export-participants/${project.id}`,
            responseType: 'arraybuffer',
            data: filters
        }).then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `${project.title}_participants.xlsx`);
            document.body.appendChild(link);
            link.click();
        }).catch(error => console.log(error));
    }
}
