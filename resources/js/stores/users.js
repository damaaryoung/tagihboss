import $axios from '../api.js'
const state = () => ({
    users: [],
    roles: [],
    history: [],
    user: {
        user_id:'',
        id_lokasi:'',
        name:'',
        email:'',
        password:'',
        nik:'',
        roles: '',
    },
    userMicro: {
        usernamemicro: '',
        passwordmicro: '',
    },
    historyid: "",
    page: 1,
    page2: 1,
    loading:false
})
const mutations = {
    SET_HISTORYID(state, payload) {
        state.historyid = payload
    },
    ASSIGN_DATA(state, payload) {
        state.users = payload
    },
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    ASSIGN_DATA_HISTORY(state, payload) {
        state.history = payload
    },
    ASSIGN_ROLES(state, payload) {
        state.roles = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_PAGE2(state, payload) {
        state.page2 = payload
    },
    ASSIGN_FORM(state, payload) {
        state.user = {
          user_id:payload.user_id,
          id_lokasi:payload.id_lokasi,
          name:payload.name,
          email:payload.email,
          password:payload.password,
          nik:payload.nik,
          roles: payload.roles,
        }
    },
    ASSIGN_FORM_MICRO(state, payload) {
        state.userMicro = {
          usernamemicro: '',
          passwordmicro: '',
        }
    },
    CLEAR_FORM(state) {
        state.user = {
          user_id:'',
          id_lokasi:'',
          name:'',
          email:'',
          password:'',
          nik:'',
          roles: '',
        }
    },
    CLEAR_FORM_MICRO(state) {
        state.userMicro = {
          usernamemicro: '',
          passwordmicro: '',
        }
    }
}

const actions = {
    getUsers({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/user?page=${state.page}&q=${search}`)
            .then((response) => {
                commit('SET_LOADING', false)
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    getRoles({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/roles`)
            .then((response) => {
                commit('ASSIGN_ROLES', response.data.data)
                resolve(response.data)
            })
        })
    },
    submitUsers({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/user`, state.user)
            .then((response) => {
                dispatch('getUsers').then(() => {
                    resolve(response.data)
                })
            })
            .catch((error) => {
              if (error.response.status == 422) {
                  commit('SET_ERRORS', error.response.data.errors, { root: true })
              }
            })
        })
    },
    submitUsersCheck({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/user-micro`, state.userMicro)
            .then((response) => {
              commit('CLEAR_FORM_MICRO')
              state.user = response.data
                resolve(response.data)
            })
            .catch((error) => {
              if (error.response.status == 422) {
                  commit('SET_ERRORS', error.response.data.errors, { root: true })
              }
            })
        })
    },
    editUsers({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/user/${payload}/edit`)
            .then((response) => {
                commit('ASSIGN_FORM', response.data)
                resolve(response.data)
            })
        })
    },
    showUsers({ commit, state }, payload) {
        return new Promise((resolve, reject) => {
            $axios.put(`/history?user=${state.historyid}&page=${state.page2}`)
            .then((response) => {
                commit('ASSIGN_DATA_HISTORY', response.data)
                resolve(response.data)
            })
        })
    },
    updateUsers({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.put(`/user/${payload}`, state.user)
            .then((response) => {
                commit('CLEAR_FORM')
                resolve(response.data)
            })
        })
    } ,
    removeUsers({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/user/${payload}`)
            .then((response) => {
                dispatch('getUsers').then(() => resolve())
            })
        })
    }
}
export default {
    namespaced: true,
    state,
    actions,
    mutations
}
