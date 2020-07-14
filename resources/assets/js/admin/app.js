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

let inited = false;
const Init = (window.Init = (options = {}) => {
  let instance = options;
  if (typeof options === "function") {
    instance = options();
  } else if (isPlainObject(options)) {
    instance = new Vue({
      el: "#app",
      ...options,
      store,
    });
  }

  if (!(instance instanceof Vue)) {
    throw new Error("Page init failed");
  }
  inited = true;
  return instance;
});

// 在js加载完之后处理
setTimeout(() => inited || Init());
