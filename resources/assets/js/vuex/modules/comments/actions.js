import axios from '../../../axios';
import paths from '../../../api';

const namespace = 'comments';

export default {
    fetch({ commit }, params) {
        return axios.get(paths.comments, {params: params})
            .then(response => commit('FETCH', response.data))
            .catch();
    },
    add({}, comment) {
        axios.post(`${paths.comments}`, comment)
            .then(() => this.dispatch(`${namespace}/fetch`, {
                type: comment.type,
                commentable_id: comment.commentable_id
            }));
    },
    delete({}, comment) {
        axios.delete(`${paths.comments}/${comment.id}`)
            .then(() => {
                this.dispatch(`${namespace}/fetch`, {type: comment.type, commentable_id: comment.commentable_id})
            })
            .catch();
    }
}
