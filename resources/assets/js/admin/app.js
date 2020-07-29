import { isPlainObject } from "lodash";
import Vue from "vue";
import "./config";
import "./boot/quasar";
import "./boot/http";
import "./boot/plugins";
import "./boot/mixin";
import "./boot/component";
import store from "./store";
import "./boot/outer";

let initOptions;
window.Init = (options = {}) => {
  if (!isPlainObject(options)) {
    throw new Error("Please input valid vue init options");
  }
  initOptions = options;
};

// 异步执行. 当所有同步js加载完毕后初始化
setTimeout(
  () =>
    new Vue({
      ...initOptions,
      el: "#app",
      store,
    })
);
