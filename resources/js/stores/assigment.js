import $axios from '../api.js'
const state = () => ({
    loading:false,
    assigments: [],
    next_action: [],
    case_category: [],
    asset_deb: [],
    kondisi_pekerjaan: [],
    location: {
      lat:"",
      long:"",
    },
    state_act:{
      stat_act:"",
      stat_vst:"",
      stat_pym:""
    },
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
      invalid_no:"",
      take_foto:null
    },
    visit_a: {
      task_code:"",
      kondisi_tempat_tinggal:"",
      latitude_tempat_tinggal:"",
      longitude_tempat_tinggal:"",
      imageTempatTinggal1:""
    },
    visit_b: {
      task_code:"",
      kondisi_jaminan:"",
      latitude_jaminan:"",
      longitude_jaminan:"",
      imageJaminan1:""
    },
    payment: {
      task_code:"",
      nama:"",
      no_rek:"",
      os_pokok:"",
      a_tertunggak:"",
      b_tertunggak:"",
      angsuran:"",
      bayar_denda:"",
      collect_fee:"",
      titipan:"",
      ang_ke:"",
      tenor:"",
      no_bss:"",
      total_bayar_angsuran:"",
      sisa_angsuran:"",
      sisa_denda:"",
      tgl_jt:"",
      take_foto:"",
      signature64Nasabah:"",
      signature64Collection:""
    },
    page: 1,
})
const mutations = {
    ASSIGN_DATA(state, payload) {
        state.assigments = payload
    },
    ASSIGN_ACT(state, payload) {
        state.state_act = payload
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
    SET_PAYMENT_FOTO(state, payload) {
        state.payment.take_foto = payload
    },
    SET_SIGNATURE_NAS(state, payload) {
        state.payment.signature64Nasabah = payload
    },
    SET_SIGNATURE_COLL(state, payload) {
        state.payment.signature64Collection = payload
    },
    SET_CALLCULATIONS_PAYMENT_A(state, payload) {
        let collectfee=parseInt(state.payment.collect_fee);
        let dendaangsuran=parseInt(state.payment.b_tertunggak);
        let denda=parseInt(state.payment.bayar_denda);
        let angsuran=parseInt(payload);
        let titipan=parseInt(state.payment.titipan);
        let q=parseInt(state.payment.a_tertunggak);

        const colfe=( isNaN(collectfee) ) ? 0 : collectfee;
        const dn=( isNaN(denda) ) ? 0 : denda;
        const sdn=( isNaN(dendaangsuran) ) ? 0 : dendaangsuran;
        const ttpn=( isNaN(titipan) ) ? 0 : titipan;
        const totalbayar=angsuran + dn + colfe + ttpn;
        const sisadenda=sdn - dn;
        const sisaangsuran=q - angsuran - ttpn;
        state.payment.total_bayar_angsuran = totalbayar
        state.payment.sisa_angsuran = sisaangsuran
        state.payment.sisa_denda = sisadenda
    },
    SET_CALLCULATIONS_PAYMENT_B(state, payload) {
      let collectfee=parseInt(state.payment.collect_fee);
      let dendaangsuran=parseInt(state.payment.b_tertunggak);
      let denda=parseInt(payload);
      let angsuran=parseInt(state.payment.angsuran);
      let titipan=parseInt(state.payment.titipan);
      let q=parseInt(state.payment.a_tertunggak);

      const colfe=( isNaN(collectfee) ) ? 0 : collectfee;
      const dn=( isNaN(denda) ) ? 0 : denda;
      const sdn=( isNaN(dendaangsuran) ) ? 0 : dendaangsuran;
      const ttpn=( isNaN(titipan) ) ? 0 : titipan;
      const totalbayar=angsuran + dn + colfe + ttpn;
      const sisadenda=sdn - dn;
      const sisaangsuran=q - angsuran - ttpn;
      state.payment.total_bayar_angsuran = totalbayar
      state.payment.sisa_angsuran = sisaangsuran
      state.payment.sisa_denda = sisadenda
    },
    SET_CALLCULATIONS_PAYMENT_C(state, payload) {
      let collectfee=parseInt(payload);
      let dendaangsuran=parseInt(state.payment.b_tertunggak);
      let denda=parseInt(state.payment.denda);
      let angsuran=parseInt(state.payment.angsuran);
      let titipan=parseInt(state.payment.titipan);
      let q=parseInt(state.payment.a_tertunggak);

      const colfe=( isNaN(collectfee) ) ? 0 : collectfee;
      const dn=( isNaN(denda) ) ? 0 : denda;
      const sdn=( isNaN(dendaangsuran) ) ? 0 : dendaangsuran;
      const ttpn=( isNaN(titipan) ) ? 0 : titipan;
      const totalbayar=angsuran + dn + colfe + ttpn;
      const sisadenda=sdn - dn;
      const sisaangsuran=q - angsuran - ttpn;
      state.payment.total_bayar_angsuran = totalbayar
      state.payment.sisa_angsuran = sisaangsuran
      state.payment.sisa_denda = sisadenda
    },
    SET_CALLCULATIONS_PAYMENT_D(state, payload) {
      let collectfee=parseInt(state.payment.collect_fee);
      let dendaangsuran=parseInt(state.payment.b_tertunggak);
      let denda=parseInt(state.payment.denda);
      let angsuran=parseInt(state.payment.angsuran);
      let titipan=parseInt(payload);
      let q=parseInt(state.payment.a_tertunggak);

      const colfe=( isNaN(collectfee) ) ? 0 : collectfee;
      const dn=( isNaN(denda) ) ? 0 : denda;
      const sdn=( isNaN(dendaangsuran) ) ? 0 : dendaangsuran;
      const ttpn=( isNaN(titipan) ) ? 0 : titipan;
      const totalbayar=angsuran + dn + colfe + ttpn;
      const sisadenda=sdn - dn;
      const sisaangsuran=q - angsuran - ttpn;
      state.payment.total_bayar_angsuran = totalbayar
      state.payment.sisa_angsuran = sisaangsuran
      state.payment.sisa_denda = sisadenda
    },
    SET_TAKEFOTO(state, payload) {
        state.activity.take_foto = payload
    },
    SET_VISITFOTO_1(state, payload) {
        state.visit_a.imageTempatTinggal1 = payload
    },
    SET_VISITFOTOJM_1(state, payload) {
        state.visit_b.imageJaminan1 = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_LOADING(state, payload) {
        state.loading = payload
    },
    ASSIGN_LOCATION(state, payload) {
        state.location = {
          lat:payload.lat,
          long:payload.long,
        }
    },
    CLEAR_LOCATION(state) {
        state.location = {
          lat:"",
          long:"",
        }
    },
    ASSIGN_FORM_ACTIVITY(state, payload) {
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
          invalid_no:payload.invalid_no,
          keterangan:payload.keterangan
        }
    },
    CLEAR_FORM_ACTIVITY(state) {
      state.activity = {
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
        invalid_no:"",
        keterangan:""
      }
    },
    ASSIGN_FORM_VISIT_A(state, payload) {
        state.visit_a = {
          task_code:payload.task_code,
          kondisi_tempat_tinggal:payload.kondisi_tempat_tinggal,
          latitude_tempat_tinggal:payload.latitude_tempat_tinggal,
          longitude_tempat_tinggal:payload.longitude_tempat_tinggal,
          imageTempatTinggal1:payload.imageTempatTinggal1
        }
    },
    CLEAR_FORM_VISIT_A(state) {
      state.visit_a = {
        task_code:"",
        kondisi_tempat_tinggal:"",
        latitude_tempat_tinggal:"",
        longitude_tempat_tinggal:"",
        imageTempatTinggal1:""
      }
    },
    ASSIGN_FORM_VISIT_B(state, payload) {
        state.visit_b = {
          task_code:payload.task_code,
          kondisi_jaminan:payload.kondisi_jaminan,
          latitude_jaminan:payload.latitude_jaminan,
          longitude_jaminan:payload.longitude_jaminan,
          imageJaminan1:payload.imageJaminan1
        }
    },
    CLEAR_FORM_VISIT_B(state) {
      state.visit_b = {
        task_code:"",
        kondisi_jaminan:"",
        latitude_jaminan:"",
        longitude_jaminan:"",
        imageJaminan1:""
      }
    },
    ASSIGN_FORM_PAYMENT(state, payload) {
        state.payment = {
          task_code:payload.task_code,
          nama:payload.nama,
          no_rek:payload.no_rek,
          os_pokok:payload.os_pokok,
          a_tertunggak:payload.a_tertunggak,
          b_tertunggak:payload.b_tertunggak,
          angsuran:payload.angsuran,
          bayar_denda:payload.bayar_denda,
          collect_fee:payload.collect_fee,
          titipan:payload.titipan,
          ang_ke:payload.ang_ke,
          tenor:payload.tenor,
          no_bss:payload.no_bss,
          total_bayar_angsuran:payload.total_bayar_angsuran,
          sisa_angsuran:payload.sisa_angsuran,
          sisa_denda:payload.sisa_denda,
          tgl_jt:payload.tgl_jt,
          take_foto:payload.take_foto,
          signature64Nasabah:payload.signature64Nasabah,
          signature64Collection:payload.signature64Collection
        }
    },
    CLEAR_FORM_PAYMENT(state) {
      state.payment = {
        task_code:"",
        nama:"",
        no_rek:"",
        os_pokok:"",
        a_tertunggak:"",
        b_tertunggak:"",
        angsuran:"",
        bayar_denda:"",
        collect_fee:"",
        titipan:"",
        ang_ke:"",
        tenor:"",
        no_bss:"",
        total_bayar_angsuran:"",
        sisa_angsuran:"",
        sisa_denda:"",
        tgl_jt:"",
        take_foto:"",
        signature64Nasabah:"",
        signature64Collection:""
      }
    },
}

const actions = {
    getAssigment({ commit, state }, payload) {
        commit('SET_LOADING', true)
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/assigment?page=${state.page}&q=${search}`)
            .then((response) => {
                commit('ASSIGN_DATA', response.data)
                commit('SET_LOADING', false)
                resolve(response.data)
            })
        })
    },
    getLocation({ commit, state }) {
      return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(
           position => {
             const location={
               lat:position.coords.latitude,
               long:position.coords.longitude
             };
             commit('ASSIGN_LOCATION',location)
           },
           error => {
             console.log(error.message);
           },
        )
      })
    },
    submitActivity({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
          let formData = new FormData();
              formData.append('task_code', state.activity.task_code);
              formData.append('kunjungan_ke', state.activity.kunjungan_ke);
              formData.append('bertemu', state.activity.bertemu);
              formData.append('karakter_debitur', state.activity.karakter_debitur);
              formData.append('total_penghasilan', state.activity.total_penghasilan);
              formData.append('kondisi_pekerjaan', state.activity.kondisi_pekerjaan);
              formData.append('asset_debt', state.activity.asset_debt);
              formData.append('janji_byr', state.activity.janji_byr);
              formData.append('tgl_janji_byr', state.activity.tgl_janji_byr);
              formData.append('case_category', state.activity.case_category);
              formData.append('next_action', state.activity.next_action);
              formData.append('invalid_no', state.activity.invalid_no);
              formData.append('keterangan', state.activity.keterangan);
              formData.append('take_foto', state.activity.take_foto);
              $axios.post(`/assigment-activity-store`, formData)
              .then((response) => {
                  dispatch('getAssigment').then(() => {
                      commit('ASSIGN_ACT', response.data.act)
                      resolve(response.data)
                  })
              })
              .catch((error) => {
                console.log(error);
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
              })
        })
    },
    submitVisitA({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
          let formData = new FormData();
            formData.append('task_code', state.visit_a.task_code);
            formData.append('kondisi_tempat_tinggal', state.visit_a.kondisi_tempat_tinggal);
            formData.append('latitude_tempat_tinggal', state.visit_a.latitude_tempat_tinggal);
            formData.append('longitude_tempat_tinggal', state.visit_a.longitude_tempat_tinggal);
            formData.append('imageTempatTinggal1', state.visit_a.imageTempatTinggal1);
            $axios.post(`/assigment-visit-a-store`, formData)
            .then((response) => {
                dispatch('getAssigment').then(() => {
                    if (response.data.status === 'success') {
                      commit('ASSIGN_ACT', response.data.act)
                    }
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
    submitVisitB({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
          let formData = new FormData();
            formData.append('task_code', state.visit_b.task_code);
            formData.append('kondisi_jaminan', state.visit_b.kondisi_jaminan);
            formData.append('latitude_jaminan', state.visit_b.latitude_jaminan);
            formData.append('longitude_jaminan', state.visit_b.longitude_jaminan);
            formData.append('imageJaminan1', state.visit_b.imageJaminan1);
            $axios.post(`/assigment-visit-b-store`, formData)
            .then((response) => {
                dispatch('getAssigment').then(() => {
                    commit('ASSIGN_ACT', response.data.act)
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
    submitPayment({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
          let formData = new FormData();
            formData.append('task_code', state.payment.task_code);
            formData.append('nama', state.payment.nama);
            formData.append('no_rek', state.payment.no_rek);
            formData.append('os_pokok', state.payment.os_pokok);
            formData.append('a_tertunggak', state.payment.a_tertunggak);
            formData.append('b_tertunggak', state.payment.b_tertunggak);
            formData.append('angsuran', state.payment.angsuran);
            formData.append('bayar_denda', state.payment.bayar_denda);
            formData.append('collect_fee', state.payment.collect_fee);
            formData.append('titipan', state.payment.titipan);
            formData.append('ang_ke', state.payment.ang_ke);
            formData.append('tenor', state.payment.tenor);
            formData.append('no_bss', state.payment.no_bss);
            formData.append('total_bayar_angsuran', state.payment.total_bayar_angsuran);
            formData.append('sisa_angsuran', state.payment.sisa_angsuran);
            formData.append('sisa_denda', state.payment.sisa_denda);
            formData.append('tgl_jt', state.payment.tgl_jt);
            formData.append('take_foto', state.payment.take_foto);
            formData.append('signature64Nasabah', state.payment.signature64Nasabah);
            formData.append('signature64Collection', state.payment.signature64Collection);
            $axios.post(`/assigment-payment-store`, formData)
            .then((response) => {
                dispatch('getAssigment').then(() => {
                    commit('ASSIGN_ACT', response.data.act)
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
    editAssigment({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/assigment/${payload}/edit`)
            .then((response) => {
              navigator.geolocation.getCurrentPosition(
                 position => {
                   const visit_a={
                     task_code:response.data.visit_a.task_code,
                     kondisi_tempat_tinggal:"",
                     latitude_tempat_tinggal:position.coords.latitude,
                     longitude_tempat_tinggal:position.coords.longitude,
                     imageTempatTinggal1:"",
                     imageTempatTinggal2:"",
                     imageTempatTinggal3:""
                   };
                   const visit_b={
                     task_code:response.data.visit_b.task_code,
                     kondisi_jaminan:"",
                     latitude_jaminan:position.coords.latitude,
                     longitude_jaminan:position.coords.longitude,
                     imageJaminan1:"",
                     imageJaminan2:"",
                     imageJaminan3:""
                   };
                   commit('ASSIGN_FORM_VISIT_A', visit_a)
                   commit('ASSIGN_FORM_VISIT_B', visit_b)
                 },
                 error => {
                   console.log(error.message);
                 },
              )
                commit('ASSIGN_FORM_ACTIVITY', response.data.activity)
                commit('ASSIGN_FORM_PAYMENT', response.data.payment)
                commit('ASSIGN_ACT', response.data.act)
                resolve(response.data)
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
