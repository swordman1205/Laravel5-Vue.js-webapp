// initial state
const state = {
  login: {
    show: false
  },
  register: {
    show: false
  },
  callback: () => {
  },
  redirect: window.location,
  socialLogin: {
    facebookUrl: '/facebook-redirect',
    googleUrl: '/google-redirect',
    redirect: null
  },
}

// getters
const getters = {
  loginRedirect: state => state.redirect,
  facebookLogin: state => {
    if (state.socialLogin.redirect) {
      return state.socialLogin.facebookUrl + '?redirect=' + state.socialLogin.redirect;
    }
    return state.socialLogin.facebookUrl;
  },
  googleLogin: state => {
    if (state.socialLogin.redirect) {
      return state.socialLogin.googleUrl + '?redirect=' + state.socialLogin.redirect;
    }
    return state.socialLogin.googleUrl;
  }
}

// actions
const actions = {
  onShowLogin ({commit, state}) {
    commit('SET_LOGIN_SHOW', true)
  },
  onShowRegister ({commit, state}) {
    commit('SET_REGISTER_SHOW', true)
  },
  setRedirect ({commit, state}, url) {
    commit('SET_REDIRECT', url)
  },
  setLoginCallback ({commit, state}, callback) {
    commit('SET_CALLBACK', callback);
  },
  loginCallback ({state}) {
    return new Promise((resolve, reject) => {
      try {
        state.callback();
        resolve();
      }
      catch (error) {
        reject(error);
      }
    });
  },
  setSocialLoginRedirect ({commit}, redirect) {
    commit('SET_SOCIAL_LOGIN_REDIRECT', redirect)
  }
}

// mutations
const mutations = {
  SET_LOGIN_SHOW (state, boolean) {
    state.login.show = boolean;
  },
  SET_REGISTER_SHOW (state, boolean) {
    state.register.show = boolean;
  },
  SET_REDIRECT (state, redirect) {
    state.redirect = redirect;
  },
  SET_CALLBACK (state, callback) {
    state.callback = callback;
  },
  SET_SOCIAL_LOGIN_REDIRECT (state, redirect) {
    state.socialLogin.redirect = redirect;
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}
