import ProjectList from "./components/Project/ProjectList";
import ProjectDetails from "./components/Project/ProjectDetails";
import Project from "./components/Project/Project";
import ProjectParticipants from "./components/Project/ProjectParticipants";
import ProjectForm from "./components/Project/ProjectForm";
import SemesterList from "./components/Semester/SemesterList";
import Semester from "./components/Semester/Semester";
import ConcertList from "./components/Concert/ConcertList";
import Concert from "./components/Concert/Concert";
import UserList from "./components/UserList";
import SemesterForm from "./components/Semester/SemesterForm";
import Dashboard from "./components/Dashboard/Dashboard";
import UserProfileForm from "./components/User/UserProfileForm";

export const routes = [
    { path: '/', component: Dashboard, name: 'Dashboard' },
    { path: '/projects', component: ProjectList, name: 'ProjectList' },
    { path: '/admin/projects/edit/:id', component: ProjectForm, name: 'EditProject' },
    { path: '/admin/projects/create', component: ProjectForm, name: 'CreateProject' },
    {
        path: '/projects/:id',
        component: Project,
        props: true,
        children: [
            {
                path: '',
                component: ProjectDetails
            },
            {
                path: 'participants',
                component: ProjectParticipants
            }
        ]
    },
    { path: '/semesters', component: SemesterList, name: 'SemesterList' },
    { path: '/admin/semesters/create', component: SemesterForm, name: 'CreateSemester' },
    {
        path: '/semesters/:id',
        component: Semester,
        props: true
    },
    { path: '/concerts', component: ConcertList, name: 'ConcertList' },
    {
        path: '/concerts/:id',
        component: Concert,
        props: true
    },
    { path: '/admin/users', component: UserList, name: 'UserList' },
    { path: '/profile/edit', component: UserProfileForm, name: 'UserProfileForm' }
];
