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
      kondisi_jaminan:"",
      latitude_jaminan:"",
      longitude_jaminan:"",
      imageJaminan1:"",
      imageJaminan2:"",
      imageJaminan3:""
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
          kondisi_jaminan:payload.kondisi_jaminan,
          latitude_jaminan:payload.latitude_jaminan,
          longitude_jaminan:payload.longitude_jaminan,
          imageJaminan1:payload.imageJaminan1
        }
    },
    CLEAR_FORM_A(state) {
        state.visit_a = {
          task_code:'',
          kondisi_jaminan:'',
          latitude_jaminan:'',
          longitude_jaminan:'',
          imageJaminan1:'',
        }
    },
}

const actions = {
    getActTT({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/visit-jaminan?page=${state.page}&q=${search}`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_DATA_TT', response.data)
                resolve(response.data)
            })
        })
    },
    editActTT({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/visit-jaminan/${payload}/edit`)
            .then((response) => {
                commit('ASSIGN_FORM_A', response.data)
                resolve(response.data)
            })
        })
    },
    updateVisit({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.put(`/visit-jaminan/${payload}`, state.visit_a)
            .then((response) => {
                commit('CLEAR_FORM_A')
                resolve(response.data)
            })
        })
    } ,
    removeVisit({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/visit-jaminan/${payload}`)
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
