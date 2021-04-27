import $axios from '../api.js'
const state = () => ({
    activitys: [],
    next_action: [],
    case_category: [],
    asset_deb: [],
    kondisi_pekerjaan: [],
    activity: {
      task_code:"",
      kunjungan_ke:"",
      bertemu:"",
      karakter_debitur:"",
      total_penghasilan:"",
      kondisi_pekerjaan:"",
      asset_debt:"",
      janji_byr:"",
      tgl_janji_byr:"",
      case_category:"",
      next_action:"",
      keterangan:"",
      take_foto:null,
      invalid_no:"",
    },
    page: 1,
    img: false,
    loading:false
})
const mutations = {
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    ASSIGN_DATA(state, payload) {
        state.activitys = payload
    },
    SET_TAKEFOTO(state, payload) {
        state.activity.take_foto = payload
    },
    SET_IMG(state, payload) {
        state.img = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    ASSIGN_NEXT_ACTION(state, payload) {
        state.next_action = payload
    },
    ASSIGN_CASE_CATEGORY(state, payload) {
        state.case_category = payload
    },
    ASSIGN_ASSET_DEB(state, payload) {
        state.asset_deb = payload
    },
    ASSIGN_KOND_PEK(state, payload) {
        state.kondisi_pekerjaan = payload
    },
    ASSIGN_FORM(state, payload) {
        state.activity = {
          task_code:payload.task_code,
          kunjungan_ke:payload.kunjungan_ke,
          bertemu:payload.bertemu,
          karakter_debitur:payload.karakter_debitur,
          total_penghasilan:payload.total_penghasilan,
          kondisi_pekerjaan:payload.kondisi_pekerjaan,
          asset_debt:payload.asset_debt,
          janji_byr:payload.janji_byr,
          tgl_janji_byr:payload.tgl_janji_byr,
          case_category:payload.case_category,
          next_action:payload.next_action,
          keterangan:payload.keterangan,
          take_foto:payload.take_foto,
          invalid_no:payload.invalid_no,
        }
    },
    CLEAR_FORM(state) {
        state.activity = {
          task_code:'',
          kunjungan_ke:'',
          bertemu:'',
          karakter_debitur:'',
          total_penghasilan:'',
          kondisi_pekerjaan:'',
          asset_debt:'',
          janji_byr:'',
          tgl_janji_byr:'',
          case_category:'',
          next_action:'',
          keterangan:'',
          take_foto:'',
          invalid_no:'',
        }
    },
}

const actions = {
    getAct({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/activity?page=${state.page}&q=${search}`)
            .then((response) => {
                commit('SET_LOADING', false)
                commit('ASSIGN_DATA', response.data)
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
    editAct({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/activity/${payload}/edit`)
            .then((response) => {
                commit('ASSIGN_FORM', response.data)
                resolve(response.data)
            })
        })
    },
    updateActivity({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.put(`/activity/${payload}`, state.activity)
            .then((response) => {
                commit('CLEAR_FORM')
                resolve(response.data)
            })
        })
    } ,
    removeAct({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/activity/${payload}`)
            .then((response) => {
                dispatch('getAct').then(() => resolve())
            })
        })
    },
    getNextActions({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/get-next-action`)
            .then((response) => {
                commit('ASSIGN_NEXT_ACTION', response.data)
                resolve(response.data)
            })
        })
    },
    getCaseCategory({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/get-case-category`)
            .then((response) => {
                commit('ASSIGN_CASE_CATEGORY', response.data)
                resolve(response.data)
            })
        })
    },
    getAssetDeb({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/get-asset-debitur`)
            .then((response) => {
                commit('ASSIGN_ASSET_DEB', response.data)
                resolve(response.data)
            })
        })
    },
    getKondPek({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/get-kondisi-pekerjaan`)
            .then((response) => {
                commit('ASSIGN_KOND_PEK', response.data)
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
