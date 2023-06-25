import { createWebHistory, createRouter } from "vue-router";
import UserCreate from './components/Users/Create.vue';
import UserList from './components/Users/List.vue';
import NotFoundPage from './components/NotFoundPage.vue';
import Dashboard from './components/Dashboard.vue';

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/users',
        name: 'UserList',
        component: UserList
    },
    {
        path: '/users/create',
        name: 'UserCreate',
        component: UserCreate
    },
    {
        path: '/users/edit/:id',
        name: 'UserEdit',
        component: UserCreate
    },
    {
        path: '/users/view/:id',
        name: 'UserView',
        component: UserCreate
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFoundPage
    },

    //   { path: '*', redirect: '/404' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
