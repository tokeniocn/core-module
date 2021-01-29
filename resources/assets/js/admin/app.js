import Vue from "vue";
import "./config";
import "./boot/quasar";
import "./boot/http";
import "./boot/plugins";
import "./boot/filter";
import "./boot/mixin";
import "./boot/component";
import store from "./store";
import "./boot/outer";

new Vue({
  ...window.initOptions,
  el: "#app",
  store,
});
