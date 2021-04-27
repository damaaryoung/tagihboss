import $axios from '../api.js'
const state = () => ({
    loading:false,
    visit_a_data: [],
    location: {
      lat:"",
      long:"",
    },
    visit_a: {
      task_code:"",
      kondisi_tempat_tinggal:"",
      latitude_tempat_tinggal:"",
      longitude_tempat_tinggal:"",
      imageTempatTinggal1:"",
      imageTempatTinggal2:"",
      imageTempatTinggal3:""
    },
    page: 1,
    loading:false
})
const mutations = {
    ASSIGN_DATA_TT(state, payload) {
        state.visit_a_data = payload
    },
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    ASSIGN_FORM_A(state, payload) {
        state.visit_a = {
          task_code:payload.task_code,
          kondisi_tempat_tinggal:payload.kondisi_tempat_tinggal,
          latitude_tempat_tinggal:payload.latitude_tempat_tinggal,
          longitude_tempat_tinggal:payload.longitude_tempat_tinggal,
          imageTempatTinggal1:payload.imageTempatTinggal1
        }
    },
    CLEAR_FORM_A(state) {
        state.visit_a = {
          task_code:'',
          kondisi_tempat_tinggal:'',
          latitude_tempat_tinggal:'',
          longitude_tempat_tinggal:'',
          imageTempatTinggal1:'',
        }
    }
}

const actions = {
    getActTT({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/visit-tempat-tinggal?page=${state.page}&q=${search}`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_DATA_TT', response.data)
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
    editActTT({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/visit-tempat-tinggal/${payload}/edit`)
            .then((response) => {
                commit('ASSIGN_FORM_A', response.data)
                resolve(response.data)
            })
        })
    },
    updateVisit({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.put(`/visit-tempat-tinggal/${payload}`, state.visit_a)
            .then((response) => {
                commit('CLEAR_FORM_A')
                resolve(response.data)
            })
        })
    } ,
    removeVisit({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/visit-tempat-tinggal/${payload}`)
            .then((response) => {
                dispatch('getActTT').then(() => resolve())
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
