import { createWebHistory, createRouter } from "vue-router";
import UserCreate from './components/Users/Create.vue';
import UserList from './components/Users/List.vue';
import GroupCreate from './components/Groups/Create.vue';
import GroupList from './components/Groups/List.vue';
import TransactionCreate from './components/Transactions/Create.vue';
import TransactionList from './components/Transactions/List.vue';
import AccountHeadCreate from './components/AccountHeads/Create.vue';
import AccountHeadList from './components/AccountHeads/List.vue';
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
        path: '/groups',
        name: 'GroupList',
        component: GroupList
    },
    {
        path: '/groups/create',
        name: 'GroupCreate',
        component: GroupCreate
    },
    {
        path: '/groups/edit/:id',
        name: 'GroupEdit',
        component: GroupCreate
    },
    {
        path: '/groups/view/:id',
        name: 'GroupView',
        component: GroupCreate
    },

    
    {
        path: '/account-heads',
        name: 'AccountHeadList',
        component: AccountHeadList
    },
    {
        path: '/account-heads/create',
        name: 'AccountHeadCreate',
        component: AccountHeadCreate
    },
    {
        path: '/account-heads/edit/:id',
        name: 'AccountHeadEdit',
        component: AccountHeadCreate
    },
    {
        path: '/account-heads/view/:id',
        name: 'AccountHeadView',
        component: AccountHeadCreate
    },

    
    {
        path: '/transactions',
        name: 'TransactionList',
        component: TransactionList
    },
    {
        path: '/transactions/create',
        name: 'TransactionCreate',
        component: TransactionCreate
    },
    {
        path: '/transactions/edit/:id',
        name: 'TransactionEdit',
        component: TransactionCreate
    },
    {
        path: '/transactions/view/:id',
        name: 'TransactionView',
        component: TransactionCreate
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
