import $axios from '../api.js'
const state = () => ({
    loading:false,
    payments: [],
    payment: {
      ang_ke: "",
      angsuran: "",
      collect_fee: "",
      denda: "",
      file: "",
      file_ttd_collection: "",
      file_ttd_nasabah: "",
      nama_nasabah: "",
      no_bss: "",
      no_rekening: "",
      sisa_angsuran: "",
      sisa_denda: "",
      task_code: "",
      tenor: "",
      tgl_jt_tempo: "",
      titipan: "",
      total_bayar: ""
    },
    page: 1,
    loading:false
})
const mutations = {
    ASSIGN_DATA(state, payload) {
        state.payments = payload
    },
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    ASSIGN_FORM(state, payload) {
        state.payment = {
          ang_ke: payload.ang_ke,
          angsuran: payload.angsuran,
          collect_fee: payload.collect_fee,
          denda: payload.denda,
          file: payload.file,
          file_ttd_collection: payload.file_ttd_collection,
          file_ttd_nasabah: payload.file_ttd_nasabah,
          nama_nasabah: payload.nama_nasabah,
          no_bss: payload.no_bss,
          no_rekening: payload.no_rekening,
          sisa_angsuran: payload.sisa_angsuran,
          sisa_denda: payload.sisa_denda,
          task_code: payload.task_code,
          tenor: payload.tenor,
          tgl_jt_tempo: payload.tgl_jt_tempo,
          titipan: payload.titipan,
          total_bayar: payload.total_bayar,
        }
    },
    CLEAR_FORM(state) {
      state.payment= {
        ang_ke: "",
        angsuran: "",
        collect_fee: "",
        denda: "",
        file: "",
        file_ttd_collection: "",
        file_ttd_nasabah: "",
        nama_nasabah: "",
        no_bss: "",
        no_rekening: "",
        sisa_angsuran: "",
        sisa_denda: "",
        task_code: "",
        tenor: "",
        tgl_jt_tempo: "",
        titipan: "",
        total_bayar: ""
      }
    }
}

const actions = {
    getActTT({ commit, state }, payload) {
      commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        console.log(state.page);
        return new Promise((resolve, reject) => {
            $axios.get(`/payment?page=${state.page}&q=${search}`)
            .then((response) => {
              commit('SET_LOADING', false)
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    editActTT({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/payment/${payload}/edit`)
            .then((response) => {
                commit('ASSIGN_FORM', response.data)
                resolve(response.data)
            })
        })
    },
    print({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/print/${payload}`, { responseType: 'blob' })
            .then((response) => {
                const blob = new Blob([response.data], { type: 'application/pdf' })
                const link = document.createElement('a')
                link.href = URL.createObjectURL(blob)
                link.download = payload
                link.click()
                URL.revokeObjectURL(link.href)
            })
        })
    },
    updatePayment({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.put(`/payment/${payload}`, state.payment)
            .then((response) => {
                // commit('CLEAR_FORM')
                resolve(response.data)
            })
        })
    } ,
    removePayment({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/payment/${payload}`)
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
