import Vue from 'vue'
import Vuex from 'vuex'

//IMPORT MODULE SECTION
import auth from './stores/auth.js'
import user from './stores/user.js'
import users from './stores/users.js'
import assigment from './stores/assigment.js'
import activity from './stores/activity.js'
import visittt from './stores/visittt.js'
import visitjm from './stores/visitjm.js'
import payment from './stores/payment.js'
import home from './stores/home.js'
import notification from './stores/notification.js'
import help from './stores/help.js'
import collection from './stores/collection.js'
import documentation from './stores/documentation.js'

Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        auth,
        user,
        users,
        assigment,
        activity,
        visittt,
        visitjm,
        payment,
        home,
        notification,
        help,
        collection,
        documentation
    },
    state: {
        token: localStorage.getItem('token'),
        errors: []
    },
    getters: {
        isAuth: state => {
            return state.token != "null" && state.token != null
        }
    },
    mutations: {
        SET_TOKEN(state, payload) {
            state.token = payload
        },
        SET_ERRORS(state, payload) {
            state.errors = payload
        },
        CLEAR_ERRORS(state) {
            state.errors = []
        }
    }
})

export default store
