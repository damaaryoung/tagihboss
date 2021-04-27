import $axios from '../api.js'
const state = () => ({
    loading:false,
    documentations: [],
    permissions: [],
    documentation: {
      title: "",
      slug: "",
      information: "",
      attention: "",
      authorization: "",
      state: "",
      created_by_name: ""
    },
    page: 1
})
const mutations = {
    ASSIGN_DATA(state, payload) {
        state.documentations = payload
    },
    ASSIGN_PERMISSION(state, payload) {
        state.permissions = payload
    },
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    ASSIGN_FORM(state, payload) {
        state.documentation = {
          title: payload.title,
          slug: payload.slug,
          information: payload.information,
          attention: payload.attention,
          authorization: payload.authorization,
          state: payload.state,
          created_by_name: payload.created_by_name
        }
    },
    CLEAR_FORM(state) {
      state.documentation= {
        title: "",
        slug: "",
        information: "",
        attention: "",
        authorization: "",
        state: "",
        created_by_name: ""
      }
    }
}

const actions = {
    getData({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        console.log(state.page);
        return new Promise((resolve, reject) => {
            $axios.get(`/documentation?page=${state.page}&q=${search}`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    getPermission({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/get-getPermission`)
            .then((response) => {
                commit('ASSIGN_PERMISSION', response.data)
                resolve(response.data)
            })
        })
    },
    Save({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/documentation`, state.documentation)
            .then((response) => {
                resolve(response.data)
                commit('CLEAR_FORM')
            })
            .catch((error) => {
              if (error.response.status == 422) {
                  commit('SET_ERRORS', error.response.data.errors, { root: true })
              }
            })
        })
    },
    Edit({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/documentation/${payload}/edit`)
            .then((response) => {
                commit('ASSIGN_FORM', response.data)
                resolve(response.data)
            })
        })
    },
    Update({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.patch(`/documentation/${payload}`, state.documentation)
            .then((response) => {
                commit('CLEAR_FORM')
                resolve(response.data)
            })
        })
    } ,
    Remove({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/documentation/${payload}`)
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
