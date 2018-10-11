// initial state
import createPersistedState from "vuex-persistedstate";
import Cookies from "js-cookie";

const state = {
  reservation: null,
}

// getters
const getters = {
  storedReservation: state => state.reservation
}

// actions
const actions = {
  setReservationState ({commit, state}, reservation) {
    commit('SET_RESERVATION_STATE', reservation)
  }
}

// mutations
const mutations = {
  SET_RESERVATION_STATE (state, reservation) {
    state.reservation = reservation;
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}
