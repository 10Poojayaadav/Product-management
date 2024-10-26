import { createStore } from "vuex";
const user = localStorage.getItem("token");

const initialState = user ? { loggedIn: true } : { loggedIn: false };

export default createStore({
  namespaced: true,
  state: initialState,
  actions: {
    async login({ commit }) {
      commit("logMeIn");
    },

    async logout({ commit }) {
      commit("logMeOut");
    },
  },
  mutations: {
    logMeIn(state) {
      state.loggedIn = true;
    },
    logMeOut(state) {
      state.loggedIn = false;
    },
  },

  getters: {
    loggedIn(state) {
      return state.loggedIn;
    },
  },
});
