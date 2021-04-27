//IMPORT SECTION
import Vue from 'vue'
import Router from 'vue-router'
import Home from './pages/Home.vue'
import Notif from './pages/Notif.vue'
import Help from './pages/Help.vue'
import Login from './pages/Login.vue'
import store from './store.js'

// page started component

// permission page started
import Setting from './pages/setting/Index.vue'
import SetPermission from './pages/setting/roles/SetPermission.vue'
// permission page ended

// users manage page started
import IndexUsers from './pages/users/Index.vue'
import DataUsers from './pages/users/Users.vue'
import AddUsers from './pages/users/Add.vue'
import EditUsers from './pages/users/Edit.vue'
import ShowUsers from './pages/users/Show.vue'
// users manage page ended

// assigment manage page started
import IndexAssigment from './pages/assigment/Index.vue'
import DataAssigment from './pages/assigment/Assigment.vue'
import EditAssigment from './pages/assigment/Edit.vue'
// assigment manage page ended

// activity manage page started
import IndexActivity from './pages/activity/Index.vue'
import DataActivity from './pages/activity/Activity.vue'
import EditActivity from './pages/activity/Edit.vue'
import ShowActivity from './pages/activity/Show.vue'
// activity manage page ended

// visit tempat tinggal manage page started
import IndexVisitTT from './pages/visitTempatTinggal/Index.vue'
import DataVisitTT from './pages/visitTempatTinggal/Visit.vue'
import EditVisitTT from './pages/visitTempatTinggal/Edit.vue'
import ShowVisitTT from './pages/visitTempatTinggal/Show.vue'
// visit tempat tinggal manage page ended

// visit jaminan manage page started
import IndexVisitJM from './pages/visitJaminan/Index.vue'
import DataVisitJM from './pages/visitJaminan/Visit.vue'
import EditVisitJM from './pages/visitJaminan/Edit.vue'
import ShowVisitJM from './pages/visitJaminan/Show.vue'
// visit jaminan manage page ended

// payment manage page started
import IndexPayment from './pages/payment/Index.vue'
import DataPayment from './pages/payment/Payment.vue'
import ShowPayment from './pages/payment/Show.vue'
import EditPayment from './pages/payment/Edit.vue'
// payment manage page ended

// Collections manage page started
import IndexCollections from './pages/infocoll/Index.vue'
import DataCollections from './pages/infocoll/Collections.vue'
import ShowCollections from './pages/infocoll/Show.vue'
import EditCollections from './pages/infocoll/Edit.vue'
import AddCollections from './pages/infocoll/Add.vue'
// Collections manage page ended

// Documentation manage page started
import IndexDocumentation from './pages/documentation/Index.vue'
import DataDocumentation from './pages/documentation/Documentation.vue'
import ShowDocumentation from './pages/documentation/Show.vue'
import EditDocumentation from './pages/documentation/Edit.vue'
import AddDocumentation from './pages/documentation/Add.vue'
// Documentation manage page ended

// page ended component

Vue.use(Router)

