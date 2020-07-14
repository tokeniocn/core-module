import Vue from "vue";
import { mapGetters, mapActions } from "vuex";
import { Notify } from "quasar";
import $config from "../config";
import $store from "../store";
import $http from "./http";
import { errorHandler } from "./handler";

// 全局监控store, 并响应
// TODO 界面层面的操作应当放入组件中实现?(组件中监控有优先级的问题, 目前这是最高优先级)
$store.watch(
  (state) => state.notify,
  (newVal, val) => {
    if (newVal) {
      Notify.create({
        message: "未知错误",
        position: "top",
        ...newVal,
      });
    }
  }
);

Vue.mixin({
  computed: {
    ...mapGetters({ hasLoading: "loading" }),

    // 全局配置
    $config: () => $config,
    $http: () => $http,
  },

  methods: {
    ...mapActions([
      "showNotify",
      "toggleLoading", // 切换loading状态
    ]),
    // 错误处理
    handleError: errorHandler,
  },
});
