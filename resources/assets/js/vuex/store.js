import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

import projects from './modules/projects/store';
import users from './modules/users/store';
import rehearsals from './modules/rehearsals/store';
import comments from './modules/comments/store';
import semesters from './modules/semesters/store';
import concerts from './modules/concerts/store';
import dashboard from './modules/dashboard/store';

export default new Vuex.Store({
    modules: {
        projects: projects,
        users: users,
        rehearsals: rehearsals,
        comments: comments,
        semesters: semesters,
        concerts: concerts,
        dashboard: dashboard
    }
})