//DEFINE ROUTE
const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            meta: { requiresAuth: true,title: 'Dashboard' }
        },
        {
            path: '/notification',
            name: 'notification',
            component: Notif,
            meta: { requiresAuth: true,title: 'Notification' }
        },
        {
            path: '/help',
            name: 'help',
            component: Help,
            meta: { requiresAuth: true,title: 'Bantuan & dukungan' }
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/setting',
            component: Setting,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '/role-permission',
                    name: 'role.permissions',
                    component: SetPermission,
                    meta: { title: 'Set Users & Permissions' }
                },
            ]
        },
        {
            path: '/users',
            component: IndexUsers,
            children: [
                {
                    path: '',
                    name: 'users.data',
                    component: DataUsers,
                    meta: { title: 'Manage Users' }
                },
                {
                    path: 'add',
                    name: 'users.add',
                    component: AddUsers,
                    meta: { title: 'Add New Users' }
                },
                {
                    path: 'show',
                    name: 'users.show',
                    component: ShowUsers,
                    meta: { title: 'Show Users' }
                },
                {
                    path: 'edit/:id',
                    name: 'users.edit',
                    component: EditUsers,
                    meta: { title: 'Edit Users' }
                }
            ]
        },
        {
            path: '/assigment',
            component: IndexAssigment,
            children: [
                {
                    path: '',
                    name: 'assigment.data',
                    component: DataAssigment,
                    meta: { title: 'Data Assigment' }
                },
                {
                    path: 'act/:id',
                    name: 'assigment.act',
                    component: EditAssigment,
                    meta: { title: 'Actions Assigment' }
                }
            ]
        },
        {
            path: '/activity',
            component: IndexActivity,
            children: [
                {
                    path: '',
                    name: 'activity.data',
                    component: DataActivity,
                    meta: { title: 'Manage Activity' }
                },
                {
                    path: 'show/:id',
                    name: 'activity.show',
                    component: ShowActivity,
                    meta: { title: 'Show Activity' }
                },
                {
                    path: 'edit/:id',
                    name: 'activity.edit',
                    component: EditActivity,
                    meta: { title: 'Edit Activity' }
                }
            ]
        },
        {
            path: '/visittt',
            component: IndexVisitTT,
            children: [
                {
                    path: '',
                    name: 'visittt.data',
                    component: DataVisitTT,
                    meta: { title: 'Manage Visit Tempat Tinggal' }
                },
                {
                    path: 'show/:id',
                    name: 'visittt.show',
                    component: ShowVisitTT,
                    meta: { title: 'Show Visit Tempat Tinggal' }
                },
                {
                    path: 'edit/:id',
                    name: 'visittt.edit',
                    component: EditVisitTT,
                    meta: { title: 'Edit Visit Tempat Tinggal' }
                }
            ]
        },
        {
            path: '/visitjm',
            component: IndexVisitJM,
            children: [
                {
                    path: '',
                    name: 'visitjm.data',
                    component: DataVisitJM,
                    meta: { title: 'Manage Visit Jaminan' }
                },
                {
                    path: 'show/:id',
                    name: 'visitjm.show',
                    component: ShowVisitJM,
                    meta: { title: 'Show Visit Jaminan' }
                },
                {
                    path: 'edit/:id',
                    name: 'visitjm.edit',
                    component: EditVisitJM,
                    meta: { title: 'Edit Visit Jaminan' }
                }
            ]
        },
        {
            path: '/payment',
            component: IndexPayment,
            children: [
                {
                    path: '',
                    name: 'payment.data',
                    component: DataPayment,
                    meta: { title: 'Manage Payment' }
                },
                {
                    path: 'show/:id',
                    name: 'payment.show',
                    component: ShowPayment,
                    meta: { title: 'Show Payment' }
                },
                {
                    path: 'edit/:id',
                    name: 'payment.edit',
                    component: EditPayment,
                    meta: { title: 'Edit Payment' }
                }
            ]
        },
        {
            path: '/collection',
            component: IndexCollections,
            children: [
                {
                    path: '',
                    name: 'collection.data',
                    component: DataCollections,
                    meta: { title: 'Manage Info Collections' }
                },
                {
                    path: 'add',
                    name: 'collection.add',
                    component: AddCollections,
                    meta: { title: 'Add New Information' }
                },
                {
                    path: 'show/:id',
                    name: 'collection.show',
                    component: ShowCollections,
                    meta: { title: 'Show Info Collections' }
                },
                {
                    path: 'edit/:id',
                    name: 'collection.edit',
                    component: EditCollections,
                    meta: { title: 'Edit Info Collections' }
                }
            ]
        },
        {
            path: '/documentation',
            component: IndexDocumentation,
            children: [
                {
                    path: '',
                    name: 'documentation.data',
                    component: DataDocumentation,
                    meta: { title: 'Manage Function Documentation' }
                },
                {
                    path: 'add',
                    name: 'documentation.add',
                    component: AddDocumentation,
                    meta: { title: 'Add New Function Documentation' }
                },
                {
                    path: 'show/:id',
                    name: 'documentation.show',
                    component: ShowDocumentation,
                    meta: { title: 'Show Function Documentation' }
                },
                {
                    path: 'edit/:id',
                    name: 'documentation.edit',
                    component: EditDocumentation,
                    meta: { title: 'Edit Function Documentation' }
                }
            ]
        }
    ]
});

//Navigation Guards
router.beforeEach((to, from, next) => {
    store.commit('CLEAR_ERRORS')
    if (to.matched.some(record => record.meta.requiresAuth)) {
        let auth = store.getters.isAuth
        if (!auth) {
            next({ name: 'login' })
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router
