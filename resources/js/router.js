import Vue from 'vue'
import Router from 'vue-router'

// import Home from './components/App.vue'

import Dashboard from './components/admin/Dashboard.vue'
import Users from './components/admin/users/Users.vue'
import Customers from './components/admin/users/Customers.vue'
import Roles from './components/admin/users/Roles.vue'
import Tickets from './components/admin/Tickets.vue'
import EditTicket from './components/admin/EditTicket.vue'
import TicketStatus from './components/admin/TicketStatus.vue'

Vue.use(Router)

const routes = [
    {
        path: '/admin/dashboard',
        component: Dashboard,
        name: 'dashboard'
    },
    
    // users
    {
        path: '/admin/customers',
        component: Customers,
        name: 'customers'
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
        path: '/admin/tickets',
        component: Tickets,
        name: 'tickets'
    },
    {
        path: '/admin/editTicket/:id',
        component: EditTicket,
        name: 'editTicket'
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