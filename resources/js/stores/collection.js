import $axios from '../api.js'
const state = () => ({
    loading:false,
    collections: [],
    collection: {
      title: "",
      slug: "",
      information: "",
      state: ""
    },
    page: 1
})
const mutations = {
    ASSIGN_DATA(state, payload) {
        state.collections = payload
    },
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    ASSIGN_FORM(state, payload) {
        state.collection = {
          title: payload.title,
          slug: payload.slug,
          information: payload.information,
          state: payload.state
        }
    },
    CLEAR_FORM(state) {
      state.collection= {
        title: "",
        slug: "",
        information: "",
        state: ""
      }
    }
}

const actions = {
    getData({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        console.log(state.page);
        return new Promise((resolve, reject) => {
            $axios.get(`/infocoll?page=${state.page}&q=${search}`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    submitData({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/infocoll`, state.collection)
            .then((response) => {
                resolve(response.data)
            })
            .catch((error) => {
              if (error.response.status == 422) {
                  commit('SET_ERRORS', error.response.data.errors, { root: true })
              }
            })
        })
    },
    editCollection({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/infocoll/${payload}/edit`)
            .then((response) => {
                commit('ASSIGN_FORM', response.data)
                resolve(response.data)
            })
        })
    },
    updateCollection({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.patch(`/infocoll/${payload}`, state.collection)
            .then((response) => {
                commit('CLEAR_FORM')
                resolve(response.data)
            })
        })
    } ,
    removeCollection({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/infocoll/${payload}`)
            .then((response) => {
                dispatch('getData').then(() => resolve())
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
