import Vue from "vue";
import Vuex from "vuex";

import menu from "./menu";
import media from "./media";

// import example from './module-example'

Vue.use(Vuex);

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default new Vuex.Store({
  state: {
    notify: null,
    loading: null,
  },
  mutations: {
    setNotify(state, notify) {
      state.notify = notify;
    },
    setLoading(state, loading) {
      state.loading = loading;
    },
  },
  getters: {
    loading(state) {
      return state.loading || {};
    },
  },
  actions: {
    async showNotify({ commit }, options) {
      commit("setNotify", {
        ...(typeof options === "string"
          ? {
              message: options,
            }
          : options),
      });
    },
    async toggleLoading({ state, commit }, { key, loading }) {
      if (typeof loading === "function") {
        try {
          commit("setLoading", {
            ...state.loading,
            [key]: true,
          });
          return await loading();
        } finally {
          commit("setLoading", {
            ...state.loading,
            [key]: false,
          });
        }
      } else {
        commit("setLoading", {
          ...state.loading,
          [key]: typeof loading === "boolean" ? loading : !state.loading[key],
        });
      }
    },
  },
  modules: {
    menu,
    media,

    // example
  },

  // enable strict mode (adds overhead!)
  // for dev mode only
  strict: process.env.DEV,
});
