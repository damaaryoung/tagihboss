import $axios from '../api.js'
const state = () => ({
    doc: [],
    pick: [],
    page: 1,
    loading:false
})
const mutations = {
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    ASSIGN_DATA(state, payload) {
        state.doc = payload
    },
    ASSIGN_PICK(state, payload) {
        state.pick = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    }
}

const actions = {
  getData({ commit }) {
      return new Promise((resolve, reject) => {
          $axios.get(`/help-support`)
          .then((response) => {
              commit('ASSIGN_DATA', response.data)
              resolve(response.data)
          })
      })
  },
  getPic({ commit }, payload) {
    commit('SET_LOADING', true)
      return new Promise((resolve, reject) => {
          $axios.get(`/help-support-pick/${payload}`)
          .then((response) => {
              commit('SET_LOADING', false)
              commit('ASSIGN_PICK', response.data)
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
