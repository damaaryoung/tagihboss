import $axios from '../api.js'

const state = () => ({

})

const mutations = {

}

const actions = {
    submit({ commit }, payload) {
        const cekToken = localStorage.getItem('token');
        if(cekToken){
          localStorage.removeItem('token');
        }
        localStorage.setItem('token', null)
        commit('SET_TOKEN', null, { root: true })
        return new Promise((resolve, reject) => {
            $axios.post('/login', payload)
            .then((response) => {
                if (response.status == 200) {
                    localStorage.setItem('token', response.data.token)
                    commit('SET_TOKEN', response.data.token, { root: true })
                } else {
                    commit('SET_ERRORS', { invalid: 'Email/Password Salah' }, { root: true })
                }
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    logout({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`logout`)
            .then((response) => {
              if (response.status == 200) {
                  localStorage.removeItem('token')
                  commit('SET_TOKEN', null, { root: true })
              }
              resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
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
