import { merge, cloneDeep } from "lodash";
import { mapStore } from "../utils/store";
import $config from "../config";

export default merge(
  {
    namespaced: true,
    state: { tree: null, tabs: null, activeTabId: null },
    mutations: {
      setTabs(state, tabs) {
        state.tabs = tabs;
      },
      setActiveTab(state, tab) {
        state.activeTabId = tab.id;
      },
    },
    getters: {
      tabs(state) {
        return state.tabs || [];
      },
      activeTab(state, getters) {
        const tab = getters.tabs.find((tab) => tab.id == state.activeTabId);
        return tab || getters.tabs[0] || {};
      },
    },
    actions: {
      toggleTab({ commit, getters }, tab) {
        if (!getters.tabs.find((getterTab) => getterTab.id == tab.id)) {
          commit("setTabs", [...getters.tabs, tab]);
        }
        commit("setActiveTab", tab);
      },
      async removeTab({ getters, commit }, tab) {
        const tabs = cloneDeep(getters.tabs);
        const index = tabs.length == 1 ? -1 : tabs.indexOf(tab);
        tabs.splice(index, 1);

        commit("setTabs", tabs);
      },
    },
  },
  mapStore("tree", {
    url: $config.api.menu.tree,

    getter: false,
    loadingKey: "menu.tree",
    options: {
      actionOptions: {
        commitType: "data",
      },
    },
  })
);
