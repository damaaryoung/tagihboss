import $axios from '../api.js'
const state = () => ({
    notif: [],
    page: 1,
    loading:false
})
const mutations = {
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    ASSIGN_DATA(state, payload) {
        state.notif = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    }
}

const actions = {
    getData({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/notif?page=${state.page}&q=${search}`)
            .then((response) => {
                commit('SET_LOADING', false)
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    checkD({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/notif/${payload}/edit`)
            .then((response) => {
                resolve(response.data)
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
