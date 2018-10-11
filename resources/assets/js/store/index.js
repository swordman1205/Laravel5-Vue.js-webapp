import Vue from 'vue'
import Vuex from 'vuex'
import globalModals from './modules/globalModals'
import reservation from './modules/reservation'
import createPersistedState from 'vuex-persistedstate'
import Cookies from 'js-cookie'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    globalModals,
    reservation
  },
  plugins: [createPersistedState({
    storage: {
      getItem: key => Cookies.get(key),
      setItem: (key, value) => Cookies.set(key, value, {expires: 3, secure: true}),
      removeItem: key => Cookies.remove(key)
    },
    paths:['reservation']
  })]
})
