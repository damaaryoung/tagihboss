import $axios from '../api.js'
const state = () => ({
    loading:false,
    assigment: [],
    infocoll: [],
    info:"",
    activity: "",
    activity_: "",
    visit: "",
    visit_: "",
    payment: "",
    payment_: "",
    page: 1,
    categories:"",
    loadingInfo:false
})
const mutations = {
    SET_LOADINGINFO(state, payload) {
        state.loadingInfo = payload
    },
    ASSIGN_INFOCOLL(state, payload) {
        state.infocoll = payload
    },
    ASSIGN_INFO(state, payload) {
        state.info = payload
    },
    ASSIGN_COUNT_ASSIGMENT(state, payload) {
        state.assigment = payload
    },
    ASSIGN_COUNT_A(state, payload) {
        state.activity = payload
    },
    ASSIGN_COUNT_ASELF(state, payload) {
        state.activity_ = payload
    },
    ASSIGN_COUNT_B(state, payload) {
        state.visit = payload
    },
    ASSIGN_COUNT_BSELF(state, payload) {
        state.visit_ = payload
    },
    ASSIGN_COUNT_C(state, payload) {
        state.payment = payload
    },
    ASSIGN_COUNT_CSELF(state, payload) {
        state.payment_ = payload
    },
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    ASSIGN_DATA_ASSIGMENT(state, payload) {
        state.transactions = payload
    }
}

const actions = {
    infocollTitle({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/title-infocoll`)
            .then((response) => {
              commit('SET_LOADING', false)
              commit('ASSIGN_INFOCOLL', response.data)
              resolve(response.data)
            })
        })
    },
    getInfo({ commit, state }, payload) {
      commit('SET_LOADINGINFO', true)
      return new Promise((resolve, reject) => {
          $axios.get(`/infocoll/${payload}/edit`)
          .then((response) => {
              commit('SET_LOADINGINFO', false)
              commit('ASSIGN_INFO', response.data)
              // resolve(response.data)
          })
      })
    },
    countAssigment({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/assigment-count`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_COUNT_ASSIGMENT', response.data)
                resolve(response.data)
            })
        })
    },
    countActivity({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/activity-count`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_COUNT_A', response.data.all)
                commit('ASSIGN_COUNT_ASELF', response.data.self)
                resolve(response.data)
            })
        })
    },
    countVisit({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/visit-count`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_COUNT_B', response.data.all)
                commit('ASSIGN_COUNT_BSELF', response.data.self)
                resolve(response.data)
            })
        })
    },
    countPayment({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/payment-count`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_COUNT_C', response.data.all)
                commit('ASSIGN_COUNT_CSELF', response.data.self)
                resolve(response.data)
            })
        })
    },
}
export default {
    namespaced: true,
    state,
    actions,
    mutations
}
