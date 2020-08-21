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

new Vue({
  ...initOptions,
  el: "#app",
  store,
});
