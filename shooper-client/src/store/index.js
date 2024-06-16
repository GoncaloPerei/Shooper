import { createStore } from 'vuex'

export default createStore({
  state: { authenticated: false },
  mutations: {
    SET_AUTH(state, auth) {
      state.authenticated = auth
    }
  },
  action: {
    setAuth({ commit }, auth) {
      commit('SET_AUTH', auth)
    }
  }
})
