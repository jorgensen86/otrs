import Vue from 'vue'
import Router from 'vue-router'

// import Home from './components/App.vue'

import Dashboard from './components/admin/Dashboard.vue'
import Users from './components/admin/users/Users.vue'
import Roles from './components/admin/users/Roles.vue'
import TicketStatus from './components/admin/TicketStatus.vue'

Vue.use(Router)

const routes = [
    {
        path: '/admin/dashboard',
        component: Dashboard,
        name: 'dashboard'
    },
    {
        path: '/admin/users',
        component: Users,
        name: 'user'
    },
    {
        path: '/admin/roles',
        component: Roles,
        name: 'roles'
    },
    {
        path: '/admin/ticket-status',
        component: TicketStatus,
        name: 'ticket-status'
    },
    
]

export default new Router({
    mode: 'history',
    routes
})